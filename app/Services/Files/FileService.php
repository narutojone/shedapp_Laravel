<?php

namespace App\Services\Files;

use Storage;
use Store;
use Carbon\Carbon;
use Exception;
use Uuid;
use Log;
use Auth;
use Entrust;

use App\Models\File;

class FileService
{
    public $messages;
    public $errors;
    public $files;

    public function store($uploadedFiles, $storable) {
        if (is_array($uploadedFiles)) {
            foreach ($uploadedFiles as $uploadedFile) {
                $this->save($uploadedFile, $storable);
            }
        } else {
            $this->save($uploadedFiles, $storable);
        }

        return $this;
    }

    public function save($uploadedFile, $storable) {
        if (!$uploadedFile->isValid())
            return response()->json($uploadedFile->getErrorMessage());

        $storable['name'] = $uploadedFile->getClientOriginalName();
        $storable['ext'] = $uploadedFile->getClientOriginalExtension();
        $this->add($uploadedFile->getRealPath(), $storable);
        return $this;
    }

    /**
     * Add file to storage, based on filepath and storable data
     * @param $filePath
     * @param $storable
     * @param bool $overwrite
     * @return $this
     */
    public function add($filePath, $storable, $overwrite = false) {
        $userId = $storable['user_id'];
        $storableKey = $storable['key'];
        $storableType = $storable['type'];
        $storableId = $storable['id'];
        $categoryId = $storable['category_id'] ?? null;
        $fileName = $storable['name'] ?? substr($filePath, strrpos($filePath, '/') + 1);

        try {
            $fs = new \Symfony\Component\HttpFoundation\File\File($filePath, true);
            $origFileName = $storable['name'] ?? $fs->getFilename();
            $fileExt = $storable['ext'] ?? $fs->getExtension();
            $fileSize = $fs->getSize();
            $mime = $fs->getMimeType();
            
            // optimize pdf within ghost script (realy required for hellosign pdf - x10 size after esigns + SN timestamp campatibility)
            if ($fileExt === 'pdf') {
                $fs = $this->optimizePDF($filePath);
                $origFileName = $storable['name'] ?? $fs->getFilename();
                $fileExt = $storable['ext'] ?? $fs->getExtension();
                $fileSize = $fs->getSize();
                $mime = $fs->getMimeType();
            }

            $dirPath = "/{$storableType}/{$storableKey}/";
            $fileDir = storage_path('app/public').$dirPath;
            $fileName = $origFileName;
            $fullPath = $fileDir.$fileName;
            
            Storage::makeDirectory('public'.$dirPath);
            $fs->move($fileDir, $fileName);

            $type = explode('/', $mime);
            $type = ($type[0] === 'application') ? $type[1] : $type[0];

            if ($overwrite) {
                $found = File::where('path', $dirPath)
                    ->where('name', $fileName)
                    ->where('storable_id', $storableId)
                    ->where('storable_type', $storableType)
                    ->first();
                if ($found) {
                    $file = $found;
                    $file->updated_at = date('Y-m-d H:i:s');
                }
            }

            $file = $file ?? new File;
            $file->user_id = $userId;
            $file->storable_id = $storableId;
            $file->storable_type = $storableType;
            $file->category_id = $categoryId;
            $file->type = $type;
            $file->mime = $mime;
            $file->path = $dirPath;
            $file->name = $fileName;
            $file->ext = $fileExt;
            // $file->description = null;
            // $file->source_id = null;
            // $file->reason = null;
            $file->size = $fileSize;

            if ($type === 'image') {
                $dimensions = getimagesize($fullPath);
                $file->width = $dimensions[0];
                $file->height = $dimensions[1];
            }

            $file->save();

            $this->messages[] = "File \"{$origFileName}\" successfully added.";
            $this->files[] = $file;
            return $this;
        } catch (Exception $e) {
            Log::error($e);
            $this->errors[] = "File \"{$fileName}\" has not been added.";
            return $this;
        }

        $this->errors[] = "File \"{$fileName}\" has not been added.";
        return $this;
    }

    public function delete($files) {
        if (!is_array($files)) {
            $files = [$files];
        }
        
        foreach ($files as $file) {
            if ($file->storable_type === 'order') {
                if(in_array($file->storable->status_id, ['draft', 'review_needed']) || Entrust::hasRole('administrator')) {
                    $file->delete(); // safe delete (without drop from hard drive for now)
                    $this->messages[] = "File \"{$file->name}\" successfully removed.";
                } else {
                    $this->errors[] = "You have no privilege to remove \"{$file->name}\" file.";
                }
            } else {
                if (Entrust::hasRole('administrator')) {
                    $file->delete();
                } else {
                    $this->errors[] = "You have no privilege to remove \"{$file->name}\" file.";
                }
            }   
        }
        
        return $this;
    }
    
    public function success() {
        return count($this->errors) === 0;
    }
    
    public function error() {
        return count($this->errors) !== 0;
    }

    protected function optimizePDF($filePath) {
        $tmpfname = tempnam("/tmp", "FOO");
        if (!exec("gs -dAutoRotatePages=/None -sDEVICE=pdfwrite -sOutputFile='{$tmpfname}' -dNOPAUSE -dBATCH '{$filePath}'"))
            throw new Exception("PDF optimization failed.");

        return new \Symfony\Component\HttpFoundation\File\File($tmpfname, true);
    }
}

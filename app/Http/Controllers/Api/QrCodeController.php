<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingStatus;
use App\Models\File;

use App\Repositories\Qrcode\QrCodeRepository; 
use App\Http\Requests\Qrcode\CreateQrCodeRequest;
use App\Http\Requests\Qrcode\UpdateStatusRequest;
use App\Http\Requests\Qrcode\UploadFile;
use App\Http\Requests\Qrcode\ScanIdentifier;

use App\Repositories\BuildingRepository; 
use App\Services\Building\BuildingService;

class QrcodeController extends Controller
{   
    /**
     * @var QrCodeRepository
     */
    protected $qrcode;

    /**
     * @var BuildingRepository
    */
    protected $building;

    /**
     * @param QrCodeRepository $qrcode BuildingRepository $building
     */
    public function __construct(QrCodeRepository $qrcode , BuildingRepository $building){
        $this->qrcode = $qrcode;
        $this->building = $building;
    }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->building_id){
            $item =  $this->qrcode->get(
                $request->building_id , 
                $request->type
                );
            return response()->json($item);
        }else{
            return response()->json(['msg'=>'Something went wrong'], 422);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQrCodeRequest $request)
    {
        //
        try{
            if($this->qrcode->create(['data' =>$request->all()])){
                return response()->json([
                    'msg' => 'QR Code generated successfully.'
                    ]); 
            }
            return response()->json(['msg'=>'Building in draft status'], 422);
        }
        catch(Exception $e){
            \Log::error($e);
            return response()->json(['msg'=>'Something went wrong'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdentifier(ScanIdentifier $request){
        $item =  $this->qrcode
        ->getdeepByIdentifier($request->identifier);
        //dd($item);
        $image_count = $this->_getImagesCount($item->building->last_status->status_id);
        $status =  $this->building->getToPrioritize($item->building_id);

        return response()->json(array('item'=>$item,'status'=>$status,'images_count'=>$image_count));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function postStatus(UpdateStatusRequest $request , BuildingService $buildingService){
        try{
                //save image here 
            if($request->file('upload_files')){
                $buildingData['files'] = $request->file('upload_files') ?? null;
                $buildingData['category_id'] = $this->_getStatusName($request->status_id);
                $building = Building::find($request->building_id);
                $buildingService->saveFiles($building ,$buildingData);
            }
            $this->building
            ->saveStatus($request->all());
            return response()->json([
                'msg' => 'Status updated successfully.'
                ]);
        }
        catch(Exception $e){
            \Log::error($e);
            return response()->json(['msg'=>'Something went wrong'], 422);
        }
    }

    /**
     * Store a newly created resource in storage | upload building images to.
     *
     * @return \Illuminate\Http\Response
     */
    public function postFiles(UploadFile $request , BuildingService $buildingService){
        try{
            if($request->file('upload_files')){
                $buildingData['files'] = $request->file('upload_files') ?? null;
                $buildingData['category_id'] = $this->_getStatusName($request->status_id);
                $building = Building::find($request->building_id);
                if($building){
                    $buildingService->saveFiles($building ,$buildingData);
                    return response()->json([
                        'msg' => 'Image uploaded successfully.'
                        ]);
                }else{
                    return response()->json(['msg'=>'Something went wrong'], 422);
                }
            }
        }catch(Exception $e){
            \Log::error($e);
            return response()->json(['msg'=>'Something went wrong'], 422);
        }

    }

    /**
     * get status name and convert spaces with _ .
     *
     * @return \Illuminate\Http\Response
     */
    protected function _getStatusName(int $status_id){
        $status_name =  BuildingStatus::find($status_id)->name;
        return preg_replace('/\s+/', '_', strtolower($status_name));
    }

    /**
     * get images count.
     *
     * @return \Illuminate\Http\Response
     */

    protected function _getImagesCount(int $status_id){
        $status =  BuildingStatus::find($status_id);
        $next_priority = $status->priority + 1 ;
        $new_status_id =  BuildingStatus::where('priority',$next_priority)->first();
        if($new_status_id){
            $new_status_id = $new_status_id->id;
        }else{
            $new_status_id = $status_id;   
        }
        $category_id = $this->_getStatusName($new_status_id);
        return File::where('category_id',$category_id)->count();
    }

}

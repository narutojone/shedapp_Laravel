<?php

namespace App\Http\Controllers\Api;

use Log;
use Exception;
use Store;
use DB;
use Auth;

use App\Models\Material;

use App\Validators\Validator;
use App\Http\Requests;
use App\Http\Requests\Materials\IndexMaterialRequest;
use App\Http\Requests\Materials\AddMaterialRequest;
use App\Http\Requests\Materials\UpdateMaterialRequest;
use App\Http\Requests\Materials\DeleteMaterialRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get all materials
     * @param IndexMaterialRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexMaterialRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Material());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        return response()->json($result);
    }

    /**
     * Get resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Material());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Material is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddMaterialRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddMaterialRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $materialParams = $request->all();

                $material = Material::create($materialParams);
                if ($material->id) {
                    $material_categories = $request->input('material_categories_id') ?? [];
                    $material->material_categories()->sync($material_categories);

                    $allowableModels = $request->input('allowable_models_id') ?? [];
                    $material->allowable_models()->sync($allowableModels);

                    $allowableColors = $request->input('allowable_colors_id') ?? [];
                    $material->allowable_colors()->sync($allowableColors);
                }
            });
            return response()->json(['Material successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMaterialRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $material = Store::get('material');
                $materialParams = $request->all();

                $material->update($materialParams);
                if ($material->id) {
                    $material_categories = $request->input('material_categories_id') ?? [];
                    $material->material_categories()->sync($material_categories);

                    $allowableModels = $request->input('allowable_models_id') ?? [];
                    $material->allowable_models()->sync($allowableModels);

                    $allowableColors = $request->input('allowable_colors_id') ?? [];
                    $material->allowable_colors()->sync($allowableColors);
                }
            });

            return response()->json(['Material successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteMaterialRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteMaterialRequest $request)
    {
        try
        {
            // get data which has got through validator
            $material = Store::get('material');
            $material->delete();
            return response()->json(['Material successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = Material::$isActive;

        return response()->json($isActiveFlags);
    }
}

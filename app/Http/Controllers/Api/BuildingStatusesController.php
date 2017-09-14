<?php

namespace App\Http\Controllers\Api;

use DB;
use Event;
use Validator;

use App\Models\Building;
use App\Http\Requests;
use App\Models\BuildingStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\IndexBuildingStatusRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Repositories\BuildingRepository; 

class BuildingStatusesController extends Controller
{
    
    protected $building;

    public function __construct(BuildingRepository $building)
    {
        $this->building =  $building;
    }

    /**
     * Get all options
     * @param IndexBuildingStatusRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexBuildingStatusRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new BuildingStatus());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        return response()->json($result);
    }
    
    /**
     * Get building statuses by type
     * @param Request $request
     * @return array
     */
    public function getByType(Request $request)
    {
        $statusType = $request->route('type');
        $validator = Validator::make(['type' => $statusType], ['type' => 'required|in:build,sale']);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        try
        {
            $buildingStatuses = BuildingStatus::active()
                ->select('id', 'type', 'name', 'priority')
                ->where('type', $statusType)
                ->orderBy('priority', 'asc')
                ->get();

            $buildingStatuses->keyBy('id');

            $response = $buildingStatuses;
        } catch (ModelNotFoundException $e)
        {
            $response = ['status' => 'error', 'message' => 'No building status matches the provided type'];
        }

        return response()->json($response);
    }

    /**
     * Get building statuses by type
     * @param Request $request
     * @return array
     */
    public function getToPrioritize(Request $request)
    {
        $buildingId = $request->route('building_id');
        $validator = Validator::make([
            'building' => $buildingId,
        ], [
            'building' => 'required|numeric|exists:buildings,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) return response()->json($validator->errors()->all());

        $buildingStatusesSelect =  $this->building->getToPrioritize($buildingId);
        
        return response()->json($buildingStatusesSelect);
    }
}

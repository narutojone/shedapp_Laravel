<?php

namespace App\Http\Controllers\Api;

use App\Events\BuildingLocationWasAdded;

use App\Models\Building;
use App\Models\BuildingLocation;
use App\Models\Location;
use App\Models\Delivery;

use Validator;
use Event;
use DB;
use Log;
use Auth;
use Store;
use Exception;
use Carbon\Carbon;

use App\Http\Requests\Deliveries\AddDeliveryRequest;
use App\Http\Requests\Deliveries\UpdateDeliveryRequest;
use App\Http\Requests\Deliveries\DeleteDeliveryRequest;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class DeliveriesController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the model statuses.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function statuses(Request $request)
    {
        $deliveryStatuses = Delivery::$statuses;

        return response()->json($deliveryStatuses);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Delivery());
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
        $abAssistant->setModel(new Delivery());
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
            return response()->json(['Delivery is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddBillRequest|AddDeliveryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddDeliveryRequest $request)
    {
        try
        {
            $deliveryParams = $request->only([
                'sale_id',
                'building_id',
                'location_start_id',
                'location_end_id',
                'ready_date',
                'scheduled_date',
                'confirmed_date',
                'date_start',
                'date_end',
                'length',
                'price',
                'cost',
                'invoice',
                'notes',
            ]);

            if ($deliveryParams['ready_date'] !== null)
                $deliveryParams['ready_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['ready_date'])->format('Y-m-d');
            if ($deliveryParams['scheduled_date'] !== null)
                $deliveryParams['scheduled_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['scheduled_date'])->format('Y-m-d');
            if ($deliveryParams['confirmed_date'] !== null)
                $deliveryParams['confirmed_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['confirmed_date'])->format('Y-m-d');
            if ($deliveryParams['date_start'] !== null)
                $deliveryParams['date_start'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['date_start'])->format('Y-m-d');
            if ($deliveryParams['date_end'] !== null)
                $deliveryParams['date_end'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['date_end'])->format('Y-m-d');

            $deliveryParams['user_id'] = Auth::user()->id;
            $deliveryParams['status_id'] = 'pending';
            $delivery = Delivery::create($deliveryParams);
            return response()->json(['Delivery successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDeliveryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeliveryRequest $request, $id)
    {
        // get data which has got through validator
        $delivery = Store::get('delivery');
        try
        {
            $deliveryParams = $request->only([
                'status_id',
                'sale_id',
                'building_id',
                'location_start_id',
                'location_end_id',
                'ready_date',
                'scheduled_date',
                'confirmed_date',
                'date_start',
                'date_end',
                'length',
                'price',
                'cost',
                'invoice',
                'notes',
            ]);

            if ($deliveryParams['ready_date'] !== null)
                $deliveryParams['ready_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['ready_date'])->format('Y-m-d');
            if ($deliveryParams['scheduled_date'] !== null)
                $deliveryParams['scheduled_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['scheduled_date'])->format('Y-m-d');
            if ($deliveryParams['confirmed_date'] !== null)
                $deliveryParams['confirmed_date'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['confirmed_date'])->format('Y-m-d');
            if ($deliveryParams['date_start'] !== null)
                $deliveryParams['date_start'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['date_start'])->format('Y-m-d');
            if ($deliveryParams['date_end'] !== null)
                $deliveryParams['date_end'] = Carbon::createFromFormat('Y-m-d', $deliveryParams['date_end'])->format('Y-m-d');

            DB::transaction(function () use ($delivery, $deliveryParams, $request) {
                $delivery->update($deliveryParams);

                $assocEndLocation = $request->input('assoc_end_location');
                if ($assocEndLocation && $delivery->location_end_id) {

                    $buildingLocation = new BuildingLocation([
                        'location_id' => $delivery->location_end_id,
                        'user_id' => Auth::user()->id
                    ]);

                    $delivery->building->building_locations()->save($buildingLocation);
                    // Fire building location was added
                    Event::fire(new BuildingLocationWasAdded($buildingLocation));

                }
            });

            return response()->json(['Delivery successfully updated.']);
        } catch (QueryException $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteDeliveryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteDeliveryRequest $request, $id)
    {
        try
        {
            // get data which has got through validator
            $delivery = Store::get('delivery');
            $delivery->delete();
            return response()->json(['Delivery successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}

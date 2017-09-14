<?php

namespace App\Http\Controllers;

use Event;

use App\Models\Option;
use App\Models\OptionPackage;
use App\Models\BuildingModel;
use App\Http\Requests\AddOptionPackageRequest;
use App\Http\Requests\EditOptionPackageRequest;
use App\Http\Requests\UpdateOptionPackageRequest;
use App\Http\Requests\DeleteOptionPackageRequest;

use App\Events\OptionPackageWasUpdated;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OptionPackagesController extends Controller
{
    private $route_name = 'option_packages';

    private $params = [];

    public function __construct()
    {
        $this->params = [
            'breadcrumbs' => [['url' => '/', 'page' => 'Dashboard'], ['url' => $this->route_name, 'page' => 'Option Packages']],
            'title' => 'Option Packages',
            'subtitle' => 'Option Packages',
            'search' => 'yes',
            'filter' => '',
            'items_per_page' => 500,
            'route_name' => $this->route_name,
            'route' => '/' . $this->route_name,
            'data' => null,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->params['filter'] = $request->input('filter');
        $this->params['data']['items'] = OptionPackage::filteredPaginate($this->params['filter'], $this->params['items_per_page']);

        return view($this->route_name . '.index')->withParams($this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'New Option Package']);

        $buildingModels = BuildingModel::active()->get();

        $options[0] = ['id' => 0, 'name' => 'None', 'unit_price' => 0, 'disabled' => true];
        Option::all()->each(function($option, $key) use (&$options) {
            $options[$option->id] = [
                'id' => $option->id,
                'name' => $option->name.' ($'.$option->unit_price.')',
                'unit_price' => $option->unit_price
            ];
        });

        $this->params['subtitle'] = 'New Option Package';
        $this->params['data']['options'] = $options;
        $this->params['data']['allowable_models'] = $buildingModels;
        return view($this->route_name . '._form')->withParams($this->params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddOptionPackageRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddOptionPackageRequest $request)
    {
        try
        {
            \DB::transaction(function () use ($request) {
                $optionPackageData = $request->all();
                $optionPackage = OptionPackage::create($optionPackageData);

                if( isset($optionPackageData['allowable_models']))
                {
                    $optionPackage->allowable_models()->attach($optionPackageData['allowable_models']);
                }

                if( isset($optionPackageData['options']) )
                {
                    foreach($optionPackageData['options'] as $key => $option_id)
                    {
                        if(isset($optionPackageData['options_price'][$key]))
                            $optionPackage->options()->attach($option_id, [
                                'unit_price' => $optionPackageData['options_price'][$key]
                            ]);
                        else
                            $optionPackage->options()->attach($option_id);
                    }
                }

                // Fire building location was added
                Event::fire(new OptionPackageWasUpdated($optionPackage));
            });
        } catch (QueryException $e)
        {
            return redirect($this->route_name)->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error saving the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Option package added succcessfully.']);
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
     * @param EditOptionPackageRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditOptionPackageRequest $request, $id)
    {
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'Edit Option Package']);

        // get data which has got through validator
        $requestedOptionPackage = $request->validator->getStore('requestedOptionPackage');
        $requestedOptionPackage->load('options', 'allowable_models');
        $buildingModels = BuildingModel::active()->get();

        $options[0] = ['id' => 0, 'name' => 'None', 'unit_price' => 0, 'disabled' => true];
        Option::all()->each(function($option, $key) use (&$options) {
            $options[$option->id] = [
                'id' => $option->id,
                'name' => $option->name.' ($'.$option->unit_price.')',
                'unit_price' => $option->unit_price
            ];
        });

        $this->params['data']['item'] = $item = $requestedOptionPackage;
        $this->params['data']['options'] = $options;
        $this->params['data']['allowable_models'] = $buildingModels;

        $this->params['subtitle'] = 'Edit Option Package ' . $item->name;
        return view($this->route_name . '._form')->withParams($this->params);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateOptionPackageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOptionPackageRequest $request, $id)
    {
        try
        {
            \DB::transaction(function () use ($request) {
                // get data which has got through validator
                $requestedOptionPackage = $request->validator->getStore('requestedOptionPackage');

                $optionPackageData = $request->all();
                $requestedOptionPackage->update($optionPackageData);
                $requestedOptionPackage->options()->detach();

                if( isset($optionPackageData['allowable_models']))
                {
                    $requestedOptionPackage->allowable_models()->sync($optionPackageData['allowable_models']);
                }

                if( isset($optionPackageData['options']) )
                {
                    foreach($optionPackageData['options'] as $key => $option_id)
                    {
                        if(isset($optionPackageData['options_price'][$key]))
                            $requestedOptionPackage->options()->attach($option_id, [
                                'unit_price' => $optionPackageData['options_price'][$key]
                            ]);
                        else
                            $requestedOptionPackage->options()->attach($option_id);
                    }
                }

                // Fire building location was added
                Event::fire(new OptionPackageWasUpdated($requestedOptionPackage));
            });
        }
        catch (QueryException $e)
        {
            return redirect($this->route_name)->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Option package successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteOptionPackageRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOptionPackageRequest $request, $id)
    {
        try
        {
            // get data which has got through validator
            $optionPackage = $request->validator->getStore('requestedOptionPackage');
            $optionPackage->delete();
        }
        catch (QueryException $e)
        {
            return redirect($this->route_name)->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Option package successfully deleted.']);
    }
}

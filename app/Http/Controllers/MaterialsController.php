<?php

namespace App\Http\Controllers;

use Exception;
use Log;
use Store;
use App\Models\Color;
use App\Models\Option;
use App\Models\Material;
use App\Models\BuildingModel;
use App\Models\MaterialCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Materials\AddMaterialRequest;
use App\Http\Requests\Materials\EditMaterialRequest;
use App\Http\Requests\Materials\UpdateMaterialRequest;
use App\Http\Requests\Materials\DeleteMaterialRequest;

class MaterialsController extends Controller
{

    private $route_name = 'materials';

    private $params = [];

    public function __construct()
    {
        $this->params = [
            'breadcrumbs' => [
                ['url' => '/', 'page' => 'Materials'],
                ['url' => $this->route_name, 'page' => 'Materials']
            ],
            'title' => 'Materials',
            'subtitle' => 'Materials',
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
        $this->params['data']['items'] = Material::filteredPaginate($this->params['filter'], $this->params['items_per_page']);

        return view($this->route_name . '.index')->withParams($this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'New Material']);
        $this->params['subtitle'] = 'New Material';

        return view($this->route_name . '._form')->withParams($this->params);
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
            $inputs = $request->all();
            $material = Material::create($inputs);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error saving the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material added succcessfully.']);
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
     * @param EditMaterialRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditMaterialRequest $request,  $id)
    {
        // get data which has got through validator
        $material = Store::get('material');

        $this->params['data']['item'] = $item = $material;
        $this->params['subtitle'] = 'Edit Material ' . $item->title;
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'Edit Material ' . $item->title]);
        return view($this->route_name . '._form')->withParams($this->params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMaterialRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialRequest $request, $id)
    {
        // get data which has got through validator
        $material = Store::get('material');
        try
        {
            $inputs = $request->only(['name', 'description', 'category', 'option_id']);
            $material->update($inputs);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect()->back()->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteMaterialRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteMaterialRequest $request, $id)
    {
        try
        {
            // get data which has got through validator
            $material = Store::get('material');
            $material->delete();
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material successfully deleted.']);
    }
}

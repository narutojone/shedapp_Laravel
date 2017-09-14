<?php

namespace App\Http\Controllers;

use Store;
use Illuminate\Http\Request;
use App\Models\MaterialCategory;

use App\Http\Requests\AddMaterialCategoryRequest;
use App\Http\Requests\EditMaterialCategoryRequest;
use App\Http\Requests\UpdateMaterialCategoryRequest;
use App\Http\Requests\DeleteMaterialCategoryRequest;

class MaterialCategoriesController extends Controller
{
    private $route_name = 'material_categories';

    private $params = [];

    public function __construct()
    {
        $this->params = [
            'breadcrumbs' => [
                ['url' => '/', 'page' => 'Material Categories'],
                ['url' => $this->route_name, 'page' => 'Material Categories']
            ],
            'title' => 'Material Categories',
            'subtitle' => 'Material Categories',
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->params['filter'] = $request->input('filter');
        $this->params['data']['items'] = MaterialCategory::filteredPaginate($this->params['filter'], $this->params['items_per_page']);

        return view($this->route_name . '.index')->withParams($this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'New Material Category']);

        $this->params['subtitle'] = 'New Material Category';

        return view($this->route_name . '._form')->withParams($this->params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddMaterialCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddMaterialCategoryRequest $request)
    {
        try
        {
            $inputs = $request->all();
            MaterialCategory::create($inputs);
        }
        catch (Exception $e)
        {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error saving the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material Category added succcessfully.']);
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
     * @param  EditMaterialCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditMaterialCategoryRequest $request,  $id)
    {
        // get data which has got through validator
        $material_category = Store::get('material_category');

        $this->params['data']['item'] = $item = $material_category;
        $this->params['subtitle'] = 'Edit Material Category ' . $item->title;
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'Edit Material Category ' . $item->title]);
        return view($this->route_name . '._form')->withParams($this->params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateMaterialCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialCategoryRequest $request, $id)
    {
        // get data which has got through validator
        $materialCategory = Store::get('material_category');
        try
        {
            $inputs = $request->only(['name']);
            $materialCategory->update($inputs);
        }
        catch (Exception $e)
        {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material Category successfully updated.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteMaterialCategoryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteMaterialCategoryRequest $request, $id)
    {
        try
        {
            // get data which has got through validator
            $materialCategory = Store::get('material_category');
            $materialCategory->delete();
        }
        catch (Exception $e)
        {
            Log::error($e);
            return redirect()->back()->withMessages(['title' => 'Error', 'type' => 'error', 'text' => 'Sorry, there was an error updating the record.']);
        }

        return redirect($this->route_name)->withMessages(['title' => 'Success', 'type' => 'success', 'text' => 'Material Category successfully deleted.']);
    }
}

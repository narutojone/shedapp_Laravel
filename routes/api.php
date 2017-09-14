<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// dealers
Route::get('dealers/active-flags', 'Api\DealersController@activeFlags');
Route::get('dealers/per-id', 'Api\DealersController@perId');

Route::get('orders/statuses', 'Api\OrdersController@statuses');
Route::get('orders/payment-types', 'Api\OrdersController@paymentTypes');
Route::get('orders/order-types', 'Api\OrdersController@orderTypes');
Route::get('order-references/learning-about-us', 'Api\OrderReferencesController@learningAboutUs');
Route::get('sales/statuses', 'Api\SalesController@statuses');

Route::get('building-packages/active-flags', 'Api\BuildingPackagesController@activeFlags');
Route::get('building-package-categories/active-flags', 'Api\BuildingPackageCategoriesController@activeFlags');

// buildings
Route::get('buildings/per-id', 'Api\BuildingsController@perId');
Route::get('buildings/inventory', 'Api\BuildingsController@indexDealerInventory');
Route::get('buildings/export-csv', 'Api\BuildingsController@exportCsv');
Route::get('buildings/export-xls', 'Api\BuildingsController@exportXls');
// building models
Route::get('building-models/active-flags', 'Api\BuildingModelsController@activeFlags');
// colors
Route::get('colors/active-flags', 'Api\ColorsController@activeFlags');
Route::get('colors/types', 'Api\ColorsController@types');
Route::get('colors/options', 'Api\ColorsController@options');
Route::get('colors/building-models', 'Api\ColorsController@buildingModels');

// materials
Route::get('materials/active-flags', 'Api\MaterialsController@activeFlags');

// options
Route::get('options/active-flags', 'Api\OptionsController@activeFlags');
Route::get('options/force-quantity-flags', 'Api\OptionsController@forceQuantityFlags');
Route::get('options/categories', 'Api\OptionsController@categories');
Route::get('options/{option_id}/files', 'Api\OptionsController@getFiles')->where('option_id', '[0-9]+');

Route::get('option-categories/groups', 'Api\OptionCategoriesController@groups');

// styles
// Route::get('styles', 'Api\StylesController@index');
Route::get('styles/active-flags', 'Api\StylesController@activeFlags');

// locations
Route::get('locations/categories', 'Api\LocationsController@categories');
Route::get('locations/active-flags', 'Api\LocationsController@activeFlags');

//orders export
Route::get('orders/export-csv', 'Api\OrdersController@exportCsv');
Route::get('orders/export-xls', 'Api\OrdersController@exportXls');

//sales export
Route::get('sales/export-csv', 'Api\SalesController@exportCsv');
Route::get('sales/export-xls', 'Api\SalesController@exportXls');

Route::get('settings/per-id', 'Api\SettingsController@perId');

Route::group(['middleware' => 'uscguard'], function() {
    Route::get('deliveries/statuses', 'Api\DeliveriesController@statuses');
    Route::resource('users', 'Api\UsersController');
    Route::resource('plants', 'Api\PlantsController');
    Route::resource('dealers', 'Api\DealersController');
    Route::resource('buildings', 'Api\BuildingsController');
    Route::resource('building.history', 'Api\BuildingHistoryController');
    Route::resource('building.locations', 'Api\BuildingLocationsController');
    Route::resource('building-models', 'Api\BuildingModelsController');
    Route::resource('options', 'Api\OptionsController');
    Route::resource('colors', 'Api\ColorsController');
    Route::resource('settings', 'Api\SettingsController');
    Route::resource('styles', 'Api\StylesController');
    Route::resource('option-categories', 'Api\OptionCategoriesController');
    Route::resource('materials', 'Api\MaterialsController');
    Route::resource('deliveries', 'Api\DeliveriesController');
    Route::resource('locations', 'Api\LocationsController');
    Route::resource('orders', 'Api\OrdersController');
    Route::resource('order-references', 'Api\OrderReferencesController');
    Route::resource('sales', 'Api\SalesController');
    Route::post('sales/send-email/{id}', 'Api\SalesController@sendEmail');
    Route::resource('employees', 'Api\EmployeesController');
    Route::resource('building-packages', 'Api\BuildingPackagesController');
    Route::resource('building-package-categories', 'Api\BuildingPackageCategoriesController');
    Route::get('buildings/{building_id}/files', 'Api\BuildingsController@getFiles')->where('building_id', '[0-9]+');
});

Route::group(['middleware' => ['auth','qraccess'] ], function() {

    Route::post('qrcodes/files', 'Api\QrCodeController@postFiles');
    Route::post('qrcodes/status', 'Api\QrCodeController@postStatus');
    Route::get('qrcodes/getbyidentifier', 'Api\QrCodeController@getbyIdentifier');
    Route::resource('qrcodes', 'Api\QrCodeController');
});


Route::get('files/categories', 'Api\FilesController@categories');

Route::resource('files', 'Api\FilesController');
Route::resource('colors-use/{use_flag}/', 'Api\ColorsController@colorsByUsage');

// orders
Route::get('orders/get-dealer-order/{id}', 'Api\OrdersController@getDealerOrder');
Route::post('orders/save-dealer-order', 'Api\OrdersController@saveDealerOrder');
Route::post('orders/update-reason-note', 'Api\OrdersController@updateReasonNote');
Route::post('orders/customer-order', 'Api\OrdersController@customerOrder');
Route::post('orders/search', 'Api\OrdersController@search');
Route::get('orders/{order_uuid}/generate-document/{category_id}', 'Api\OrdersController@generateDocument');
Route::get('orders/{order_uuid}/files', 'Api\OrdersController@files')->where('order_uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


Route::post('similar-inventory', 'Api\BuildingsController@similarInventory');
Route::get('dealer-inventory/{dealer_id}/{building_serial}', 'Api\BuildingsController@dealerInventory');
Route::get('dealer-inventory-search/', 'Api\BuildingsController@dealerInventorySearch');
Route::post('buildings/import', 'Api\BuildingsController@import')->name('api.buildings.import');

Route::get('building-statuses/type/{type}', 'Api\BuildingStatusesController@getByType');
Route::get('building-statuses/to-prioritize/{building_id}', 'Api\BuildingStatusesController@getToPrioritize');
Route::get('building-statuses', 'Api\BuildingStatusesController@index');
Route::get('building-models', 'Api\BuildingModelsController@index');
Route::get('building-packages', 'Api\BuildingPackagesController@index');

// dealer-order-form
Route::get('options', 'Api\OptionsController@index');
Route::get('options-categories', 'Api\OptionCategoriesController@index');
Route::get('materials', 'Api\MaterialsController@index');
Route::get('colors', 'Api\ColorsController@index');
Route::get('dealers', 'Api\DealersController@index');
Route::get('settings', 'Api\SettingsController@index');
Route::get('styles', 'Api\StylesController@index');
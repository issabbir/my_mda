<?php

Route::group(['middleware' => ['auth'], 'name'=> 'vms.'], function () {

    Route::post('/brta-api-match-vehicle', 'Mea\Brta\BrtaApiController@matchVehicle')->name('brta-api-match-vehicle');
    Route::post('/brta-api-get-vehicle-data', 'Mea\Brta\BrtaApiController@getvehiclewithid')->name('brta-api-get-vehicle-data');
    Route::post('/nid-api-data', 'Mea\Nid\NidApiController@nid_data')->name('nid-api-data');


    Route::get('/workshop-service', 'Mea\Vms\WorkshopServiceController@index')->name('workshop-service-index');
    //
    Route::get('/datatable-workshop-service', 'Mea\Vms\WorkshopServiceController@datatableList')->name('datatable-workshop-service');
    Route::get('/workshop-service/{id}', 'Mea\Vms\WorkshopServiceController@edit')->name('workshop-service-edit');
    //
    Route::post('/workshop-service', 'Mea\Vms\WorkshopServiceController@store')->name('workshop-service-store');
    Route::put('/workshop-service/{id}', 'Mea\Vms\WorkshopServiceController@update')->name('workshop-service-update');

   //Route::group(['name' => 'vehicleInfo', 'as' => 'vehicleInfo'], function (){
    Route::get('/vehicle-info', 'Mea\Vms\VehicleInfoController@index')->name('vehicle-info-index');
    Route::get('/datatable-vehicle-list', 'Mea\Vms\VehicleInfoController@datatableList')->name('datatable-vehicle-list');
    Route::get('/vehicle-info/{id}', 'Mea\Vms\VehicleInfoController@edit')->name('vehicle-info-edit');
    Route::post('/vehicle-info', 'Mea\Vms\VehicleInfoController@store')->name('vehicle-info-store');
    Route::put('/vehicle-info/{id}', 'Mea\Vms\VehicleInfoController@update')->name('vehicle-info-update');
   //});

   // Route::group(['name' => 'driver_enlist2', 'as' => 'driver_enlist2'], function (){
    Route::get('/driver-enlist', 'Mea\Vms\DriverEnlistedController@index')->name('driver-enlist-index');
    Route::get('/driver-enlist-datatable', 'Mea\Vms\DriverEnlistedController@datatableList')->name('driver-enlist-datatable');
    Route::get('/driver-enlist/{id}', 'Mea\Vms\DriverEnlistedController@edit')->name('driver-enlist-edit');
    Route::post('/driver-enlist', 'Mea\Vms\DriverEnlistedController@store')->name('driver-enlist-store');
    Route::put('/driver-enlist/{id}', 'Mea\Vms\DriverEnlistedController@update')->name('driver-enlist-update');

    // });

    Route::get('/vehicle-assign', 'Mea\Vms\VehicleAssignController@index')->name('vehicle-assign-index');
    Route::get('/datatable-vehicle-assign', 'Mea\Vms\VehicleAssignController@datatableList')->name('datatable-vehicle-assign');
    Route::get('/vehicle-assign/{id}', 'Mea\Vms\VehicleAssignController@edit')->name('vehicle-assign-edit');
    Route::post('/vehicle-assign', 'Mea\Vms\VehicleAssignController@store')->name('vehicle-assign-store');
    Route::put('/vehicle-assign/{id}', 'Mea\Vms\VehicleAssignController@update')->name('vehicle-assign-update');

    Route::get('/fuel-consumption', 'Mea\Vms\FuelConsumptionController@index')->name('fuel-consumption-index');
    Route::get('/datatable-fuel-consumption', 'Mea\Vms\FuelConsumptionController@datatableList')->name('datatable-fuel-consumption');
    Route::get('/fuel-consumption/{id}', 'Mea\Vms\FuelConsumptionController@edit')->name('fuel-consumption-edit');
    Route::post('/fuel-consumption', 'Mea\Vms\FuelConsumptionController@store')->name('fuel-consumption-store');
    Route::put('/fuel-consumption/{id}', 'Mea\Vms\FuelConsumptionController@update')->name('fuel-consumption-update');

    Route::get('/maintenance', 'Mea\Vms\MaintenanceController@index')->name('maintenance-index');
    Route::get('/datatable-maintenance', 'Mea\Vms\MaintenanceController@datatableList')->name('datatable-maintenance');
    Route::get('/maintenance/{id}', 'Mea\Vms\MaintenanceController@edit')->name('maintenance-edit');
    Route::post('/maintenance', 'Mea\Vms\MaintenanceController@store')->name('maintenance-store');
    Route::put('/maintenance/{id}', 'Mea\Vms\MaintenanceController@update')->name('maintenance-update');

    Route::get('/workshop', 'Mea\Vms\WorkshopController@index')->name('workshop-index');
    Route::get('/datatable-workshop', 'Mea\Vms\WorkshopController@datatableList')->name('datatable-workshop');
    Route::get('/workshop/{id}', 'Mea\Vms\WorkshopController@edit')->name('workshop-edit');
    Route::post('/workshop', 'Mea\Vms\WorkshopController@store')->name('workshop-store');
    Route::put('/workshop/{id}', 'Mea\Vms\WorkshopController@update')->name('workshop-update');

    Route::get('/services', 'Mea\Vms\ServicesController@index')->name('services-index');
    Route::get('/datatable-services', 'Mea\Vms\ServicesController@datatableList')->name('datatable-services');
    Route::get('/services/{id}', 'Mea\Vms\ServicesController@edit')->name('services-edit');
    Route::post('/services', 'Mea\Vms\ServicesController@store')->name('services-store');
    Route::put('/services/{id}', 'Mea\Vms\ServicesController@update')->name('services-update');

    Route::get('/vehicle-rent', 'Mea\Vms\VehicleRentController@index')->name('vehicle-rent-index');
    Route::post('/vehicle-rent', 'Mea\Vms\VehicleRentController@store')->name('vehicle-rent-store');
    Route::get('/datatable-vehicle-rent', 'Mea\Vms\VehicleRentController@datatableList')->name('datatable-vehicle-rent');
    Route::get('/vehicle-rent/{id}', 'Mea\Vms\VehicleRentController@edit')->name('vehicle-rent-edit');
    Route::put('/vehicle-rent/{id}', 'Mea\Vms\VehicleRentController@update')->name('vehicle-rent-update');

    Route::get('/tracker', 'Mea\Vms\trackerController@index')->name('tracker-index');
    Route::get('/datatable-tracker', 'Mea\Vms\trackerController@datatableList')->name('datatable-tracker');
    Route::get('/tracker/{id}', 'Mea\Vms\trackerController@edit')->name('tracker-edit');
    Route::post('/tracker', 'Mea\Vms\trackerController@store')->name('tracker-store');
    Route::put('/tracker/{id}', 'Mea\Vms\trackerController@update')->name('tracker-update');

    Route::get('/workshop-type', 'Mea\Vms\WorkshopTypeController@index')->name('workshop-type-index');
    Route::get('/datatable-workshop-type', 'Mea\Vms\WorkshopTypeController@datatableList')->name('datatable-workshop-type');
    Route::post('/workshop-type', 'Mea\Vms\WorkshopTypeController@store')->name('workshop-type-store');
    Route::get('/workshop-type/{id}', 'Mea\Vms\WorkshopTypeController@edit')->name('workshop-type-edit');
    Route::put('/workshop-type/{id}', 'Mea\Vms\WorkshopTypeController@update')->name('workshop-type-update');

    //Download
    Route::get('/vehicles-attachments/download/{id}', 'Mea\Vms\DownloaderController@vehiclesAttachments')->name('vehicles-attachments');
    Route::get('/driver-photo/download/{id}', 'Mea\Vms\DownloaderController@driverPhoto')->name('driver-photo');
    Route::get('/fuel-voucher-attachment/download/{id}', 'Mea\Vms\DownloaderController@fuelVoucherAttachment')->name('fuel-voucher-attachment');
    Route::get('/supplier-attachments/download/{id}', 'Mea\Vms\DownloaderController@supplierAttachments')->name('supplier-attachments');

    Route::get('/supplier', 'Mea\Vms\SupplierController@index')->name('supplier-index');
    Route::post('/supplier', 'Mea\Vms\SupplierController@store')->name('supplier-store');
    Route::get('/datatable-supplier', 'Mea\Vms\SupplierController@datatableList')->name('datatable-supplier');
    Route::get('/supplier/{id}', 'Mea\Vms\SupplierController@edit')->name('supplier-edit');
    Route::put('/supplier/{id}', 'Mea\Vms\SupplierController@update')->name('supplier-update');

    //Schedule Info
    Route::get('/schedule', 'Mea\Vms\ScheduleController@index')->name('schedule-index');
    Route::get('/datatable-schedule', 'Mea\Vms\ScheduleController@datatableList')->name('datatable-schedule');
    Route::get('/schedule/{id}', 'Mea\Vms\ScheduleController@edit')->name('schedule-edit');
    Route::post('/schedule', 'Mea\Vms\ScheduleController@store')->name('schedule-store');
    Route::put('/schedule/{id}', 'Mea\Vms\ScheduleController@update')->name('schedule-update');


    Route::get('/fuel-limit', 'Mea\Vms\FuelLimitController@index')->name('fuel-limit-index');
    Route::get('/datatable-fuel-limit', 'Mea\Vms\FuelLimitController@datatableList')->name('datatable-fuel-limit');
    Route::get('/fuel-limit/{id}', 'Mea\Vms\FuelLimitController@edit')->name('fuel-limit-edit');
    Route::post('/fuel-limit', 'Mea\Vms\FuelLimitController@store')->name('fuel-limit-store');
    Route::put('/fuel-limit/{id}', 'Mea\Vms\FuelLimitController@update')->name('fuel-limit-update');


    Route::get('/fuel-limit-addition', 'Mea\Vms\FuelLimitAdditionController@index')->name('fuel-limit-addition-index');
    Route::get('/datatable-fuel-limit-addition', 'Mea\Vms\FuelLimitAdditionController@datatableList')->name('datatable-fuel-limit-addition');
    Route::get('/fuel-limit-addition/{id}', 'Mea\Vms\FuelLimitAdditionController@edit')->name('fuel-limit-addition-edit');
    Route::post('/fuel-limit-addition', 'Mea\Vms\FuelLimitAdditionController@store')->name('fuel-limit-addition-store');
    Route::put('/fuel-limit-addition/{id}', 'Mea\Vms\FuelLimitAdditionController@update')->name('fuel-limit-addition-update');

    Route::get('/fuel-bulk-entry', 'Mea\Vms\FuelBulkEntryController@index')->name('fuel-bulk-entry-index');
//    Route::get('/datatable-fuel-bulk-entry', 'Mea\Vms\FuelBulkEntryController@datatableList')->name('datatable-fuel-bulk-entry');
    Route::get('/fuel-bulk-entry-list', 'Mea\Vms\FuelBulkEntryController@fuelBulkEntryList')->name('fuel-bulk-entry-list');
//    Route::get('/fuel-bulk-entry/{id}', 'Mea\Vms\FuelBulkEntryController@edit')->name('fuel-bulk-entry-edit');
    Route::post('/fuel-bulk-entry', 'Mea\Vms\FuelBulkEntryController@store')->name('fuel-bulk-entry-store');
//    Route::put('/fuel-bulk-entry/{id}', 'Mea\Vms\FuelBulkEntryController@update')->name('fuel-bulk-entry-update');

    Route::get('/populate-vehicle-list', 'Mea\Vms\FuelBulkEntryController@populateVehicleList')->name('populate-vehicle-list');

});

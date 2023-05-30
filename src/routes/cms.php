<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth']], function () {

        Route::prefix('ajax')->group(function (){
            Route::get('/ajax-search-employee','Cms\AjaxController@searchEmployee')->name('cms.ajax.search-employee');
            Route::get('/get-employee-info','Cms\AjaxController@getEmployeeInfo')->name('cms.ajax.get-employee-info');
            Route::get('/show-emp-designation','Cms\AjaxController@showEmpDesignation')->name('cms.ajax.show-emp-designation');
            Route::get('/show-offday-by-year-month','Cms\AjaxController@showOffDayListByYearMonth')->name('cms.ajax.show-offday-by-year-month');
        });

        /****************************************************Settings***********************************/

        Route::prefix('fuel-setting')->group(function (){
            Route::get('/','Cms\SettingsController@fuelIndex')->name('cms.setting.fuel');
            Route::post('/','Cms\SettingsController@fuelStore')->name('cms.setting.fuel-store');
            Route::get('/edit/{id}', 'Cms\SettingsController@fuelEdit')->name('cms.setting.fuel-edit');
            Route::put('/edit/{id}', 'Cms\SettingsController@fuelUpdate')->name('cms.setting.fuel-update');
            Route::delete('/delete/{id}', 'Cms\SettingsController@fuelDestroy')->name('cms.setting.fuel-destroy');
            Route::post('/datatable/{id}','Cms\SettingsController@fuelDatatable')->name('cms.setting.fuel-datatable');
        });

        Route::prefix('placement-setting')->group(function (){
            Route::get('/','Cms\SettingsController@placementIndex')->name('cms.setting.placement');
            Route::post('/','Cms\SettingsController@placementStore')->name('cms.setting.placement-store');
            Route::get('/edit/{id}', 'Cms\SettingsController@placementEdit')->name('cms.setting.placement-edit');
            Route::put('/edit/{id}', 'Cms\SettingsController@placementUpdate')->name('cms.setting.placement-update');
            Route::delete('/delete/{id}', 'Cms\SettingsController@placementDestroy')->name('cms.setting.placement-destroy');
            Route::post('/datatable/{id}','Cms\SettingsController@placementDatatable')->name('cms.setting.placement-datatable');
        });

        Route::prefix('vessel-engine-setting')->group(function (){
            Route::get('/','Cms\SettingsController@vesselEngineTypeIndex')->name('cms.setting.vessel-engine');
            Route::post('/','Cms\SettingsController@vesselEngineTypeStore')->name('cms.setting.vessel-engine-store');
            Route::get('/edit/{id}', 'Cms\SettingsController@vesselEngineTypeEdit')->name('cms.setting.vessel-engine-edit');
            Route::put('/edit/{id}', 'Cms\SettingsController@vesselEngineTypeUpdate')->name('cms.setting.vessel-engine-update');
            Route::delete('/delete/{id}', 'Cms\SettingsController@vesselEngineTypeDestroy')->name('cms.setting.vessel-engine-destroy');
            Route::post('/datatable/{id}','Cms\SettingsController@vesselEngineTypeDatatable')->name('cms.setting.vessel-engine-datatable');

        });

        Route::prefix('shifting-setting')->group(function (){
            Route::get('/','Cms\SettingsController@shiftingIndex')->name('cms.setting.shifting');
            Route::post('/','Cms\SettingsController@shiftingStore')->name('cms.setting.shifting-store');
            Route::get('/edit/{id}', 'Cms\SettingsController@shiftingEdit')->name('cms.setting.shifting-edit');
            Route::put('/edit/{id}', 'Cms\SettingsController@shiftingUpdate')->name('cms.setting.shifting-update');
            Route::delete('/delete/{id}', 'Cms\SettingsController@shiftingDestroy')->name('cms.setting.shifting-destroy');
            Route::post('/datatable/{id}','Cms\SettingsController@shiftingDatatable')->name('cms.setting.shifting-datatable');

        });

        Route::prefix('duties')->group(function (){
            Route::get('/','Cms\ShiftingController@dutiesIndex')->name('cms.shifting.duties');
            Route::get('/create','Cms\ShiftingController@dutiesCreate')->name('cms.shifting.duties-create');
            Route::post('/','Cms\ShiftingController@dutiesStore')->name('cms.shifting.duties-store');
            Route::get('/edit/{id}', 'Cms\ShiftingController@dutiesEdit')->name('cms.shifting.duties-edit');
            Route::post('/edit/{id}', 'Cms\ShiftingController@dutiesUpdate')->name('cms.shifting.duties-update');
            Route::delete('/delete/{id}', 'Cms\ShiftingController@dutiesDestroy')->name('cms.shifting.duties-destroy');
            Route::post('/datatable/{id}','Cms\ShiftingController@dutiesDatatable')->name('cms.shifting.duties-datatable');
            Route::post('/search-duty-schedule','Cms\ShiftingController@searchDutySchedule')->name('cms.shifting.search-duty-schedule');

        });

        Route::prefix('shift')->group(function (){
            Route::get('/','Cms\ShiftingController@dutyShiftingIndex')->name('cms.shifting.shift');
            Route::post('/','Cms\ShiftingController@dutyShiftingStore')->name('cms.shifting.shift-store');
            Route::get('/edit/{id}', 'Cms\ShiftingController@dutyShiftingEdit')->name('cms.shifting.shift-edit');
            Route::put('/edit/{id}', 'Cms\ShiftingController@dutyShiftingUpdate')->name('cms.shifting.shift-update');
            Route::delete('/delete/{id}', 'Cms\ShiftingController@dutyShiftingDestroy')->name('cms.shifting.shift-destroy');
            Route::post('/datatable','Cms\ShiftingController@dutyShiftingDatatable')->name('cms.shifting.shift-datatable');
        });

        Route::prefix('offday')->group(function (){
            Route::get('/','Cms\ShiftingController@offDayIndex')->name('cms.offday');
            Route::post('/','Cms\ShiftingController@offDayStore')->name('cms.offday.store');
            Route::get('/edit/{id}', 'Cms\ShiftingController@offDayEdit')->name('cms.offday.edit');
            Route::put('/edit/{id}', 'Cms\ShiftingController@offDayUpdate')->name('cms.offday.update');
            Route::delete('/delete/{id}', 'Cms\ShiftingController@offDayDestroy')->name('cms.offday.destroy');
            Route::post('/datatable','Cms\ShiftingController@offDayDatatable')->name('cms.offday.datatable');
        });

    Route::prefix('vessel')->group(function (){
        Route::get('/','Cms\VesselController@vesselIndex')->name('cms.vessel');
        Route::post('/','Cms\VesselController@vesselStore')->name('cms.vessel.store');
        Route::get('/edit/{id}', 'Cms\VesselController@vesselEdit')->name('cms.vessel.edit');
        Route::put('/edit/{id}', 'Cms\VesselController@vesselUpdate')->name('cms.vessel.update');
        Route::delete('/delete/{id}', 'Cms\VesselController@vesselDestroy')->name('cms.vessel.destroy');
        Route::post('/datatable','Cms\VesselController@vesselDatatable')->name('cms.vessel.datatable');
        Route::get('/user-wise-vessel','Cms\VesselController@showUserWiseVessel')->name('cms.user-wise-vessel');
        Route::post('/user-wise-vessel/datatable','Cms\VesselController@userWiseVesselDatatable')->name('cms.vessel.user-wise-vessel-datatable');
    });

    Route::prefix('vessel-engine-mapping')->group(function (){
        Route::get('/','Cms\VesselController@vesselEngineMappingIndex')->name('cms.vessel-engine-mapping');
        Route::post('/','Cms\VesselController@vesselEngineMappingStore')->name('cms.vessel-engine-mapping.store');
        Route::get('/edit/{id}', 'Cms\VesselController@vesselEngineMappingEdit')->name('cms.vessel-engine-mapping.edit');
        Route::put('/edit/{id}', 'Cms\VesselController@vesselEngineMappingUpdate')->name('cms.vessel-engine-mapping.update');
        Route::delete('/delete/{id}', 'Cms\VesselController@vesselEngineMappingDestroy')->name('cms.vessel-engine-mapping.destroy');
        Route::post('/datatable','Cms\VesselController@vesselEngineMappingDatatable')->name('cms.vessel-engine-mapping.datatable');
    });

    Route::prefix('fuel-consumption')->group(function (){
        Route::get('/','Cms\VesselController@fuelConsumptionIndex')->name('cms.fuel-consumption');
        Route::post('/','Cms\VesselController@fuelConsumptionStore')->name('cms.fuel-consumption.store');
        Route::get('/edit/{id}', 'Cms\VesselController@fuelConsumptionEdit')->name('cms.fuel-consumption.edit');
        Route::put('/edit/{id}', 'Cms\VesselController@fuelConsumptionUpdate')->name('cms.fuel-consumption.update');
        Route::delete('/delete/{id}', 'Cms\VesselController@fuelConsumptionDestroy')->name('cms.fuel-consumption.destroy');
        Route::post('/datatable','Cms\VesselController@fuelConsumptionDatatable')->name('cms.fuel-consumption.datatable');
        Route::get('/show-approval-stage','Cms\VesselController@showFuelConsumptionApproval')->name('cms.fuel-consumption.show-approval-stage');
        Route::post('/send-to-approval','Cms\VesselController@storeFuelConsumptionApproval')->name('cms.fuel-consumption.send-to-approval.store');
        Route::post('/store-send-to-approval','Cms\VesselController@fuelConsumptionStoreSendToApproval')->name('cms.fuel-consumption.store-send-to-approval');
    });

    Route::group(['prefix' => 'approval', 'as' => 'approval.'], function () {
        Route::get('/list','Cms\ApprovalController@index')->name('list');
        Route::get('/show','Cms\ApprovalController@show')->name('show');
        Route::post('/store','Cms\ApprovalController@store')->name('store');
        Route::post('/datatable','Cms\ApprovalController@datatable')->name('datatable');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', 'Cms\ReportGeneratorController@index')->name('cms.reports');
        Route::get('/report-generator-params/{id}', 'Cms\ReportGeneratorController@reportParams')->name('cms.report-params');
        Route::get('/get-vessel-data', 'Cms\ReportGeneratorController@getVesselData')->name('get-vessel-data');
        Route::get('/fuel-consumption-by-vessel/{id}', 'Cms\ReportGeneratorController@getFuelConsumptionByVessel')->name('fuel-consumption-by-vessel');
//        Route::get('/get-cargo-data', 'Mda\ReportGeneratorController@cargoData')->name('get-cargo-data');
//        Route::get('/get-pilotage-types', 'Mda\ReportGeneratorController@pilotageTypesData')->name('get-pilotage-types');
//        Route::get('/get-cpa-vessel-data', 'Mda\ReportGeneratorController@cpaVesselData')->name('get-cpa-vessel-data');
//        Route::get('/get-cpa-pilots-data', 'Mda\ReportGeneratorController@cpaPilotData')->name('get-cpa-pilots-data');
//        Route::get('/get-local-vessel-data', 'Mda\ReportGeneratorController@localVesselData')->name('get-local-vessel-data');
//        Route::get('/get-swing-mooring-data', 'Mda\ReportGeneratorController@swingMooringData')->name('get-swing-mooring-data');
//        Route::get('/foreign-vessel-detail/{id}','Mda\ReportGeneratorController@foreignVesselsDetails')->name("get-foreign-vessel-detail");

    });
});



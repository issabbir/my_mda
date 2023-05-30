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

Route::get('/', 'UserController@index')->name('login');
Route::post('/authorization/login', 'Auth\LoginController@authorization')->name('authorization.login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/user/change-password', function () {
        return view('resetPassword');
    })->name('change-password');

    Route::post('/user/change-password', 'Auth\ResetPasswordController@resetPassword')->name('user.reset-password');
    Route::post('/report/render/{title}', 'Report\OraclePublisherController@render')->name('report');
    Route::get('/report/render/{title?}', 'Report\OraclePublisherController@render')->name('report-get');
    Route::post('/authorization/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/get-workflow-id', 'Mda\WorkflowController@get_workflow')->name('get-workflow-id');
    Route::get('/get-approval-list', 'Mda\WorkflowController@load_workflow')->name('get-approval-list');
    Route::post('/get-approval-post', 'Mda\WorkflowController@assign_workflow')->name('get-approval-post');

    Route::get('/approval', 'Mda\WorkflowController@status')->name('approval');
    Route::post('/approval-post', 'Mda\WorkflowController@store')->name('approval-post');

    Route::get('/seen-notification', 'Mda\WorkflowController@updateNotification')->name('seen-notification');


    Route::prefix('/collection-slip-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@collectionSlipTypeCreate')->name('collection-slip-type');
        Route::post('/', 'Mda\SettingsController@collectionSlipTypeStore')->name('collection-slip-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@collectionSlipTypeEdit')->name('collection-slip-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@collectionSlipTypeUpdate')->name('collection-slip-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@collectionSlipTypeDatatable')->name('collection-slip-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@collectionSlipTypeDestroy')->name('collection-slip-type-destroy');
    });
    Route::prefix('/pilotage-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@pilotageTypeCreate')->name('pilotage-type');
        Route::post('/', 'Mda\SettingsController@pilotageTypeStore')->name('pilotage-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@pilotageTypeEdit')->name('pilotage-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@pilotageTypeUpdate')->name('pilotage-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@pilotageTypeDatatable')->name('pilotage-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@pilotageTypeDestroy')->name('pilotage-type-destroy');
    });

    Route::prefix('/tug-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@tugTypeCreate')->name('tug-type');
        Route::post('/', 'Mda\SettingsController@tugTypeStore')->name('tug-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@tugTypeEdit')->name('tug-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@tugTypeUpdate')->name('tug-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@tugTypeDatatable')->name('tug-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@tugTypeDestroy')->name('tug-type-destroy');
    });

    Route::prefix('/cargo')->group(function () {
        Route::get('/', 'Mda\SettingsController@cargoCreate')->name('cargo');
        Route::post('/', 'Mda\SettingsController@cargoStore')->name('cargo-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@cargoEdit')->name('cargo-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@cargoUpdate')->name('cargo-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@cargoDatatable')->name('cargo-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@cargoDestroy')->name('cargo-destroy');
    });

    Route::prefix('company-setup')->group(function () {
        Route::get('/', 'Mwe\SettingsController@companyIndex')->name('company-setup');
        Route::post('/', 'Mwe\SettingsController@companyStore')->name('mwe.setting.company-setup-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@companyEdit')->name('mwe.setting.company-setup-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@companyUpdate')->name('mwe.setting.company-setup-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@companyDestroy')->name('mwe.setting.company-setup-destroy');
        Route::post('/datatable', 'Mwe\SettingsController@companyDatatable')->name('mwe.setting.company-setup-datatable');

    });

    Route::prefix('office-setup')->group(function () {
        Route::get('/', 'Mwe\SettingsController@officeIndex')->name('mwe.setting.office-setup');
        Route::post('/', 'Mwe\SettingsController@officeStore')->name('mwe.setting.office-setup-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@officeEdit')->name('mwe.setting.office-setup-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@officeUpdate')->name('mwe.setting.office-setup-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@officeDestroy')->name('mwe.setting.office-setup-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@officeDatatable')->name('mwe.setting.office-setup-datatable');
        Route::get('/get-all-employee', 'Mwe\SettingsController@getAllEmployee')->name('get-all-employee');
    });

    Route::prefix('company-vessel-setup')->group(function () {
        Route::get('/', 'Mwe\SettingsController@companyVesselIndex')->name('company-vessel-setup');
        Route::post('/', 'Mwe\SettingsController@companyVesselStore')->name('mwe.setting.company-vessel-setup-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@companyVesselEdit')->name('mwe.setting.company-vessel-setup-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@companyVesselUpdate')->name('mwe.setting.company-vessel-setup-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@companyVesselDestroy')->name('mwe.setting.company-vessel-setup-destroy');
        Route::post('/datatable', 'Mwe\SettingsController@companyVesselDatatable')->name('mwe.setting.company-vessel-setup-datatable');
        Route::get('/search-vessel', 'Mwe\SettingsController@searchVessel')->name('mwe.setting.search-vessel');
        Route::get('/get-vessel-info', 'Mwe\SettingsController@getVesselInfo')->name('mwe.setting.vessel-info');
    });

    Route::prefix('/vessel-condition')->group(function () {
        Route::get('/', 'Mda\SettingsController@vesselConditionCreate')->name('vessel-condition');
        Route::post('/', 'Mda\SettingsController@vesselConditionStore')->name('vessel-condition-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@vesselConditionEdit')->name('vessel-condition-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@vesselConditionUpdate')->name('vessel-condition-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@vesselConditionDatatable')->name('vessel-condition-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@vesselConditionDestroy')->name('vessel-condition-destroy');
    });

    Route::prefix('/cpa-vessel-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@cpaVesselTypeCreate')->name('cpa-vessel-type');
        Route::post('/', 'Mda\SettingsController@cpaVesselTypeStore')->name('cpa-vessel-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@cpaVesselTypeEdit')->name('cpa-vessel-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@cpaVesselTypeUpdate')->name('cpa-vessel-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@cpaVesselTypeDatatable')->name('cpa-vessel-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@cpaVesselTypeDestroy')->name('cpa-vessel-type-destroy');
    });

    Route::prefix('/ps-schedule-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@psScheduleTypeCreate')->name('ps-schedule-type');
        Route::post('/', 'Mda\SettingsController@psScheduleTypeStore')->name('ps-schedule-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@psScheduleTypeEdit')->name('ps-schedule-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@psScheduleTypeUpdate')->name('ps-schedule-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@psScheduleTypeDatatable')->name('ps-schedule-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@psScheduleTypeDestroy')->name('ps-schedule-type-destroy');
    });

    Route::prefix('/vessel-working-type')->group(function () {
        Route::get('/', 'Mda\SettingsController@vesselWorkingTypeCreate')->name('vessel-working-type');
        Route::post('/', 'Mda\SettingsController@vesselWorkingTypeStore')->name('vessel-working-type-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@vesselWorkingTypeEdit')->name('vessel-working-type-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@vesselWorkingTypeUpdate')->name('vessel-working-type-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@vesselWorkingTypeDatatable')->name('vessel-working-type-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@vesselWorkingTypeDestroy')->name('vessel-working-type-destroy');
    });


    Route::prefix('/local-vessel')->group(function () {
        Route::get('/', 'Mda\LocalVesselController@localVesselCreate')->name('local-vessel');
        Route::post('/', 'Mda\LocalVesselController@localVesselStore')->name('local-vessel-store');
        Route::get('/edit/{id}', 'Mda\LocalVesselController@localVesselEdit')->name('local-vessel-edit');
        Route::put('/edit/{id}', 'Mda\LocalVesselController@localVesselUpdate')->name('local-vessel-update');
        Route::post('/datatable/{id}', 'Mda\LocalVesselController@localVesselDatatable')->name('local-vessel-datatable');
        Route::delete('/delete/{id}', 'Mda\LocalVesselController@localVesselDestroy')->name('local-vessel-destroy');
        Route::get('/get-agent-info', 'Mda\LocalVesselController@getAgentInfo')->name('get-agent-info');

        Route::get('/download/{id}', 'Mda\LocalVesselController@downloadAfile')->name("local-vessel-download-media");
    });

    Route::prefix('/cpa-vessel')->group(function () {
        Route::get('/', 'Mda\CpaVesselsController@cpaVesselCreate')->name('cpa-vessel');
        Route::post('/', 'Mda\CpaVesselsController@cpaVesselStore')->name('cpa-vessel-store');
        Route::get('/edit/{id}', 'Mda\CpaVesselsController@cpaVesselEdit')->name('cpa-vessel-edit');
        Route::put('/edit/{id}', 'Mda\CpaVesselsController@cpaVesselUpdate')->name('cpa-vessel-update');
        Route::delete('/delete/{id}', 'Mda\CpaVesselsController@cpaVesselDestroy')->name('cpa-vessel-destroy');
        Route::post('/datatable/{id}', 'Mda\CpaVesselsController@cpaVesselDatatable')->name('cpa-vessel-datatable');
    });


    Route::prefix('/tug-registration')->group(function () {
        Route::get('/', 'Mda\TugsRegistrationController@tugCreate')->name('tug-registration');
        Route::post('/', 'Mda\TugsRegistrationController@tugStore')->name('tug-registration-store');
        Route::get('/edit/{id}', 'Mda\TugsRegistrationController@tugEdit')->name('tug-registration-edit');
        Route::put('/edit/{id}', 'Mda\TugsRegistrationController@tugUpdate')->name('tug-registration-update');
        Route::delete('/delete/{id}', 'Mda\TugsRegistrationController@tugDestroy')->name('tug-registration-destroy');
        Route::post('/datatable/{id}', 'Mda\TugsRegistrationController@tugDatatable')->name('tug-registration-datatable');
    });

    Route::prefix('/p-working-location')->group(function () {
        Route::get('/', 'Mda\SettingsController@pilotageWorkLocationCreate')->name('p-working-location');
        Route::post('/', 'Mda\SettingsController@pilotageWorkLocationStore')->name('p-working-location-store');
        Route::get('/edit/{id}', 'Mda\SettingsController@pilotageWorkLocationEdit')->name('p-working-location-edit');
        Route::put('/edit/{id}', 'Mda\SettingsController@pilotageWorkLocationUpdate')->name('p-working-location-update');
        Route::post('/datatable/{id}', 'Mda\SettingsController@pilotageWorkLocationDatatable')->name('p-working-location-datatable');
        Route::delete('/delete/{id}', 'Mda\SettingsController@pilotageWorkLocationDestroy')->name('p-working-location-destroy');
    });

    Route::prefix('/ps-certificate-entry')->group(function () {
        Route::get('/', 'Mda\PilotageController@pilotageCreate')->name('ps-certificate-entry');
        Route::post('/', 'Mda\PilotageController@pilotageStore')->name('ps-certificate-entry-store');
        Route::get('/edit/{id}', 'Mda\PilotageController@pilotageEdit')->name('ps-certificate-entry-edit');
        Route::put('/edit/{id}', 'Mda\PilotageController@pilotageUpdate')->name('ps-certificate-entry-update');
        Route::delete('/delete/{id}', 'Mda\PilotageController@pilotageDestroy')->name('ps-certificate-entry-destroy');
        Route::post('/datatable/{id}', 'Mda\PilotageController@pilotageDatatable')->name('ps-certificate-entry-datatable');

        Route::get('/mother-vessels/{id}', 'Mda\PilotageController@foreignVesselsList')->name("ps-certificate-entry-vessels");
        Route::get('/foreign-vessels-detail/{id}', 'Mda\PilotageController@foreignVesselsDetails')->name("ps-certificate-entry-vessels-detail");
        Route::post('/tugs', 'Mda\PilotageController@tugsList')->name("ps-certificate-entry-tugs");

        Route::get('/download/{id}', 'Mda\PilotageController@downloadAfile')->name("ps-certificate-download-media");
        Route::get('/get-master-name','Mda\PilotageController@getMasterName')->name('get-master-name');
    });
//dom
    Route::prefix('/ps-verify-certificate')->group(function () {
        Route::get('/', 'Mda\PilotageController@certificateList')->name("ps-verify-certificate");
        Route::get('/view/{id}', 'Mda\PilotageController@certificateDetail')->name("ps-verify-certificate-view");
//        Route::post('/approve', 'Mda\PilotageController@approve')->name("ps-verify-certificate-approve");
        Route::post('/approve', 'Mda\PilotageController@certificateApprove')->name("ps-verify-certificate-approve");
        Route::post('/datatable/{id}', 'Mda\PilotageController@certificateDatatable')->name('ps-verify-certificate-datatable');
    });

    Route::prefix('/sm-registration')->group(function () {
        Route::get('/', 'Mda\SmController@smCreate')->name('sm-registration');
        Route::post('/', 'Mda\SmController@smStore')->name('sm-registration-store');
        Route::get('/edit/{id}', 'Mda\SmController@smEdit')->name('sm-registration-edit');
        Route::put('/edit/{id}', 'Mda\SmController@smUpdate')->name('sm-registration-update');
        Route::delete('/delete/{id}', 'Mda\SmController@smDestroy')->name('sm-registration-destroy');
        Route::post('/datatable/{id}', 'Mda\SmController@smDatatable')->name('sm-registration-datatable');
    });

    Route::prefix('/sm-license-duty-entry')->group(function () {
        Route::get('/', 'Mda\SmController@smLdeCreate')->name('sm-license-duty-entry');
        Route::post('/', 'Mda\SmController@smLdeStore')->name('sm-license-duty-entry-store');
        Route::get('/edit/{id}', 'Mda\SmController@smLdeEdit')->name('sm-license-duty-entry-edit');
        Route::put('/edit/{id}', 'Mda\SmController@smLdeUpdate')->name('sm-license-duty-entry-update');
        Route::delete('/delete/{id}', 'Mda\SmController@smLdeDestroy')->name('sm-license-duty-entry-destroy');
        Route::post('/datatable/{id}', 'Mda\SmController@smLdeDatatable')->name('sm-license-duty-entry-datatable');
        Route::get('/sm-pilot-list', 'Mda\SmController@pilotList')->name('sm-pilot-list');
        Route::get('/sm-pilot-dtl', 'Mda\SmController@pilotDtl')->name('sm-pilot-dtl');
        Route::get('/get-last-agent', 'Mda\SmController@getLastAgent')->name('get-last-agent');
    });

    Route::prefix('/sm-inspector-approval')->group(function () {
        Route::get('/', 'Mda\SmController@smInsApproveCreate')->name('sm-inspector-approval');
        Route::post('/', 'Mda\SmController@smInsApproveStore')->name('sm-inspector-approval-store');
        Route::get('/edit/{id}', 'Mda\SmController@smInsApproveEdit')->name('sm-inspector-approval-edit');
        Route::put('/edit/{id}', 'Mda\SmController@smInsApproveUpdate')->name('sm-inspector-approval-update');
        Route::delete('/delete/{id}', 'Mda\SmController@smInsApproveDestroy')->name('sm-inspector-approval-destroy');
        Route::post('/datatable/{id}', 'Mda\SmController@smInsApproveDatatable')->name('sm-inspector-approval-datatable');

        Route::post('/approve', 'Mda\SmController@smInsApproval')->name('sm-inspector-approval-approve');
    });
    Route::prefix('/berthing-schedule')->group(function () {
        Route::get('/', 'Mda\BsController@bsCreate')->name('berthing-schedule');
        Route::post('/', 'Mda\BsController@bsStore')->name('berthing-schedule-store');
        Route::get('/edit/{id}', 'Mda\BsController@bsEdit')->name('berthing-schedule-edit');
        Route::put('/edit/{id}', 'Mda\BsController@bsUpdate')->name('berthing-schedule-update');
        Route::delete('/delete/{id}', 'Mda\BsController@bsDestroy')->name('berthing-schedule-destroy');
        Route::post('/datatable/{id}', 'Mda\BsController@berthDatatable')->name('berthing-schedule-datatable');
        Route::get('/get-foreign-vessel-data/{id}', 'Mda\BsController@foreignVesselData')->name('get-foreign-vessel-data');

        Route::get('/verify-bs-create', 'Mda\BsController@verifyBsCreate')->name('verify-bs-create');
        Route::post('/verify-bs-create', 'Mda\BsController@verifyBsStore')->name('verify-bs-create');
        Route::get('/verify-bs-edit/{id}', 'Mda\BsController@verifyBsEdit')->name('verify-bs-edit');
        Route::put('/verify-bs-edit/{id}', 'Mda\BsController@verifyBsUpdate')->name('verify-bs-update');
        Route::post('/verify-bs-datatable/{id}', 'Mda\BsController@verifyBsDatatable')->name('verify-bs-datatable');
    });

    Route::prefix('/cc-slip-generation')->group(function () {
        Route::get('/', 'Mda\CashCollectionController@slipGenerationCreate')->name('cc-slip-generation');
        Route::get('/port', 'Mda\CashCollectionController@slipGenerationCreatePort')->name('cc-slip-generation-port');
        Route::get('/river', 'Mda\CashCollectionController@slipGenerationCreateRiver')->name('cc-slip-generation-river');
        Route::get('/barge', 'Mda\CashCollectionController@slipGenerationCreateBarge')->name('cc-slip-generation-barge');
        Route::get('/license', 'Mda\CashCollectionController@slipGenerationCreateLicense')->name('cc-slip-generation-license');
        Route::post('/', 'Mda\CashCollectionController@slipGenerationStore')->name('cc-slip-generation-store');
        Route::get('/edit/{id}', 'Mda\CashCollectionController@slipGenerationEdit')->name('cc-slip-generation-edit');
//        Route::put('/edit/{id}', 'Mda\CashCollectionController@slipGenerationUpdate')->name('cc-slip-generation-update');
        Route::post('/edit/{id}', 'Mda\CashCollectionController@slipGenerationUpdate')->name('cc-slip-generation-update');
        Route::post('/datatable/{id}', 'Mda\CashCollectionController@slipGenerationDatatable')->name('cc-slip-generation-datatable');
        Route::delete('/delete/{id}', 'Mda\CashCollectionController@slipGenerationDestroy')->name('cc-slip-generation-destroy');
        Route::get('/vessel-data', 'Mda\CashCollectionController@getVesselData')->name('vessel-data');
    });

    Route::prefix('/jetty-service')->group(function (){
        Route::get('/', 'Mda\MService\JettyServiceController@jettyServiceCreate')->name('jetty-service');
        Route::get('/get-vessel-info/{id}', 'Mda\MService\JettyServiceController@getVesselInfo')->name('get-vessel-info');
        Route::post('/', 'Mda\MService\JettyServiceController@jettyServiceStore')->name('jetty-service-store');
        Route::get('/edit/{id}', 'Mda\MService\JettyServiceController@JettyServiceEdit')->name('jetty-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\JettyServiceController@JettyServiceUpdate')->name('jetty-service-update');
        Route::delete('/delete/{id}', 'Mda\MService\JettyServiceController@JettyServiceDestroy')->name('jetty-service-destroy');
        Route::post('/datatable/{id}', 'Mda\MService\JettyServiceController@datatable')->name('jetty-service-datatable');

    });

    Route::prefix('/mooring-charge')->group(function () {
        Route::get('/', 'Mda\MooringChargeController@create')->name('mooring-charge');
        Route::post('/', 'Mda\MooringChargeController@store')->name('mooring-charge-store');
        Route::get('/edit/{id}', 'Mda\MooringChargeController@edit')->name('mooring-charge-edit');
        Route::put('/edit/{id}', 'Mda\MooringChargeController@update')->name('mooring-charge-update');
        Route::post('/datatable/{id}', 'Mda\MooringChargeController@datatable')->name('mooring-charge-datatable');
        Route::delete('/delete/{id}', 'Mda\MooringChargeController@destroy')->name('mooring-charge-destroy');
    });

    Route::prefix('/navy-service')->group(function () {
        Route::get('/', 'Mda\MService\NavyServiceController@index')->name('navy-service');
        Route::post('/', 'Mda\MService\NavyServiceController@store')->name('navy-service-store');
        Route::get('/edit/{id}', 'Mda\MService\NavyServiceController@edit')->name('navy-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\NavyServiceController@update')->name('navy-service-update');
        Route::post('/datatable', 'Mda\MService\NavyServiceController@datatable')->name('navy-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\NavyServiceController@destroy')->name('navy-service-destroy');
        Route::get('/get-cpa-vessel', 'Mda\MService\NavyServiceController@getCPAVessel')->name('get-cpa-vessel');
        Route::get('/get-jetty-list', 'Mda\MService\NavyServiceController@getJettyList')->name('get-jetty-list');
        Route::get('/download/{id}', 'Mda\MService\NavyServiceController@downloader')->name('file-download');
        Route::get('/doc-remove', 'Mda\MService\NavyServiceController@removeDoc')->name('docRemove');
        Route::get('/get-navy-vessel', 'Mda\MService\NavyServiceController@getVesselNavy')->name('get-navy-vessel');
    });

    Route::prefix('/tug-service')->group(function () {
        Route::get('/', 'Mda\MService\TugServiceController@index')->name('tug-service');
        Route::post('/', 'Mda\MService\TugServiceController@store')->name('tug-service-store');
        Route::get('/edit/{id}', 'Mda\MService\TugServiceController@edit')->name('tug-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\TugServiceController@update')->name('tug-service-update');
        Route::post('/datatable', 'Mda\MService\TugServiceController@datatable')->name('tug-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\TugServiceController@destroy')->name('tug-service-destroy');
        Route::get('/get-foreign-vessel', 'Mda\MService\TugServiceController@getForeignVessel')->name('get-foreign-vessel');
        Route::get('/get-tug-vessel', 'Mda\MService\TugServiceController@getTugVessel')->name('get-tug-vessel');
        Route::get('/get-tug-list', 'Mda\MService\TugServiceController@getTugList')->name('get-tug-list');
    });

    Route::prefix('/fire-service')->group(function () {
        Route::get('/', 'Mda\MService\FireServiceController@index')->name('fire-service');
        Route::post('/', 'Mda\MService\FireServiceController@store')->name('fire-service-store');
        Route::get('/edit/{id}', 'Mda\MService\FireServiceController@edit')->name('fire-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\FireServiceController@update')->name('fire-service-update');
        Route::post('/datatable', 'Mda\MService\FireServiceController@datatable')->name('fire-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\FireServiceController@destroy')->name('fire-service-destroy');
        Route::get('/get-shipping-agent', 'Mda\MService\FireServiceController@getShippingAgent')->name('get-shipping-agent');
        Route::get('/dtl-data-remove', 'Mda\MService\FireServiceController@removeData')->name('fire-service.dtl-data-remove');
    });

    Route::prefix('/forklift-service')->group(function () {
        Route::get('/', 'Mda\MService\ForkliftServiceController@index')->name('forklift-service');
        Route::post('/', 'Mda\MService\ForkliftServiceController@store')->name('forklift-service-store');
        Route::get('/edit/{id}', 'Mda\MService\ForkliftServiceController@edit')->name('forklift-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\ForkliftServiceController@update')->name('forklift-service-update');
        Route::post('/datatable', 'Mda\MService\ForkliftServiceController@datatable')->name('forklift-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\ForkliftServiceController@destroy')->name('forklift-service-destroy');
        Route::get('/get-vessel-info', 'Mda\MService\ForkliftServiceController@getVesselInfo')->name('get-vessel-info');
        Route::get('/get-foreign-vessel', 'Mda\MService\ForkliftServiceController@getForeignVessel')->name('get-foreign-vessel');
        Route::get('/dtl-data-remove', 'Mda\MService\ForkliftServiceController@removeData')->name('dtl-data-remove');
    });

    Route::prefix('/water-service')->group(function () {
        Route::get('/', 'Mda\MService\WaterServiceController@index')->name('water-service');
        Route::post('/', 'Mda\MService\WaterServiceController@store')->name('water-service-store');
        Route::get('/edit/{id}', 'Mda\MService\WaterServiceController@edit')->name('water-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\WaterServiceController@update')->name('water-service-update');
        Route::post('/datatable', 'Mda\MService\WaterServiceController@datatable')->name('water-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\WaterServiceController@destroy')->name('water-service-destroy');
    });

    Route::prefix('/fixed-mooring-service')->group(function () {
        Route::get('/', 'Mda\MService\FMServiceController@index')->name('fixed-mooring-service');
        Route::post('/', 'Mda\MService\FMServiceController@store')->name('fixed-mooring-service-store');
        Route::get('/edit/{id}', 'Mda\MService\FMServiceController@edit')->name('fixed-mooring-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\FMServiceController@update')->name('fixed-mooring-service-update');
        Route::post('/datatable', 'Mda\MService\FMServiceController@datatable')->name('fixed-mooring-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\FMServiceController@destroy')->name('fixed-mooring-service-destroy');
        Route::get('/get-local-vessel', 'Mda\MService\FMServiceController@getLocalVessel')->name('get-local-vessel');
        Route::get('/get-cpa-vessel-info', 'Mda\MService\FMServiceController@getCPAVesselInfo')->name('get-cpa-vessel-info');
    });

    Route::prefix('/tug-cancel-service')->group(function () {
        Route::get('/', 'Mda\MService\TugCancelServiceController@index')->name('tug-cancel-service');
        Route::post('/', 'Mda\MService\TugCancelServiceController@store')->name('tug-cancel-service-store');
        Route::get('/edit/{id}', 'Mda\MService\TugCancelServiceController@edit')->name('tug-cancel-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\TugCancelServiceController@update')->name('tug-cancel-service-update');
        Route::post('/datatable', 'Mda\MService\TugCancelServiceController@datatable')->name('tug-cancel-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\TugCancelServiceController@destroy')->name('tug-cancel-service-destroy');
        Route::get('/get-pilot-list', 'Mda\MService\TugCancelServiceController@getPilotList')->name('get-pilot-list');
    });

    Route::prefix('/port-scrap-service')->group(function () {
        Route::get('/', 'Mda\MService\PortScrapServiceController@index')->name('port-scrap-service');
        Route::post('/', 'Mda\MService\PortScrapServiceController@store')->name('port-scrap-service-store');
        Route::get('/edit/{id}', 'Mda\MService\PortScrapServiceController@edit')->name('port-scrap-service-edit');
        Route::put('/edit/{id}', 'Mda\MService\PortScrapServiceController@update')->name('port-scrap-service-update');
        Route::post('/datatable', 'Mda\MService\PortScrapServiceController@datatable')->name('port-scrap-service-datatable');
        Route::delete('/delete/{id}', 'Mda\MService\PortScrapServiceController@destroy')->name('port-scrap-service-destroy');

//        Route::get('/get-local-vessel', 'Mda\MService\PortScrapServiceController@getLocalVessel')->name('get-local-vessel');
        Route::get('/get-vessel-info', 'Mda\MService\PortScrapServiceController@getVesselInfo')->name('get-vessel-info-port-scrap');
    });

});


Route::any('/report', 'Report\OraclePublisherController@render')->name('reportGet');

Route::prefix('reports')->group(function () {
    Route::get('/', 'Mda\ReportGeneratorController@index')->name('reports');
    Route::get('/report-generator-params/{id}', 'Mda\ReportGeneratorController@reportParams')->name('report-params');
    Route::get('/get-foreign-vessel-data', 'Mda\ReportGeneratorController@foreignVesselData')->name('get-foreign-vessel-data');
    Route::get('/get-foreign-agent-data', 'Mda\ReportGeneratorController@foreignVesselAgent')->name('get-foreign-agent-data');
    Route::get('/get-jetty-data', 'Mda\ReportGeneratorController@jettyData')->name('get-jetty-data');
    Route::get('/get-cargo-data', 'Mda\ReportGeneratorController@cargoData')->name('get-cargo-data');
    Route::get('/get-pilotage-types', 'Mda\ReportGeneratorController@pilotageTypesData')->name('get-pilotage-types');
    Route::get('/get-cpa-vessel-data', 'Mda\ReportGeneratorController@cpaVesselData')->name('get-cpa-vessel-data');
    Route::get('/get-cpa-pilots-data', 'Mda\ReportGeneratorController@cpaPilotData')->name('get-cpa-pilots-data');
    Route::get('/get-local-vessel-data', 'Mda\ReportGeneratorController@localVesselData')->name('get-local-vessel-data');
    Route::get('/get-swing-mooring-data', 'Mda\ReportGeneratorController@swingMooringData')->name('get-swing-mooring-data');
    Route::get('/foreign-vessel-detail/{id}', 'Mda\ReportGeneratorController@foreignVesselsDetails')->name("get-foreign-vessel-detail");
});

Route::prefix('invoice-pilotages')->group(function () {
    Route::get('/', 'Mda\InvoiceController@pilotages')->name('invoice-pilotages');
    Route::get('/pilotage/{id}', 'Mda\InvoiceController@pilotageDetail')->name('invoice-pilotages-view');
    Route::get('/invoice-download/{id}', 'Mda\InvoiceController@downloadInvoice')->name('invoice-pilotages-download');
    Route::post('/datatable', 'Mda\InvoiceController@pilotagesDatatable')->name('invoice-pilotage-datatable');
});

Route::prefix('invoice-cash-collection')->group(function () {
    Route::get('/', 'Mda\InvoiceController@slips')->name('invoice-cash-collection');
    Route::get('/slips/{id}', 'Mda\InvoiceController@slipsDetail')->name('invoice-cash-collection-view');
    Route::post('/datatable', 'Mda\InvoiceController@slipsDatatable')->name('invoice-cash-collection-datatable');
});


/*******************************Start Marine workshop engineer**************************/

Route::prefix('mwe')->group(function () {

    Route::prefix('ajax')->group(function () {
        Route::get('/ajax-search-employee', 'Mwe\AjaxController@searchEmployee')->name('mwe.ajax.search-employee');
        Route::get('/ajax-search-vessel', 'Mwe\AjaxController@searchVessel')->name('mwe.ajax.search-vessel');
        Route::get('/ajax-search-vessel-master', 'Mwe\AjaxController@searchVesselMaster')->name('mwe.ajax.search-vessel-master');
        Route::get('/ajax-search-doc-master', 'Mwe\AjaxController@searchDocMaster')->name('mwe.ajax.search-doc-master');
        Route::get('/ajax-search-maintenance-saen', 'Mwe\AjaxController@searchMaintenanceSAEN')->name('mwe.ajax.search-maintenance-saen');
        Route::get('/ajax-search-maintenance-ssaen', 'Mwe\AjaxController@searchMaintenanceSSAEN')->name('mwe.ajax.search-maintenance-ssaen');
        Route::get('/ajax-search-product', 'Mwe\AjaxController@searchProduct')->name('mwe.ajax.search-product');
        Route::get('/ajax-show-vessel-master', 'Mwe\AjaxController@getVesselMaster')->name('mwe.ajax-show-vessel-master');
        Route::get('/ajax-get-maintenance-req-number', 'Mwe\AjaxController@getMaintennanceRequestNumber')->name('mwe.ajax-get-maintenance-req-number');
        Route::get('/ajax-search-maintenance-request', 'Mwe\AjaxController@searchMaintenanceRequest')->name('mwe.ajax.ajax-search-maintenance-request');
    });

    /****************************************************Settings***********************************/

    Route::prefix('slipway-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@slipwayIndex')->name('mwe.setting.slipway');
        Route::post('/', 'Mwe\SettingsController@slipwayStore')->name('mwe.setting.slipway-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@slipwayEdit')->name('mwe.setting.slipway-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@slipwayUpdate')->name('mwe.setting.slipway-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@slipwayDestroy')->name('mwe.setting.slipway-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@slipwayDatatable')->name('mwe.setting.slipway-datatable');
    });

    Route::prefix('maintenance-schedule-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@maintenanceScheduleIndex')->name('mwe.setting.maintenance-schedule');
        Route::post('/', 'Mwe\SettingsController@maintenanceScheduleStore')->name('mwe.setting.maintenance-schedule-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@maintenanceScheduleEdit')->name('mwe.setting.maintenance-schedule-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@maintenanceScheduleUpdate')->name('mwe.setting.maintenance-schedule-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@maintenanceScheduleDestroy')->name('mwe.setting.maintenance-schedule-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@maintenanceScheduleDatatable')->name('mwe.setting.maintenance-schedule-datatable');
        Route::get('/get-last-maintenance-date/{id}', 'Mwe\SettingsController@getLastMaintenanceSchedule')->name('mwe.setting.get-last-maintenance-date');
    });

    Route::prefix('workshop-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@workshopIndex')->name('mwe.setting.workshop-setting');
        Route::post('/', 'Mwe\SettingsController@workshopStore')->name('mwe.setting.workshop-setting-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@workshopEdit')->name('mwe.setting.workshop-setting-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@workshopUpdate')->name('mwe.setting.workshop-setting-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@workshopDestroy')->name('mwe.setting.workshop-setting-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@workshopDatatable')->name('mwe.setting.workshop-setting-datatable');

    });

    Route::prefix('unit-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@unitIndex')->name('mwe.setting.unit-setting');
        Route::post('/', 'Mwe\SettingsController@unitStore')->name('mwe.setting.unit-setting-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@unitEdit')->name('mwe.setting.unit-setting-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@unitUpdate')->name('mwe.setting.unit-setting-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@unitDestroy')->name('mwe.setting.unit-setting-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@unitDatatable')->name('mwe.setting.unit-setting-datatable');

    });

    Route::prefix('inspection-job-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@inspectionJobIndex')->name('mwe.setting.inspection-job-setting');
        Route::post('/', 'Mwe\SettingsController@inspectionJobStore')->name('mwe.setting.inspection-job-setting-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@inspectionJobEdit')->name('mwe.setting.inspection-job-setting-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@inspectionJobUpdate')->name('mwe.setting.inspection-job-setting-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@inspectionJobDestroy')->name('mwe.setting.inspection-job-setting-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@inspectionJobDatatable')->name('mwe.setting.inspection-job-setting-datatable');

    });

    Route::prefix('product-setting')->group(function () {
        Route::get('/', 'Mwe\SettingsController@productIndex')->name('mwe.setting.product-setting');
        Route::post('/', 'Mwe\SettingsController@productStore')->name('mwe.setting.product-setting-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@productEdit')->name('mwe.setting.product-setting-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@productUpdate')->name('mwe.setting.product-setting-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@productDestroy')->name('mwe.setting.product-setting-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@productDatatable')->name('mwe.setting.product-setting-datatable');

    });

    Route::prefix('work-setup')->group(function () {
        Route::get('/', 'Mwe\SettingsController@workIndex')->name('mwe.setting.work-setup');
        Route::post('/', 'Mwe\SettingsController@workStore')->name('mwe.setting.work-setup-store');
        Route::get('/edit/{id}', 'Mwe\SettingsController@workEdit')->name('mwe.setting.work-setup-edit');
        Route::put('/edit/{id}', 'Mwe\SettingsController@workUpdate')->name('mwe.setting.work-setup-update');
        Route::delete('/delete/{id}', 'Mwe\SettingsController@workDestroy')->name('mwe.setting.work-setup-destroy');
        Route::post('/datatable/{id}', 'Mwe\SettingsController@workDatatable')->name('mwe.setting.work-setup-datatable');

    });


    /****************************************************Operation***********************************/

    Route::prefix('maintenance-request')->group(function () {

        Route::get('/', 'Mwe\MaintenanceRequestController@index')->name('mwe.operation.maintenance-request');
        Route::post('/', 'Mwe\MaintenanceRequestController@store')->name('mwe.operation.maintenance-request-store');
        Route::get('/edit/{id}', 'Mwe\MaintenanceRequestController@edit')->name('mwe.operation.maintenance-request-edit');
        Route::put('/edit/{id}', 'Mwe\MaintenanceRequestController@update')->name('mwe.operation.maintenance-request-update');
        Route::delete('/delete/{id}', 'Mwe\MaintenanceRequestController@destroy')->name('mwe.operation.maintenance-request-destroy');
        Route::post('/datatable/{id}', 'Mwe\MaintenanceRequestController@datatable')->name('mwe.operation.maintenance-request-datatable');
        Route::post('/report/render/{title}', 'Report\OraclePublisherController@render')->name('report');
        Route::get('/report/render/{title?}', 'Report\OraclePublisherController@render')->name('report-get');

        Route::get('/maintenance-req-auth-by-xen/{id}', 'Mwe\MaintenanceRequestController@authorizedByXEN')->name('mwe.operation.maintenance-req-auth-by-xen');
        Route::post('/maintenance-req-auth-by-xen/{id}', 'Mwe\MaintenanceRequestController@storeAuthorizedByXEN')->name('mwe.operation.maintenance-req-auth-by-xen-store');

        Route::get('/maintenance-req-auth-by-den/{id}', 'Mwe\MaintenanceRequestController@authorizedByDEN')->name('mwe.operation.maintenance-req-auth-by-den');
        Route::post('/maintenance-req-auth-by-den/{id}', 'Mwe\MaintenanceRequestController@storeAuthorizedByDEN')->name('mwe.operation.maintenance-req-auth-by-den-store');
    });

    Route::prefix('request-inspection')->group(function () {

        Route::get('/', 'Mwe\RequestInspectionController@index')->name('mwe.operation.request-inspection');
        Route::get('/{id}', 'Mwe\RequestInspectionController@makeInspectionReport')->name('mwe.operation.request-inspection-report');
        Route::get('/inspection/job/{id}', 'Mwe\RequestInspectionController@getInspectionJob')->name('mwe.operation.maintenance-inspection-job');
        Route::post('/{id}', 'Mwe\RequestInspectionController@store')->name('mwe.operation.request-inspection-store');
        Route::post('/inspection-job/add', 'Mwe\RequestInspectionController@addInspectionJob')->name('mwe.operation.request-add-inspection-job');
        Route::post('/vessel-inspector/add', 'Mwe\RequestInspectionController@addVesselInspector')->name('mwe.operation.add-vessel-inspector');
        Route::delete('/vessel-inspector/delete', 'Mwe\RequestInspectionController@removeVesselInspector')->name('mwe.operation.delete-vessel-inspector');
        Route::delete('/inspection-job/delete/by-inspector', 'Mwe\RequestInspectionController@removeInspectionByInspector')->name('mwe.operation.delete-inspection.by-inspector');
        Route::get('/inspection/view/{id}', 'Mwe\RequestInspectionController@viewInspectionByInspectorJob')->name('mwe.operation.view-inspection');
        Route::delete('/delete/{id}', 'Mwe\RequestInspectionController@removeInspectionJob')->name('mwe.operation.remove-inspection-job');
        Route::post('/datatable/{id}', 'Mwe\RequestInspectionController@datatable')->name('mwe.operation.request-inspection-datatable');
    });

    Route::prefix('inspection-order')->group(function () {
        Route::get('/', 'Mwe\InspectionController@index')->name('mwe.operation.inspection-order');
        Route::get('view/{id}', 'Mwe\InspectionController@view')->name('mwe.operation.inspection-order-view');
        Route::get('/inspection/job/{id}', 'Mwe\InspectionController@getInspectionJob')->name('mwe.operation.maintenance-inspection-order-job');
        Route::post('/inspection-job/add', 'Mwe\InspectionController@addInspectionJob')->name('mwe.operation.add-inspection-order-job');
        Route::delete('/inspection-job/delete', 'Mwe\InspectionController@removeInspectionJob')->name('mwe.operation.remove-inspection-order-job');
        Route::post('/datatable/{id}', 'Mwe\InspectionController@datatable')->name('mwe.operation.inspection-order-datatable');
        Route::get('/download/{id}', 'Mwe\InspectionController@inspectionOrderDownload')->name('mwe.operation.inspection-order-download');
        Route::post('/inspection-confirm-by-ssae', 'Mwe\InspectionController@inspConfirmSSAE')->name('mwe.operation.inspection-confirm-by-ssae');

        Route::get('/get-data-from-job-no', 'Mwe\InspectionController@getDataFromJob')->name("mwe.operation.get-data-from-job-no");
    });

    Route::prefix('workshop-order')->group(function () {

        Route::get('/', 'Mwe\WorkshopOrderController@index')->name('mwe.operation.workshop-order');
        Route::get('view/{id}', 'Mwe\WorkshopOrderController@view')->name('mwe.operation.workshop-order-view');
        Route::post('view/{id}', 'Mwe\WorkshopOrderController@store')->name('mwe.operation.workshop-order-store');
        Route::delete('/delete-inspection-job', 'Mwe\WorkshopOrderController@deleteInspectionJob')->name('mwe.operation.delete-inspection-job');
        Route::post('/datatable/{id}', 'Mwe\WorkshopOrderController@datatable')->name('mwe.operation.workshop-order-datatable');
    });

    Route::prefix('workshop-requisition')->group(function () {

        Route::get('/', 'Mwe\WorkshopRequisitionController@index')->name('mwe.operation.workshop-requisition');
        Route::get('/{id}/{workshopId}', 'Mwe\WorkshopRequisitionController@makeRequisition')->name('mwe.operation.workshop-requisition-create');
        Route::post('/item/add', 'Mwe\WorkshopRequisitionController@addRequisitionItem')->name('mwe.operation.workshop-requisition-item-add');
        Route::post('/show/task-details', 'Mwe\WorkshopRequisitionController@showWorkshopTaskDetails')->name('mwe.operation.workshop-requisition-task-details');
        Route::post('/process/workshop-req', 'Mwe\WorkshopRequisitionController@processWorkshopRequisition')->name('mwe.operation.workshop-requisition-process');
        Route::post('/complete/workshop-req', 'Mwe\WorkshopRequisitionController@completeWorkshopRequisition')->name('mwe.operation.workshop-requisition-complete');
        Route::delete('/delete-req/item', 'Mwe\WorkshopRequisitionController@removeWorkshopReqItem')->name('mwe.operation.remove-workshop-req-item');
        Route::post('/datatable', 'Mwe\WorkshopRequisitionController@datatable')->name('mwe.operation.workshop-requisition-datatable');
    });

    Route::prefix('workshop-requisition-auth')->group(function () {

        Route::get('/', 'Mwe\RequestInspectionAuthController@index')->name('mwe.operation.workshop-requisition-auth');
        Route::get('/{id}/{workshopId}', 'Mwe\RequestInspectionAuthController@makeAuthRequisition')->name('mwe.operation.workshop-auth-requisition-create');
        Route::post('/show/task-details', 'Mwe\RequestInspectionAuthController@showWorkshopTaskDetails')->name('mwe.operation.workshop-req-auth-task-details');
        Route::post('/authorized/workshop-req', 'Mwe\RequestInspectionAuthController@authorizedWorkshopRequisition')->name('mwe.operation.workshop-requisition-authorized');
        Route::post('/datatable', 'Mwe\RequestInspectionAuthController@datatable')->name('mwe.operation.workshop-auth-requisition-datatable');
    });

    Route::prefix('third-party-assign')->group(function () {
        Route::get('/{maintenance_req_id}/{workshopId}/{vessel_inspection_id}', 'Mwe\RequestThirdPartyController@index')->name('mwe.operation.third-party-assign');
        Route::post('/third-party-assign-post', 'Mwe\RequestThirdPartyController@post')->name('mwe.operation.third-party-assign-post');
        Route::post('/add-new-task-dtl', 'Mwe\RequestThirdPartyController@addNewDtl')->name('mwe.operation.third-party-new-task-dtl');
        Route::post('/entry-task-monitor-datatable', 'Mwe\RequestThirdPartyController@monitordataTableList')->name('mwe.operation.entry-task-monitor-datatable');
    });

    Route::prefix('third-party-requests')->group(function () {
        Route::get('/', 'Mwe\ThirdPartyRequestsController@index')->name('mwe.operation.third-party-requests');
        Route::post('/third-party-requests-datatable', 'Mwe\ThirdPartyRequestsController@dataTableList')->name('mwe.operation.third-party-requests-datatable');
        Route::get('/{id}', 'Mwe\ThirdPartyRequestsController@assignThirdparty')->name('mwe.operation.third-party-req-assign');
        Route::get('/request-data-remove', 'Mwe\ThirdPartyRequestsController@removeData')->name('mwe.operation.request-data-remove');
        Route::post('/add-new-party', 'Mwe\ThirdPartyRequestsController@addNewParty')->name('mwe.operation.add-new-party');
        Route::post('/assign-party', 'Mwe\ThirdPartyRequestsController@assignParty')->name('mwe.operation.assign-party');
    });

    Route::prefix('third-party-request-approval')->group(function () {
        Route::get('/', 'Mwe\ThirdPartyRequestsApprovalController@index')->name('mwe.operation.third-party-request-approval');
        Route::post('/third-party-requests-approval-datatable', 'Mwe\ThirdPartyRequestsApprovalController@dataTableList')->name('mwe.operation.third-party-request-approval-datatable');
        Route::get('/get-dtl/{thirdparty_req_id}', 'Mwe\ThirdPartyRequestsApprovalController@getDtlData')->name("mwe.operation.third-party-request-approval-data");
        Route::post('/forward', 'Mwe\ThirdPartyRequestsApprovalController@forwardData')->name('mwe.operation.forward-to');
        Route::post('/only-dtl-datatable', 'Mwe\ThirdPartyRequestsApprovalController@onlyDtldataTable')->name('mwe.operation.only-dtl-datatable');
        Route::post('/add-new-task-dtl', 'Mwe\ThirdPartyRequestsApprovalController@addNewDtl')->name('mwe.operation.third-party-approval-new-task-dtl');
    });

    Route::prefix('third-party-tasks')->group(function () {
        Route::get('/', 'Mwe\ThirdPartyTaskMonitorController@index')->name('mwe.operation.third-party-tasks-monitor-index');
        Route::post('/third-party-tasks-datatable', 'Mwe\ThirdPartyTaskMonitorController@dataTableList')->name('mwe.operation.third-party-tasks-datatable');
        Route::get('/{id}', 'Mwe\ThirdPartyTaskMonitorController@taskMonitor')->name('mwe.operation.third-party-tasks-monitor');
        Route::post('/task-monitor-datatable', 'Mwe\ThirdPartyTaskMonitorController@monitordataTableList')->name('mwe.operation.task-monitor-datatable');
        Route::post('/add-new-task-dtl', 'Mwe\ThirdPartyTaskMonitorController@addNewDtl')->name('mwe.operation.add-new-task-dtl');
        Route::post('/task-dtl-data-submit', 'Mwe\ThirdPartyTaskMonitorController@taskDtlPost')->name('mwe.operation.task-dtl-data-submit');
        Route::get('/task-final-submit/{id}', 'Mwe\ThirdPartyTaskMonitorController@taskFinalSubmit')->name('mwe.operation.task-final-submit');
        Route::post('/status-check', 'Mwe\ThirdPartyTaskMonitorController@statusChk')->name('mwe.operation.status-check');
        Route::post('/change-info', 'Mwe\ThirdPartyTaskMonitorController@changeInfo')->name('mwe.operation.change-info');
    });

    Route::get('/get-monitor-data', 'Mwe\ThirdPartyTaskMonitorController@getMonitorData')->name('mwe.operation.get-monitor-data');
    Route::get('/data-remove', 'Mwe\ThirdPartyTaskMonitorController@removeData')->name('mwe.operation.task-data-remove');

    //Notification
    Route::prefix('notification')->group(function () {
        Route::get('/', 'Mwe\NotificationController@index')->name('notifications');
        Route::get('notifications-count', 'Mwe\NotificationController@ajaxNotificationCount')->name('notificationCount');
        Route::get('notifications-recent', 'Mwe\NotificationController@ajaxNotificationsBar')->name('notificationRecent');

    });

    Route::prefix('reports')->group(function () {
        Route::get('/', 'Mwe\ReportGeneratorController@index')->name('mwe.reports');
        Route::get('/report-generator-params/{id}', 'Mwe\ReportGeneratorController@reportParams')->name('mwe.report-params');

    });

});
// For News
Route::get('/get-top-news', 'NewsController@getNews')->name('get-top-news');
Route::get('/news-download/{id}', 'NewsController@downloadAttachment')->name('news-download');

/*******************************End Marine workshop engineer**************************/



<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{env('APP_NAME')}} @yield('title', 'Electricity and Water Billing Management System')</title>
	<link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.html')}}">
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/logo/cns_favicon.png')}}">
	<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

	<!-- BEGIN: Vendor CSS-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/vendors.min.css')}}">
	<style>
		@font-face {
			font-family: boxicons;
			font-weight: 400;
			font-style: normal;
			src: url({{asset('assets/fonts/boxicons/fonts/boxicons.eot')}});
			src: url({{asset('assets/fonts/boxicons/fonts/boxicons.eot')}}) format('embedded-opentype'), url({{asset('assets/fonts/boxicons/fonts/boxicons.woff2')}}) format('woff2'), url({{asset('assets/fonts/boxicons/fonts/boxicons.woff')}}) format('woff'), url({{asset('assets/fonts/boxicons/fonts/boxicons.ttf')}}) format('truetype'), url({{asset('assets/fonts/boxicons/fonts/boxicons.svg?#boxicons')}}) format('svg');
		}
	</style>
    <link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/wickedpicker/src/wickedpicker.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/charts/apexcharts.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/extensions/dragula.min.css')}}">

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/forms/select/select2.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/pickadate/pickadate.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/tables/datatable/datatables.min.css')}}">

	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/editors/quill/katex.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/editors/quill/monokai-sublime.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/editors/quill/quill.snow.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/css/editors/quill/quill.bubble.css')}}">
	<!-- END: Vendor CSS-->

	<!-- BEGIN: Theme CSS-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/datetime/tempusdominus-bootstrap-4.min.css')}}" />

	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-extended.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/colors.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/components.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/themes/dark-layout.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/themes/semi-dark-layout.min.css')}}">
	<!-- END: Theme CSS-->

	<!-- BEGIN: Application global common -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/common.css')}}">
	<!-- END: Application global common -->

	<!-- BEGIN: Page CSS-->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/core/menu/menu-types/vertical-menu.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/dashboard-analytics.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/pages/app-file-manager.min.css')}}">

	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/plugins/forms/validation/form-validation.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/css/ewb/jquery-steps/jquery.steps.css')}}">
	<!-- END: Page CSS-->

	<style>
		.bg-cus-blue {
			background: #122b5a;
		}
	</style>
	<script>
		var APP_URL = "{{ url('/') }}";
	</script>
	@yield('header-style')
</head>
<!-- END: Head-->


<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern content-left-sidebar file-manager-application semi-dark-layout 2-columns navbar-sticky footer-static  "
      data-open="click" data-menu="vertical-menu-modern" data-col="content-left-sidebar" data-col="2-columns"
      data-layout="semi-dark-layout">
<!-- BEGIN Header-->


@include('layouts.partial.header')
@include('layouts.partial.sidebar')

<!-- BEGIN: Content-->
<div class="app-content content mt-5">
	<div class="content-overlay"></div>
	<div class="content-wrapper">
        <!--Preloader start-->
        <div class="loading-page-overlay" id="loading_page_loader">
                <span class="center-loader">
                    <img style="" src="{{asset('assets/images/loader.gif')}}"/>
                <h5>Loading...</h5>
                </span>
        </div>
        <!--Preloader end-->

        <br>
		<div class="content-body">
            <br>
            	@include('layouts.partial.flash-message')
				@yield('content')
		</div>
	</div>
</div>

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

@include('layouts.partial.footer')

<!-- BEGIN: Vendor JS-->
<script src="{{asset('assets/vendors/js/vendors.min.js')}}"></script>
<script src="{{asset('assets/wickedpicker/src/wickedpicker.min.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>

<script type="text/javascript" src="{{asset('assets/datetime/2.22.2-moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/datetime/tempusdominus-bootstrap-4.min.js')}}"></script>

<script src="{{asset('assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.min.js')}}"></script>
<script src="{{asset('assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.min.js')}}"></script>
<script src="{{asset('assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js')}}"></script>

<script src="{{asset('assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
<script src="{{asset('assets/vendors/js/pickers/daterange/moment.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
<!-- END Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{asset('assets/vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/extensions/dragula.min.js')}}"></script>

<script src="{{asset('assets/vendors/js/extensions/jquery.steps.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('assets/vendors/js/editors/quill/katex.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/editors/quill/highlight.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/editors/quill/quill.min.js')}}"></script>



<!-- BEGIN: Theme JS-->
<script src="{{asset('assets/js/scripts/configs/vertical-menu-light.min.js')}}"></script>
<script src="{{asset('assets/js/core/app-menu.min.js')}}"></script>
<script src="{{asset('assets/js/core/app.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/components.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/footer.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/customizer.min.js')}}"></script>

<script src="{{asset('assets/vendors/js/extensions/jquery.steps.min.js')}}"></script>
<script src="{{asset('assets/vendors/js/forms/validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/ewb/plugins/jquery-validation-additional-methods/additional-methods.min.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Application global common -->
<script src="{{asset('assets/js/scripts/common.js')}}"></script>
<!-- END: Application global common -->

<!-- BEGIN: Page JS-->
<script src="{{asset('assets/js/scripts/pages/dashboard-analytics.min.js')}}"></script>

<script src="{{asset('assets/js/scripts/forms/select/form-select2.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/datatables/datatable.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/forms/validation/form-validation.js')}}"></script>
<script src="{{asset('assets/js/scripts/forms/wizard-steps.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/editors/editor-quill.min.js')}}"></script>

<script src="{{asset('assets/js/scripts/pages/app-file-manager.min.js')}}"></script>

<script src="{{asset('assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('assets/js/scripts/extensions/sweet-alerts.min.js')}}"></script>

@yield('footer-script')
</body>

<script>
    // jQuery(function ($) {
    //     $(document).on('click', '.datepicker-input-mask', function () {
    //         var me = $(".datepicker-input-mask");
    //         me.datepicker({
    //             showOn: 'focus',
    //             altFormat: "mm/dd/yy",
    //             dateFormat: "mm/dd/yy",
    //             minDate: '01/01/1900',
    //             maxDate: '12/31/2050',
    //             changeMonth: true,
    //             changeYear: true,
    //             showWeek: true,
    //             firstDay: 1
    //         }).focus();
    //     }).on('focus', '.datepicker-input-mask', function () {
    //         var me = $(".datepicker-input-mask");
    //         me.mask('99/99/9999');
    //     });
    //
    // });

    //onload call
    $(function () {
        notificationsCount();
        setInterval(notificationsCount, 50000);
    });

    function notificationsCount() {
        let notificationCount = '{{Route('notificationCount')}}';
        let resData = ajaxNotifications(notificationCount);
    }

    function ajaxNotifications(url) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json', // added data type
            success: function (res) {//console.log(res);
                var html = '';
                if (res.count > 0) {
                    $('#notification-counter').text(res.count);
                    $('#notification-counter').show();
                    if(res.data.length >  0){
                        res.data.forEach(function (v, i) {
                            let link = v.web_message_link;
                            let target = "target=\"_blank\"";
                            if(link){
                                link = APP_URL + link;
                            }else{
                                link = 'javascript:void(0)';
                                target = '';
                            }
                            html += '<li class="scrollable-container media-list ps"><a class="d-flex justify-content-between" '+target+' href='+link+'>' +
                                '                                    <div class="media d-flex align-items-center">' +
                                '                                        <div class="media-body">' +
                                '                                            <h6 class="media-heading"><span class="text-bold-500">'+v.notification_type+': </span> '+v.web_message+'</h6><small class="notification-text">Created at '+v.created_at+'</small>\n' +
                                '                                        </div>' +
                                '                                    </div></a>' +
                                '                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></li>' +
                                '';

                        });
                    }
                    $('#notification-footer').show();
                }else{
                    html += '<li class="scrollable-container media-list ps"><a class="d-flex justify-content-between" href="javascript:void(0)">' +
                        '                                    <div class="media d-flex align-items-center">' +
                        '                                        <div class="media-body">' +
                        '                                            <h6 class="media-heading">No notification found.</h6>\n' +
                        '                                        </div>' +
                        '                                    </div></a>' +
                        '                            <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></li>' +
                        '';
                    $('#notification-footer').hide();
                }
                $('#notification-body').html(html);
            }
        });
    }
    $(".dynamicModal").on("click", function () {
        var news_id=this.getAttribute('news_id');
        $.ajax(
            {
                type: 'GET',
                url: '/get-top-news',
                data: {news_id:news_id},
                dataType: "json",
                success: function (data) {
                    $("#dynamicNewsModalContent").html(data.newsView);
                    $('#dynamicNewsModal').modal('show');
                }
            }
        );

    });
</script>
<!-- END: Body-->
</html>

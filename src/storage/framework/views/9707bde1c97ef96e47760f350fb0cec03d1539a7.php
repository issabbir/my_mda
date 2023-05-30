<?php $__env->startSection('title'); ?>
    License duty
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-style'); ?>
    <!--Load custom style link or css-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> Swing Mooring
                            Service </h4>
                        <form method="POST" action="">
                            <?php echo e(isset($data->id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label >Reporting Vessel
                                                Name</label>
                                            <select name="cpa_vessel" class="form-control select2">
                                                <option value="">Select one</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $cpaVesselNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cpaVesselName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option
                                                        <?php echo e((old("cpa_vessel", $data->cpa_vessel_id) == $cpaVesselName->id) ? "selected" : ""); ?> value="<?php echo e($cpaVesselName->id); ?>"><?php echo e($cpaVesselName->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">CPA Vessel Name empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('cpa_vessel')): ?>
                                                <span class="help-block"><?php echo e($errors->first('cpa_vessel')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label >Mooring license
                                                representative</label>
                                            <select name="lm_rep" class="form-control lmRep"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                
                                                <?php if(isset($data->employee)): ?>
                                                    <option selected
                                                            value="<?php echo e(old('lm_rep', $data->employee->emp_id)); ?>"><?php echo e($data->employee->emp_name); ?></option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('lm_rep')): ?>
                                                <span class="help-block"><?php echo e($errors->first('lm_rep')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Designation: </label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="p_desig"
                                                   readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Department: </label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="p_dept"
                                                   readonly
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="input-required">Mooring Use date<span
                                                        class="required"></span></label>
                                                <div class="input-group date"
                                                     onfocusout="$(this).datetimepicker('hide')" id="visit_date"
                                                     data-target-input="nearest">
                                                    <input type="text" autocomplete="off"
                                                           name="visit_date"
                                                           value="<?php echo e(old('visit_date', $data->visit_date)); ?>"
                                                           class="form-control datetimepicker-input "
                                                           data-target="#visit_date"
                                                           data-toggle="datetimepicker"
                                                           placeholder="Visit date"/>
                                                    <div class="input-group-append" data-target="#visit_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if($errors->has('visit_date')): ?>
                                                    <span class="help-block"><?php echo e($errors->first('visit_date')); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Swing Mooring No<span class="required"></span></label>
                                            <select name="swing_moorings" class="select2">
                                                <option value="">Select one</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $swingMooringsNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $swingMooringsName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option
                                                        <?php echo e((old("swing_moorings", $data->swing_mooring_id) == $swingMooringsName->id) ? "selected" : ""); ?> value="<?php echo e($swingMooringsName->id); ?>"><?php echo e($swingMooringsName->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">Swing Moorings Name empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('swing_moorings')): ?>
                                                <span class="help-block"><?php echo e($errors->first('swing_moorings')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Local Vessel<span
                                                    class="required"></span></label>
                                            <select name="local_vessel" class="select2 l_vessel_id">
                                                <option value="">Select one</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $localVesselNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localVesselName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option
                                                        <?php echo e((old("local_vessel", $data->local_vessel_id) == $localVesselName->id) ? "selected" : ""); ?> value="<?php echo e($localVesselName->id); ?>"><?php if($localVesselName->reg_no!=null): ?><?php echo e($localVesselName->name.' ('.$localVesselName->reg_no.')'); ?> <?php else: ?> <?php echo e($localVesselName->name); ?> <?php endif; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">Local Vessel Name empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <input type="hidden" id="agent_id" name="agent_id" class="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')"
                                                    class="btn btn btn-dark shadow mr-1 mb-1">  <?php echo e(isset($data->id)?'Update':'Save'); ?> </button>
                                            <a type="reset" href="<?php echo e(route("sm-license-duty-entry")); ?>"
                                               class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--List-->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">License Duty Entry List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <form method="POST" action="" id="search-form">
                            <?php echo e(isset($data->id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Visit From Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="from_date" id="from_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            <?php if($errors->has("from_date")): ?>
                                                <span class="help-block"><?php echo e($errors->first("from_date")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Visit To Date<span
                                                    class="required"></span></label>
                                            <input type="date"
                                                   name="to_date" id="to_date" class="form-control" required
                                                   value=""
                                                   autocomplete="off">
                                            <?php if($errors->has("to_date")): ?>
                                                <span class="help-block"><?php echo e($errors->first("to_date")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">&nbsp;</label>
                                            <div class="d-flex justify-content-end col">
                                                <button type="button" name="search" id="search"
                                                        class="btn btn btn-dark shadow mr-1 mb-1"> Search
                                                </button>
                                                <a type="reset" href="<?php echo e(route("sm-license-duty-entry")); ?>"
                                                   class="btn btn btn-outline-dark  mb-1"> Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Visit Date</th>
                                    <th>Swing Mooring</th>
                                    
                                    <th>Local Vessel</th>
                                    <th>CPA Vessel</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('footer-script'); ?>
    <!--Load custom script-->
    <script>
        let shippingAgent = '<?php echo e(route('get-shipping-agent')); ?>';
        let lastAgent = '<?php echo e(route('get-last-agent')); ?>';

        $('.ship_agent').select2({
            placeholder: "Select one",
            ajax: {
                url: shippingAgent,
                data: function (params) {
                    if (params.term) {
                        if (params.term.trim().length < 1) {
                            return false;
                        }
                    } else {
                        return false;
                    }

                    return params;
                },
                dataType: 'json',
                processResults: function (data) {
                    var formattedResults = $.map(data, function (obj, idx) {
                        obj.id = obj.agency_id;
                        obj.text = obj.agency_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $('.l_vessel_id').change(function () {
            let val = $(this).val();
            $.ajax({
                type: 'get',
                url: lastAgent,
                data: {vessel_id: val},
                success: function (msg) {
                    console.log(msg);
                    let data = msg.split('+');

                    $('#agent_id').val(data[0]);
                    $('#agent_name').val(data[1]);
                    if (data[0] == '') {
                        $("#change_label").addClass("required");
                        $('#ship_agent').prop('required', true);
                    } else {
                        $("#change_label").removeClass("required");
                        $("#ship_agent").removeAttr("required");
                    }

                }
            });
        });
        $(document).ready(function () {
            datePicker("#visit_date");
            datePicker("#fromDate");
            datePicker("#toDate");

            $('.lmRep').change(function () {
                let val = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '/sm-license-duty-entry/sm-pilot-dtl',
                    data: {pid: val},
                    success: function (msg) {
                        //console.log(msg);
                        $('#p_desig').val(msg.designation);
                        $('#p_dept').val(msg.department_name);
                    }
                });
            });

            $(".lmRep").select2({
                placeholder: "Select one",
                allowClear: false,
                minimumInputLength: 1,
                ajax: {
                    delay: 250,
                    url: '/sm-license-duty-entry/sm-pilot-list',
                    dataType: 'json',
                    data: function (params) {
                        var queryParameters = {
                            q: params.term
                        };

                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.emp_name,
                                    id: item.emp_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });


            var oTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '<?php echo e(route('sm-license-duty-entry-datatable', isset($data->id)?$data->id:0 )); ?>',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    data: function (d) {
                        d.from_date = $('input[name=from_date]').val();
                        d.to_date = $('input[name=to_date]').val();
                    }
                },
                /*"columns": [
                    {"data": "visit_date"},
                    {"data": "swing_moorings.name"},
                    // {"data": "sl_no"},
                    {"data": "local_vessel.name"},
                    {"data": "cpa_vessel.name"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],*/
                "columns": [
                    {"data": "visit_date"},
                    {"data": "swing_mooring_name"},
                    // {"data": "sl_no"},
                    {"data": "local_vessel"},
                    {"data": "cpa_vessel"},
                    {"data": "status"},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });

            $('#search').on('click', function (e) {
                oTable.draw();
                e.preventDefault();


            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mda/sm_license_duty_entry.blade.php ENDPATH**/ ?>
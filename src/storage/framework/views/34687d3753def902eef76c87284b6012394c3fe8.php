<?php $__env->startSection('title'); ?>
    Company Vessel Setup
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-style'); ?>
    <style>
        .wickedpicker {
            z-index: 1151 !important;
        }

        .event-full {
            color: #fff;
            vertical-align: middle !important;
            text-align: center;
            opacity: 1;
        }
    </style>
    <!--Load custom style link or css-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <?php if(Session::has('message')): ?>
                    <div
                        class="alert <?php echo e(Session::get('m-class') ? Session::get('m-class') : 'alert-danger'); ?> show"
                        role="alert">
                        <?php echo e(Session::get('message')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title"> <?php echo e(isset($data->cv_id)?'Edit':'Add'); ?> Company Vessel Information</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <?php echo e(isset($data->cv_id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="required">Company</label>
                                    <select class="custom-select select2 form-control"
                                            id="comp_id" name="comp_id">
                                        <option value="">Select One</option>
                                        <?php $__currentLoopData = $companyList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value->comp_id); ?>"
                                                <?php echo e(isset($data->comp_id) && $data->comp_id == $value->comp_id ? 'selected' : ''); ?>

                                            ><?php echo e($value->company_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="required">Vessel</label>
                                    <select name="vessel_id" id="vessel_id"
                                    class="form-control vessel_id select2" autocomplete="off">
                                        <?php if(isset($data) && $data!=null): ?>
                                            <option
                                                value="<?php echo e($data->vessel_id); ?>"><?php echo e($data->vessel_name); ?></option>
                                        <?php else: ?>
                                            <option value="">Select one</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-required">Owner Name</label>
                                    <input type="text" name="owner_name" id="owner_name"
                                           class="form-control"
                                           value="<?php echo e(isset($data)?$data->v_owner_name:''); ?>"
                                           readonly>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <label>Owner Address</label>
                                    <textarea type="text" rows="3" name="owner_address" readonly id="owner_address"
                                              class="form-control"><?php echo e(isset($data)?$data->v_owner_address:''); ?></textarea>
                                    <?php if($errors->has("owner_address")): ?>
                                        <span class="help-block"><?php echo e($errors->first("owner_address")); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <label class="required">Date</label>
                                    <input type="date" name="v_assign_date"
                                           class="form-control" required
                                           value="<?php echo e(isset($data->v_assign_date) ? date('Y-m-d',strtotime($data->v_assign_date)) : ''); ?>"
                                    >
                                </div>
                                
                                <div class="col-md-4 mt-1">
                                    <label>Schedule From Time</label>
                                    <div class="input-group date"
                                         onfocusout="$(this).datetimepicker('hide')" id="schedule_from_time"
                                         data-target-input="nearest">
                                        <input type="text" id="schedule_from_time" autocomplete="off"
                                               class="form-control fromTime" name="schedule_from_time"
                                               data-target="#schedule_from_time" data-toggle="datetimepicker"
                                               placeholder="From time"
                                               value="<?php echo e(old('schedule_from_time', isset($data->v_assign_from) ? date('H:i',strtotime($data->v_assign_from)) : '')); ?>"
                                               oninput="this.value = this.value.toUpperCase()"/>
                                        <div class="input-group-append" data-target="#schedule_from_time"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <label>Schedule To Time</label>
                                    <div class="input-group date"
                                         onfocusout="$(this).datetimepicker('hide')" id="schedule_to_time"
                                         data-target-input="nearest">
                                        <input type="text" id="schedule_to_time" autocomplete="off" name="schedule_to_time"
                                               class="form-control toTime"
                                               data-target="#schedule_to_time" data-toggle="datetimepicker"
                                               placeholder="To time"
                                               value="<?php echo e(old('schedule_from_time', isset($data->v_assign_to) ? date('H:i',strtotime($data->v_assign_to)) : '')); ?>"
                                               oninput="this.value = this.value.toUpperCase()"/>
                                        <div class="input-group-append" data-target="#schedule_to_time"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="bx bx-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Status</label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="<?php echo e(old('status','Y')); ?>"
                                                                   <?php echo e(isset($data->active_yn) && $data->active_yn == "Y" ? 'checked' : ''); ?> name="status"
                                                                   id="customRadio2" checked="">
                                                            <label class="custom-control-label" for="customRadio2">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="<?php echo e(old('status','N')); ?>"
                                                                   <?php echo e(isset($data->active_yn) && $data->active_yn == "N" ? 'checked' : ''); ?> name="status"
                                                                   id="customRadio1">
                                                            <label class="custom-control-label" for="customRadio1">Inactive</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                            <?php if($errors->has('status')): ?>
                                                <span class="help-block"><?php echo e($errors->first('status')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <div class="row my-1">
                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save"
                                                        onclick="return confirm('Are you sure?')"
                                                        class="btn btn btn-dark shadow mr-1 mb-1">  <?php echo e(isset($data->comp_id)?'Update':'Save'); ?> </button>
                                                <a type="reset" href="<?php echo e(route("company-vessel-setup")); ?>"
                                                   class="btn btn btn-outline-dark  mb-1"> Cancel</a>
                                            </div>
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
                    <h4 class="card-title">Company Vessel List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Vessel</th>
                                    <th>Company</th>
                                    <th>Assign Date</th>
                                    <th>Assign From</th>
                                    <th>Assign To</th>
                                    <th>Active?</th>
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
        /*$('.from-timepicker').wickedpicker({
            title: 'From',
            clearable: true,
            time: 1,

             now: "<?php echo e(isset($data->v_assign_from)?date('H:i',strtotime($data->v_assign_from)) : '00:00'); ?>", //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
        });

        $('#schedule_to_time').wickedpicker({
            title: 'To',
            clearable: true,
            now: "<?php echo e(isset($data->v_assign_to)?date('H:i',strtotime($data->v_assign_to)) : '00:00'); ?>", //hh:mm 24 hour format only, defaults to current time
            twentyFour: true, //Display 24 hour format, defaults to false
        });*/

        $('#vessel_id').select2({

            //minimumInputLength: 1,
            ajax: {
                url: '<?php echo e(route('mwe.setting.search-vessel')); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search_param: params.term
                    };
                },
                processResults: function (data) {console.log(data)
                    return {
                        results: $.map(data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.name,
                            };
                        })
                    };
                },
                cache: false
            },
        });

        $('#vessel_id').on('change', function () {
            let val = $(this).find(":selected").val();
            if (val) {
                $.ajax({
                    type: "GET",
                    url: '<?php echo e(route('mwe.setting.vessel-info')); ?>',
                    data: {vessel_id: val},
                    success: function (data) {
                        $('#owner_name').val(data.owner_name);
                        $('#owner_address').val(data.owner_address);
                    },
                    error: function (data) {
                        alert('error');
                    }
                });
            }
        });


        function companyVesselList() {
            let url = '<?php echo e(route('mwe.setting.company-vessel-setup-datatable')); ?>';
            let oTable = $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: url,
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'vessel_name', name: 'vessel_name', searchable: true},
                    {data: 'company_name', name: 'company_name', searchable: true},
                    {data: 'v_assign_date', name: 'v_assign_date', searchable: true},
                    {data: 'v_assign_from', name: 'v_assign_from', searchable: true},
                    {data: 'v_assign_to', name: 'v_assign_to', searchable: true},
                    {data: 'status', name: 'status', searchable: true},
                    {data: 'action', name: 'action', searchable: false},
                ]
            });
        };

        $(document).ready(function () {
            companyVesselList();
            timePicker24("#schedule_from_time");
            timePicker24("#schedule_to_time");
        });
    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mwe/company_vessel.blade.php ENDPATH**/ ?>
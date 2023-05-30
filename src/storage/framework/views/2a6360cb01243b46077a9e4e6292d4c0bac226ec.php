<?php $__env->startSection('title'); ?>
    Vessel conditions
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
                        <h4 class="card-title"> <?php echo e(isset($data->id)?'Edit':'Add'); ?> Employee Office Setup</h4>
                        <form method="POST" action="">
                            <?php echo e(isset($data->id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-4 mt-1">
                                    <div id="start-no-field" class="form-group">
                                        <label class="required">Office</label>
                                        <select id="office_id" name="office_id" class="form-control select2">
                                            <option value="">Select one</option>
                                            <?php $__empty_1 = true; $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <option
                                                    <?php echo e(($data->office_id == $office->office_id) ? "selected" : ""); ?> value="<?php echo e($office->office_id); ?>"><?php echo e($office->office_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <option value="">Office</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-1">
                                    <label class="required">Employee</label>
                                    <select class="custom-select select2 form-control emp_id" required
                                            style="width: 100%;"
                                            id="emp_id" name="emp_id">
                                        <?php if(isset($data)): ?>
                                            <option
                                                value="<?php echo e($data->emp_id); ?>"><?php echo e($data->emp_name); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-3">
                                    <div class="d-flex justify-content-end col">
                                        <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  <?php echo e(isset($data->id)?'Update':'Save'); ?> </button>
                                        <a type="reset" href="<?php echo e(route("mwe.setting.office-setup")); ?>" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title">EMployee Office Setup List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Office</th>
                                    <th>Employee</th>
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
        let vtmisVessel = '<?php echo e(route('get-all-employee')); ?>';
        $('.emp_id').select2({
            placeholder: "Select one",
            ajax: {
                url: vtmisVessel,
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
                        obj.id = obj.emp_id;
                        obj.text = obj.emp_name;
                        return obj;
                    });
                    return {
                        results: formattedResults,
                    };
                }
            }
        });

        $(document).ready(function () {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function(settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url:'<?php echo e(route('mwe.setting.office-setup-datatable', isset($data->id)?$data->id:0 )); ?>',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "office_name", orderable: true, searchable: true},
                    {"data": "emp_name", orderable: true, searchable: true},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    paginate: {
                        next: '<i class="bx bx-chevron-right">',
                        previous: '<i class="bx bx-chevron-left">'
                    }
                }
            });
        });
    </script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mwe/emp_ofc_setup.blade.php ENDPATH**/ ?>
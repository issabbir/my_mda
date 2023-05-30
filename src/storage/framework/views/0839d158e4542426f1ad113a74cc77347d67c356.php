<?php $__env->startSection('title'); ?>
    Local vessel
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
                        <h4 class="card-title"> <?php echo e(isset($data->id)?'Edit':'Add'); ?> Local Vessel</h4>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <?php echo e(isset($data->id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Name<span class="required"></span></label>
                                            <input type="text" name="name" value="<?php echo e(old('name', $data->name)); ?>" placeholder="Name" class="form-control"   oninput="this.value = this.value.toUpperCase()" />
                                            <?php if($errors->has('name')): ?>
                                                <span class="help-block"><?php echo e($errors->first('name')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>call sign</label>
                                            <input type="text" name="call_sign" value="<?php echo e(old('call_sign', $data->call_sign)); ?>" placeholder=" Call Sign" class="form-control" oninput="this.value = this.value.toUpperCase()"  />
                                            <?php if($errors->has('call_sign')): ?>
                                                <span class="help-block"><?php echo e($errors->first('call_sign')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>flag</label>
                                            <input type="text" name="flag" value="<?php echo e(old('flag', $data->flag)); ?>" placeholder=" Flag" class="form-control"  oninput="this.value = this.value.toUpperCase()"   />
                                            <?php if($errors->has('flag')): ?>
                                                <span class="help-block"><?php echo e($errors->first('flag')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>grt</label>
                                            <input type="number" min="0" name="grt" value="<?php echo e(old('grt', $data->grt)); ?>" placeholder="Grt" class="form-control"   />
                                            <?php if($errors->has('grt')): ?>
                                                <span class="help-block"><?php echo e($errors->first('grt')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>nrt</label>
                                            <input type="number" min="0" name="nrt" value="<?php echo e(old('nrt', $data->nrt)); ?>" placeholder=" Nrt" class="form-control" />
                                            <?php if($errors->has('nrt')): ?>
                                                <span class="help-block"><?php echo e($errors->first('nrt')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>loa</label>
                                            <input type="number" min="0" name="loa" value="<?php echo e(old('loa', $data->loa)); ?>" placeholder="Loa" class="form-control"   />
                                            <?php if($errors->has('loa')): ?>
                                                <span class="help-block"><?php echo e($errors->first('loa')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>max draught</label>
                                            <input type="text" name="max_draught" value="<?php echo e(old('max_draught', $data->max_draught)); ?>" placeholder="Max Draught" class="form-control"   />
                                            <?php if($errors->has('max_draught')): ?>
                                                <span class="help-block"><?php echo e($errors->first('max_draught')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>total crew officer</label>
                                            <input type="number" min="0" name="total_crew_officer" value="<?php echo e(old('total_crew_officer', $data->total_crew_officer)); ?>" placeholder="Total Crew Officer" class="form-control"    />
                                            <?php if($errors->has('total_crew_officer')): ?>
                                                <span class="help-block"><?php echo e($errors->first('total_crew_officer')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>owner</label>
                                            
                                            <select name="agent_id" class="select2 agent_id">
                                                <option value="">Select a type</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $agencyList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option <?php echo e(($data->agency_id == $type->agency_id) ? "selected" : ""); ?> value="<?php echo e($type->agency_id); ?>"><?php echo e($type->agency_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">Owner types empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('owner_name')): ?>
                                                <span class="help-block"><?php echo e($errors->first('owner_name')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Owner Address</label>
                                            <textarea type="text" id="agent_address" name="agent_address" class="form-control" readonly><?php echo e($data->owner_address); ?></textarea>
                                            <?php if($errors->has('agent_address')): ?>
                                                <span class="help-block"><?php echo e($errors->first('agent_address')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>registration file</label>
                                            <div class="row">
                                                <div class="<?php echo e(isset($data->id) ? 'col-md-9' : 'col-md-12'); ?>">
                                                    <input type="file" name="reg_file" value="<?php echo e(old('reg_file', $data->reg_file)); ?>" placeholder="Registration File" class="form-control"   />
                                                </div>
                                                <?php if(isset($data->id)): ?>
                                                    <div class="col-md-3">
                                                        <?php if(isset($data->vessel_file->title) && $data->vessel_file->title == "LOCAL_VESSEL"): ?>
                                                            <?php if($data->vessel_file->files != ""): ?>
                                                                <a target="_blank" class="form-control" style="text-align: center;" href="<?php echo e(route('local-vessel-download-media',$data->vessel_file->id)); ?>" ><i class="bx bx-download"></i></a>
                                                                <input type="hidden" name="pre_reg_file_id" value="<?php echo e(isset($data->vessel_file->id) ? $data->vessel_file->id : ''); ?>">
                                                            <?php else: ?>
                                                                <span class="form-control" style="text-align: center;"  >No file found</span>
                                                                <input type="hidden" name="pre_reg_file_id" value="<?php echo e(isset($data->vessel_file->id) ? $data->vessel_file->id : ''); ?>">
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($errors->has('reg_file')): ?>
                                                <span class="help-block"><?php echo e($errors->first('reg_file')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>registration no</label>
                                            <input type="text" name="reg_no" value="<?php echo e(old('total_crew_officer', $data->reg_no)); ?>" placeholder="Registration No" class="form-control" oninput="this.value = this.value.toUpperCase()"/>
                                            <?php if($errors->has('reg_no')): ?>
                                                <span class="help-block"><?php echo e($errors->first('reg_no')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Registration exp date</label>
                                                <div class="input-group date" id="expDate" data-target-input="nearest">
                                                    <div class="input-group-append" data-target="#expDate" data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="bx bx-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                           name="reg_exp_date"
                                                           value="<?php echo e(old('reg_exp_date', $data->reg_exp_date)); ?>"
                                                           class="form-control datetimepicker-input "
                                                           data-target="#expDate"
                                                           data-toggle="datetimepicker"
                                                           placeholder="Reg exp date">
                                                </div>
                                            </div>
                                            <?php if($errors->has('reg_exp_date')): ?>
                                                <span class="help-block"><?php echo e($errors->first('reg_exp_date')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Status<span class="required"></span></label>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="<?php echo e(old('status','A')); ?>" <?php echo e(isset($data->status) && $data->status == 'A' ? 'checked' : ''); ?> name="status" id="customRadio1" checked="">
                                                            <label class="custom-control-label" for="customRadio1">Active</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" value="<?php echo e(old('status','I')); ?>" <?php echo e(isset($data->status) && $data->status == 'I' ? 'checked' : ''); ?> name="status" id="customRadio2">
                                                            <label class="custom-control-label" for="customRadio2">Inactive</label>
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
                            <div class="row">
                                

                                <div class="col-md-12 float-right">
                                    <div class="row my-1 ">
                                        <div class="col-md-12">
                                            <label></label>
                                            <div class="d-flex justify-content-end col">
                                                <button type="submit" name="save" onclick="return confirm('Are you sure?')" class="btn btn btn-dark shadow mr-1 mb-1">  <?php echo e(isset($data->id)?'Update':'Save'); ?> </button>
                                                <a type="reset" href="<?php echo e(route("local-vessel")); ?>" class="btn btn btn-outline-dark  mb-1"> Cancel</a>
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
                    <h4 class="card-title"> Local Vessel List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Call Sign</th>
                                    <th>Owner Name</th>
                                    <th>Owner Address</th>
                                    <th>Reg No</th>
                                    <th>Reg Exp Date</th>
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
        $('.agent_id').on('change', function () {
            let url = '<?php echo e(route('get-agent-info')); ?>';
            let agent_id = $(this).find(":selected").val();
            $.ajax({
                type: 'GET',
                url: url,
                data: {agent_id: agent_id},
                success: function (msg) {console.log(msg)
                    $('#agent_address').val(msg);
                }
            });
        });
        $(document).ready(function () {
            datePicker("#expDate");

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
                    url:'<?php echo e(route('local-vessel-datatable', isset($data->id)?$data->id:0 )); ?>',
                    type:'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex' },
                    {"data": "name", orderable: true, searchable: true},
                    {"data": "call_sign", orderable: true, searchable: true},
                    {"data": "owner_name", orderable: true, searchable: true},
                    {"data": "owner_address", orderable: true, searchable: true},
                    {"data": "reg_no", orderable: true, searchable: true},
                    {"data": "reg_exp_date", orderable: true, searchable: true},
                    {"data": "status", orderable: true, searchable: true},
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




<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mda/local_vessel.blade.php ENDPATH**/ ?>
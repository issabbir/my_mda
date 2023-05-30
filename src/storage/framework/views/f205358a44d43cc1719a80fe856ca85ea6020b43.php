<?php $__env->startSection('title'); ?>
    Slip Generation
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
                        <h4 class="card-title"> <?php echo e(isset($data->id)?'Edit':'Add'); ?> Slip Generation</h4>
                        <form method="POST" action="">
                            <?php echo e(isset($data->id)?method_field('PUT'):''); ?>

                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Book No</label>
                                            <input type="text"
                                                   name="book_no" autocomplete="off"
                                                   value="<?php echo e(old("book_no", $data->book_no)); ?>"
                                                   placeholder="Book No"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            <?php if($errors->has("book_no")): ?>
                                                <span class="help-block"><?php echo e($errors->first("book_no")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Slip No</label>
                                            <input type="text" autocomplete="off"
                                                   name="form_no"
                                                   value="<?php echo e(old("form_no", $data->form_no)); ?>"
                                                   placeholder="Slip No"
                                                   class="form-control"
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            <?php if($errors->has("form_no")): ?>
                                                <span class="help-block"><?php echo e($errors->first("form_no")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Collection Date<span class="required"></span></label>
                                            <input type="date"
                                                   name="collection_date" id="collection_date"
                                                   class="form-control bg-white" required
                                                   
                                                   value="<?php echo e(old('collection_date',  ($data->collection_date)?(date('Y-m-d', strtotime($data->collection_date))): '')); ?>"
                                                   autocomplete="off">
                                            <?php if($errors->has("collection_date")): ?>
                                                <span class="help-block"><?php echo e($errors->first("collection_date")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Office<span class="required"></span></label>
                                            <select name="office_id" class="select2">
                                                <option value="">Select one</option>
                                                <?php if(isset($offices) && $offices!=''): ?>
                                                    <?php $__empty_1 = true; $__currentLoopData = $offices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $office): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <option
                                                            <?php echo e(($data->office_id == $office->office_id) ? "selected" : ""); ?> value="<?php echo e($office->office_id); ?>"><?php echo e($office->office_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <option value="">Office</option>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('office_id')): ?>
                                                <span class="help-block"><?php echo e($errors->first('office_id')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Slip Type<span
                                                    class="required"></span></label>
                                            <select name="slip_type_id" class="select2">
                                                <option value="">Select one</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $slip_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slip_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option
                                                        <?php echo e(($data->slip_type_id == $slip_type->id) ? "selected" : ""); ?> value="<?php echo e($slip_type->id); ?>"><?php echo e($slip_type->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">Slip Type empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('slip_type_id')): ?>
                                                <span class="help-block"><?php echo e($errors->first('slip_type_id')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Period From<span
                                                    class="required"></span></label>
                                            <input type="date"
                                                   name="period_from" id="period_from"
                                                   class="form-control bg-white" required
                                                   value="<?php echo e(old('period_from', ($data->period_from)?(date('Y-m-d', strtotime($data->period_from))):'')); ?>"
                                                   autocomplete="off">
                                            <?php if($errors->has('period_from')): ?>
                                                <span class="help-block"><?php echo e($errors->first('period_from')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Period To<span
                                                    class="required"></span></label>
                                            <input type="date"
                                                   name="period_to" id="period_to"
                                                   class="form-control bg-white" required
                                                   value="<?php echo e(old('period_to', ($data->period_to)?(date('Y-m-d', strtotime($data->period_to))):'')); ?>"
                                                   autocomplete="off">
                                            <?php if($errors->has('period_to')): ?>
                                                <span class="help-block"><?php echo e($errors->first('period_to')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Local Vessel<span
                                                    class="required"></span></label>
                                            <select name="local_vessel_id" class="select2 local_vessel_id">
                                                <option value="">Select one</option>
                                                <?php $__empty_1 = true; $__currentLoopData = $localVesselNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $localVesselName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option
                                                        <?php echo e(($data->local_vessel_id == $localVesselName->id) ? "selected" : ""); ?> value="<?php echo e($localVesselName->id); ?>"><?php echo e($localVesselName->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <option value="">Local Vessel Name empty</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if($errors->has('local_vessel_id')): ?>
                                                <span class="help-block"><?php echo e($errors->first('local_vessel_id')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">GRT<span class="required"></span></label>
                                            <input type="text" readonly
                                                   name="grt"
                                                   value="<?php echo e(old("grt", $data->grt)); ?>"
                                                   placeholder="GRT"
                                                   class="form-control grt"
                                                   oninput="this.value=this.value.toUpperCase()"/>
                                            <?php if($errors->has("form_no")): ?>
                                                <span class="help-block"><?php echo e($errors->first("form_no")); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Select Dues<span
                                                    class="required"></span></label>
                                            <select name="dues_select" class="select2 dues_select">
                                                <option value="1" <?php if($data->dues_select == 1): ?> selected <?php endif; ?>>PORT
                                                    DUES
                                                </option>
                                                <option value="2" <?php if($data->dues_select == 2): ?> selected <?php endif; ?>>RIVER
                                                    DUES
                                                </option>
                                                <option value="3" <?php if($data->dues_select == 3): ?> selected <?php endif; ?>>LICENSE
                                                    BILL
                                                </option>
                                            </select>

                                            <?php if($errors->has('port_dues_amount')): ?>
                                                <span class="help-block"><?php echo e($errors->first('port_dues_amount')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Amount<span
                                                    class="required"></span></label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" readonly
                                                       id="dues_amount"
                                                       name="dues_amount"
                                                       value="<?php if($data->dues_select == 1): ?> <?php echo e($data->port_dues_amount); ?>

                                                       <?php elseif($data->dues_select == 2): ?> <?php echo e($data->river_dues_amount); ?>

                                                       <?php elseif($data->dues_select == 3): ?> <?php echo e($data->license_bill_amount); ?> <?php endif; ?>"
                                                       placeholder="Amount" class="form-control bg-white"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($errors->has('other_dues_amount')): ?>
                                                <span
                                                    class="help-block"><?php echo e($errors->first('other_dues_amount')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Others Dues Title</label>
                                            <input type="text"
                                                   name="other_dues_title"
                                                   value="<?php echo e(old('other_dues_title', empty($data->other_dues_title)?'FINE':$data->other_dues_title)); ?>"
                                                   placeholder="Other Dues Title" class="form-control other_dues_title"
                                                   oninput="this.value = this.value.toUpperCase()"/>

                                            <?php if($errors->has('other_dues_title')): ?>
                                                <span class="help-block"><?php echo e($errors->first('other_dues_title')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label>Other Dues Amount</label>
                                            <div class="input-group" data-target-input="nearest">
                                                <input type="text" autocomplete="off"
                                                       id="other_dues_amount"
                                                       name="other_dues_amount"
                                                       value="<?php echo e(old('other_dues_amount', $data->other_dues_amount)); ?>"
                                                       placeholder="Other Dues Amount" class="form-control"
                                                       min="0"
                                                       oninput="vatCalculation()"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($errors->has('other_dues_amount')): ?>
                                                <span
                                                    class="help-block"><?php echo e($errors->first('other_dues_amount')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">VAT (<span class="text-danger"><?php echo e(\App\Helpers\HelperClass::getCashCollectionVatPercentage()); ?>%</span>)<span
                                                    class="required"></span></label>
                                            <div class="input-group" id="leftAt" data-target-input="nearest">

                                                <input type="text"
                                                       id="vat_amount"
                                                       name="vat_amount"
                                                       value="<?php echo e(old('vat_amount', ($data->vat_amount)? $data->vat_amount:0.0)); ?>"
                                                       required readonly class="form-control bg-white"
                                                       aria-invalid="false">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-1">
                                        <div class="col-md-12">
                                            <label class="input-required">Total<span class="required"></span></label>
                                            <div class="input-group" id="leftAt" data-target-input="nearest">
                                                <?php
                                                    $total = $data->port_dues_amount + $data->river_dues_amount + $data->other_dues_amount + $data->vat_amount;

                                                ?>
                                                <input type="text" name="total_amount" id="total_amount"
                                                       readonly required
                                                       value="<?php echo e(old('total_amount', $total)); ?>"
                                                       class="form-control bg-white"
                                                       aria-invalid="false">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        Taka
                                                    </div>
                                                </div>
                                            </div>
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
                                                            <input type="radio" class="custom-control-input"
                                                                   value="<?php echo e(old('status','P')); ?>"
                                                                   <?php echo e(isset($data->status) && $data->status == 'P' ? 'checked' : ''); ?> name="status"
                                                                   id="customRadio1" checked="">
                                                            <label class="custom-control-label" for="customRadio1">Prepared</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2 mb-1">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                   value="<?php echo e(old('status','A')); ?>"
                                                                   <?php echo e(isset($data->status) && $data->status == 'A' ? 'checked' : ''); ?> name="status"
                                                                   id="customRadio2">
                                                            <label class="custom-control-label" for="customRadio2">Collected</label>
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


                                

                            </div>
                            
                            <div class="row">

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row my-1">
                                        <div class="d-flex justify-content-end col">
                                            <button type="submit" name="save" onclick="return confirm('Are you sure?')"
                                                    class="btn btn btn-dark shadow mr-1 mb-1">  <?php echo e(isset($data->id)?'Update':'Save'); ?> </button>
                                            <a type="reset" href="<?php echo e(route("cc-slip-generation")); ?>"
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
                    <h4 class="card-title"> Slip List</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-sm datatable">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>FORM NO</th>
                                    <th>TYPE</th>
                                    <th>VESSEL</th>
                                    <th>OFFICE</th>
                                    <th>PORT DUES</th>
                                    <th>CARGO DUES</th>
                                    <th>OTHER DUES TITLE</th>
                                    <th>OTHER DUES AMOUNT</th>
                                    <th>VAT AMOUNT</th>
                                    <th>TOTAL AMOUNT</th>
                                    <th>PERIOD FORM</th>
                                    <th>PERIOD TO</th>
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
        $(".local_vessel_id").on('change', function (e) {
            let vessel_id = $(this).val();
            let vdata = '<?php echo e(route('vessel-data')); ?>';
            $.ajax({
                type: 'get',
                url: vdata,
                data: {vessel_id: vessel_id},
                success: function (msg) {
                    $(".grt").val(msg);
                    portDueCalculation(msg);
                    vatCalculation();
                }
            });
        });

        function portDueCalculation(msg) {
            if (msg <= 9) {
                $("#dues_amount").val(0);
            } else if (msg <= 10 || msg <= 100) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(200);
                } else {
                    $("#dues_amount").val(300);
                }
            } else if (msg <= 101 || msg <= 200) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(500);
                } else {
                    $("#dues_amount").val(750);
                }
            } else if (msg >= 201) {
                let str = $('#collection_date').val(); // this would be your date
                let res = str.split("-"); // turn the date into a list format (Split by / if needed)
                let months = ["Jan", "Feb", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"];
                if (months[res[1] - 1] == 'Jan' || months[res[1] - 1] == 'July') {
                    $("#dues_amount").val(msg * 5);
                } else {
                    $("#dues_amount").val(msg * 7.5);
                }
            }
        }

        $(".dues_select").on('change', function (e) {
            let dues_select = $(this).val();
            if (dues_select == 2) {
                $("#dues_amount").val(Number($(".grt").val()) * 10);
                vatCalculation();
            } else if (dues_select == 3) {
                $("#dues_amount").val(Number($(".grt").val()) + 1);
                vatCalculation();
            } else if (dues_select == 1) {
                portDueCalculation(Number($(".grt").val()));
                vatCalculation();
            }
        });

        $('#collection_date').change(function () {
            let getdate = $(this).val();
            const date = new Date(getdate);

            const start = new Date('2023-01-01');
            const end = new Date('2023-06-30');

            if (date > start && date < end) {
                $('#period_from').val('2023-01-01');
                $('#period_to').val('2023-06-30');
            } else {
                $('#period_from').val('2023-07-01');
                $('#period_to').val('2023-12-31');
            }
        });

        $(document).ready(function () {
            $('.datatable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 20,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
                initComplete: function (settings, json) {
                    $('body').find('.dataTables_scrollBody').css("height", "auto");
                    $('body').find('.dataTables_scrollBody').css("max-height", "300px");
                },
                ajax: {
                    url: '<?php echo e(route('cc-slip-generation-datatable', isset($data->id)?$data->id:0 )); ?>',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    }
                },
                "columns": [
                    {"data": 'DT_RowIndex', "name": 'DT_RowIndex'},
                    {"data": "form_no"},
                    {"data": "slip_type"},
                    {"data": "local_vessel_name"},
                    {"data": "office_name"},
                    {"data": "port_dues_amount"},
                    {"data": "river_dues_amount"},
                    {"data": "other_dues_title"},
                    {"data": "other_dues_amount"},
                    {"data": "vat_amount"},
                    {"data": "total"},
                    {"data": "period_form"},
                    {"data": "period_to"},
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
        });

        function vatCalculation() {
            var vatPercentage = Number('<?php echo e(\App\Helpers\HelperClass::getCashCollectionVatPercentage()); ?>');
            //var portDues = $('#port_dues_amount').val();
            var allDues = $('#dues_amount').val();
            //var reviewDues = $('#river_dues_amount').val();
            //var licenseBill = $('#license_bill_amount').val();
            var otherDues = $('#other_dues_amount').val();
            //var excludeVatTotalAmt = Number(portDues) + Number(reviewDues) + Number(otherDues) + Number(licenseBill);
            var excludeVatTotalAmt = Number(otherDues) + Number(allDues);
            var vat_amount = parseFloat((vatPercentage / 100) * excludeVatTotalAmt).toFixed(2);
            $('#vat_amount').val(vat_amount);
            $('#total_amount').val(parseFloat(Number(excludeVatTotalAmt) + Number(vat_amount)).toFixed(2));
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mda/cc_slip_generation.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title'); ?>
    Report Generator
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-style'); ?>
    <!--Load custom style link or css-->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body"><h4 class="card-title">Report Generator</h4>
                    <hr>
                    <form id="report-generator" method="POST" action="" target="_blank">
                        <?php echo e(csrf_field()); ?>

                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="row mt-1">
                                    <div class="col-md-3">
                                        <label class="required">Report</label>
                                        <select name="report" id="report" required class="form-control">
                                            <option value="">Select Report</option>
                                            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($report->report_id); ?>" data-report-name="<?php echo e($report->report_name); ?>"><?php echo e($report->report_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-1" id="report-params"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="report-params" class="col-12"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer-script'); ?>
<script type="text/javascript">
    $('#report').on('change', function(e) {
        e.preventDefault();
        let reportId = $(this).val();
        let reportName = $(this).find('option:selected').attr('data-report-name');

        if(
            (reportId !== undefined) && (reportId !== null) && (reportName !== undefined) && (reportName !== '')
        ) {
            $.ajax({
                type: "GET",
                url: APP_URL+'/reports/report-generator-params/'+reportId,
                success: function (data) {
                    $('#report-generator').attr('action', APP_URL+'/report/render/'+reportName);
                    $('#report-params').html(data);
                },
                error: function (err) {
                    alert('error', err);
                }
            });
        } else {
            $('#report-generator').attr('action', '');
            $('#report-params').html('');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/mda/reportgenerator/index.blade.php ENDPATH**/ ?>
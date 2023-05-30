<?php $__env->startSection('content'); ?>
	<section class="row flexbox-container">
		<div class="col-xl-7 col-10">
			<div class="row m-0">
				<!-- left section-login -->
				<div class="col-md-6 col-12 px-0 bg-rgba-cblack">
					<form class="" action="<?php echo e(route('authorization.login')); ?>" method="post">
						<div class="card-header pb-0">
							<div class="card-title">
								<img src="<?php echo e(asset('/assets/images/logo/cpa-logo.png')); ?>" alt="users view avatar" class="img-fluid mx-auto d-block">
								<h4 class="text-center mt-1 text-white">CPA Portal</h4>
								
								<h4 class="text-center mt-1 text-white">Marine Engineering Automation</h4>
							</div>
						</div>
						<div class="card-content">
							<div class="card-body">
								<?php if($errors->has('error')): ?>
									<div class="alert alert-dismissible alert-danger">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong></strong> <?php echo e($errors->first('error')); ?>

									</div>
								<?php endif; ?>
								<?php if(session()->has('message')): ?>
									<div class="alert alert-dismissible alert-success">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										<strong></strong> <?php echo e(session()->get('message')); ?>

									</div>
								<?php endif; ?>
								<div class="form-group">
									<label class="text-bold-600 text-white" for="exampleInputPassword1">Username *</label>
									<input type="text" class="form-control" name="p_user_name" id="exampleInputPassword1" placeholder="Username" required>
								</div>
								<div class="form-group">
									<label class="text-bold-600 text-white" for="exampleInputPassword2">Password *</label>
									<input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" name="p_user_pass" required>
								</div>
								<div class="form-group">
									<div class="checkbox checkbox-sm">
										<input type="checkbox" name="rememberMe" class="form-check-input" id="exampleCheck1">
										<label class="checkboxsmall text-white" for="exampleCheck1"><small>Keep me logged in</small></label>
									</div>
								</div>
							</div>
						</div>

						<div class="card-content">
							<div class="card-body">
								<button type="submit" class="btn btn-primary glow position-relative w-100">
									LOGIN<!--i id="icon-arrow" class="bx bx-right-arrow-alt"></i-->
								</button>
								<hr>
								
							</div>
							<div class="float-right text-light">
								<small>Operation and Maintenance by</small>
								<a class="text-primary font-weight-bold " href="https://site.cnsbd.com" target="_blank">
									<img src="<?php echo e(asset('/assets/images/logo/cns-logo-w.png')); ?>" alt="cns_logo" class="img-fluid mb-1"/>
								</a>
							</div>
						</div>
						<?php echo csrf_field(); ?>

					</form>
				</div>
			</div>
		</div>
	</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/user/login.blade.php ENDPATH**/ ?>
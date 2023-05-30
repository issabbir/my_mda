<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
	<div class="navbar-header">
		<ul class="nav navbar-nav flex-row">
			<li class="nav-item mr-auto">
                <a class="navbar-brand mt-0" href="<?php echo e(route('dashboard')); ?>">
					<img src="<?php echo e(asset('assets/images/logo/cpa-logo.png')); ?>" alt="users view avatar" class="img-fluid"/>
				</a>
			</li>
			<li class="nav-item nav-toggle">
				<a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
					<i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i>
					<i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i>
				</a>
			</li>
		</ul>
	</div>
	<div class="shadow-bottom"></div>
	<div class="main-menu-content mt-1">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class="nav-item sidebar-group-active">
                <a href="<?php echo e(env('DASHBOARD_URL')); ?>"><i class="bx bx-home" data-icon="users"></i><span class="menu-item" data-i18n="Invoice List">Dashboard</span></a>
            </li>
			<?php
				$mdaActiveMenus = \App\Helpers\MdaClass::activeMenus(\Illuminate\Support\Facades\Route::currentRouteName()) ;
				$hasActiveChildImsMenu = \App\Helpers\MdaClass::hasChildMenu(\Illuminate\Support\Facades\Route::currentRouteName());
			?>
			<?php $__currentLoopData = \App\Helpers\MdaClass::menuSetup(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php if($menu->module->enabled == 'Y'): ?>

					<li class="nav-item  mt-1 <?php echo e(trim(Route::currentRouteName()) === trim($menu->menu_name) ? 'sidebar-group-active open ok' : 'none '); ?>"><a href=""><i class="bx bx-notepad" data-icon="users"></i><span class="menu-item" data-i18n="Invoice List"><?php echo e($menu->menu_name); ?></span></a>
						<ul class="menu-content">
							<?php $__currentLoopData = $menu->sub_menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($submenu->active_yn=='Y'): ?>
								<?php if(Auth::user()->hasGrantAll() || in_array($submenu->submenu_id,$menu->role_submenus)): ?>
									<li class="<?php echo e(($hasActiveChildImsMenu )?'open has-sub':((in_array($submenu->submenu_id,$mdaActiveMenus) && $submenu->route_name)?'active':'')); ?>">
										<a href="<?php echo e((isset($submenu->action_name) && ($submenu->menu_id == \App\Enums\ModuleInfo::MDA_MODULE_ID))  ? route($submenu->action_name) : $submenu->route_name); ?>" <?php if($submenu->route_name): ?> class="link_item" <?php endif; ?>><i class="bx bx-right-arrow-alt"></i><span class="menu-item" data-i18n="Third Level"><?php echo e($submenu->submenu_name); ?></span></a>
										<?php if(count($submenu->submenus)>0): ?>
											<ul class="menu-content">
												<?php $__currentLoopData = $submenu->submenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $smenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($smenu->active_yn=='Y'): ?>
													<?php if(Auth::user()->hasGrantAll() || in_array($smenu->submenu_id,$menu->role_submenus)): ?>
														<li class="<?php echo e(in_array($smenu->submenu_id,$mdaActiveMenus)?'active':''); ?>">
															<?php if(strpos($smenu->route_name, '.xdo') !== false): ?>
																<a href="<?php echo e((isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::MDA_MODULE_ID))  ? request()->root().route($smenu->action_name) : request()->root().$smenu->route_name); ?>" class="link_item" target="_blank"><i class="bx bx-right-arrow-alt"></i><span class="menu-item"><?php echo e($smenu->submenu_name); ?></span></a>
															<?php else: ?>
																<a href="<?php echo e((isset($smenu->action_name) && ($smenu->menu_id == \App\Enums\ModuleInfo::MDA_MODULE_ID))  ? route($smenu->action_name) : $smenu->route_name); ?>" class="link_item"<?php if(strpos($smenu->route_name, '.xdo') !== false): ?> target="_blank" <?php endif; ?>><i class="bx bx-right-arrow-alt"></i><span class="menu-item"><?php echo e($smenu->submenu_name); ?></span></a>
															<?php endif; ?>
														</li>
													 <?php endif; ?>
                                                    <?php endif; ?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</ul>
										<?php endif; ?>
									</li>
								<?php endif; ?>
                                <?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</ul>
					</li>
				<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
	</div>
</div>
<!-- END: Main Menu-->
<!-- END: Header-->
<?php /**PATH C:\xampp\htdocs\cpa_mda\src\resources\views/layouts/partial/sidebar.blade.php ENDPATH**/ ?>
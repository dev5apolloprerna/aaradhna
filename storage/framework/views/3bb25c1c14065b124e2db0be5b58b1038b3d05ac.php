    <?php 
if(auth()->user())
{
$roleid = auth()->user()->role_id;
}else{

$roleid = Auth::guard('web_employees')->user()->role_id;
}
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>
                 <li class="nav-item">
                    <a class="nav-link menu-link <?php if(request()->routeIs('home')): ?> <?php echo e('active'); ?> <?php endif; ?>"
                        href="<?php echo e(route('home')); ?>">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                <?php if($roleid == '1' && $roleid != '2'): ?>
                   
                    <!-- Category -->
                    <li class="nav-item">
                        <a class="nav-link menu-link <?php if(request()->routeIs('magazine.*')): ?> active <?php endif; ?>"
                            href="<?php echo e(route('magazine.index')); ?>">
                            <i class="fas fa-newspaper"></i>
                            <span data-key="t-category">Magazines</span>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.free_article.index')); ?>" class="nav-link <?php echo e(request()->is('admin/free_article*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-newspaper"></i>
                            Free Article
                        </a>
                    </li>

                      <li class="nav-item">
                        <a href="<?php echo e(route('plan.index')); ?>" class="nav-link <?php echo e(request()->is('admin/plan*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-cube"></i>
                                Plan
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?php echo e(route('customer.index')); ?>" class="nav-link <?php echo e(request()->is('admin/customer*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-users"></i>Customer
                    </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('customer.subscriptions')); ?>" class="nav-link <?php echo e(request()->is('admin/customer-subscriptions*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-credit-card"></i>Customer Subscriptions
                        </a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sidebarMore" data-bs-toggle="collapse" role="button"
                        aria-expanded="true" aria-controls="sidebarMore">
                        <i class="fa fa-list text-white"></i> Reports </a>
                    <div class="menu-dropdown collapse show" id="sidebarMore" style="">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.customers_login-history.index')); ?>"
                                   class="nav-link <?php echo e(request()->routeIs('admin.customers_login-history.index') ? 'active' : ''); ?>">
                                      <i class="far fa-circle nav-icon"></i></i>Customer Login Report
                                </a>
                            </li>
                             <li class="nav-item">
                                <a href="<?php echo e(route('admin.reports.articleWisePdfViews')); ?>" class="nav-link">
                                    <i class="ri-file-text-line"></i>
                                    <span>Article Views Report</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.reports.userWiseArticleViews')); ?>" class="nav-link">
                                    <i class="ri-user-line"></i>
                                    <span>Customer Article Report</span>
                                </a>
                            </li>
                            <!--<li class="nav-item">
                                <a href="<?php echo e(route('admin.reports.userWisePdfViews')); ?>"
                                   class="nav-link <?php echo e(request()->routeIs('admin.reports.userWisePdfViews*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file-pdf"></i>
                                    Customer Magazine View Report
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.reports.magazineWisePdfViews')); ?>"
                                   class="nav-link <?php echo e(request()->routeIs('admin.reports.magazineWisePdfViews*') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>Magazine View Report</p>
                                </a>
                            </li>-->


                        </ul>
                    </div>
                </li>


                 
                <?php endif; ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/common/sidebar.blade.php ENDPATH**/ ?>
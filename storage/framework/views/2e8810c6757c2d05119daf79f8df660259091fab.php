
<?php $__env->startSection('title', 'Customer Subscriptions'); ?>

<?php $__env->startSection('content'); ?>
<?php
    // ✅ Read tab from controller or URL (?tab=renewal)
    $tab = $activeTab ?? request('tab', 'subscribed');
    $allowed = ['subscribed','renewal','unsubscribed'];
    if(!in_array($tab, $allowed)) $tab = 'subscribed';
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?php echo e($tab=='subscribed' ? 'active' : ''); ?>"
                       data-bs-toggle="tab" href="#subscribed" role="tab">
                        Subscribed
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e($tab=='renewal' ? 'active' : ''); ?>"
                       data-bs-toggle="tab" href="#renewal" role="tab">
                        Renewal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e($tab=='unsubscribed' ? 'active' : ''); ?>"
                       data-bs-toggle="tab" href="#unsubscribed" role="tab">
                        Unsubscribed
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade <?php echo e($tab=='subscribed' ? 'show active' : ''); ?>" id="subscribed" role="tabpanel">
                    <?php echo $__env->make('admin.customer.subscription-list', ['customers' => $subscribed, 'type' => 'Subscribed'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="tab-pane fade <?php echo e($tab=='renewal' ? 'show active' : ''); ?>" id="renewal" role="tabpanel">
                    <?php echo $__env->make('admin.customer.subscription-list', ['customers' => $renewal, 'type' => 'Renewal Due'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="tab-pane fade <?php echo e($tab=='unsubscribed' ? 'show active' : ''); ?>" id="unsubscribed" role="tabpanel">
                    <?php echo $__env->make('admin.customer.subscription-list', ['customers' => $unsubscribed, 'type' => 'Unsubscribed'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/customer/subscription-tabs.blade.php ENDPATH**/ ?>
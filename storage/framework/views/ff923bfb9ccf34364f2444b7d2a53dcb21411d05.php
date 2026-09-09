

<?php $__env->startSection('title', isset($customer) ? 'Edit Customer' : 'Add Customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <h3>Login History </h3>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                
                    <b><?php echo e($customer->customer_name); ?></b> (<?php echo e($customer->customer_id); ?>) <br>
                    <?php echo e($customer->customer_mobile); ?> | <?php echo e($customer->customer_email); ?>


                  </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?php echo e(route('admin.customers_login-history.index') . (request()->getQueryString() ? '?' . request()->getQueryString() : '')); ?>"
               class="btn btn-secondary btn-sm mb-3">
                Back
            </a>

        </div>
    </div>
    
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">

        <thead>
            <tr>
                <th>#</th>
                <th>Login Date Time</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($logs->firstItem() + $i); ?></td>
                <td><?php echo e($log->login_date_time->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo e($logs->links()); ?>

</div>
</div>
<div>
</div>
</div>
</div>
</div>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/login_history.blade.php ENDPATH**/ ?>
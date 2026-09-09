

<?php $__env->startSection('title', isset($customer) ? 'Edit Customer' : 'Add Customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="card">
<div class="card-body">
    
            <div class="row mb-3">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h4><?php echo e(isset($customer) ? 'Edit' : 'Add'); ?> Customer</h4>
                    <a href="<?php echo e(route('customer.index')); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <form action="<?php echo e(isset($customer) ? route('customer.update', $customer->customer_id) : route('customer.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php if(isset($customer)): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Customer Name <span style="color:red;">*</span></label>
                        <input type="text" name="customer_name" class="form-control" value="<?php echo e(old('customer_name', $customer->customer_name ?? '')); ?>">
                        <?php if($errors->has('customer_name')): ?>
                            <span class="text-danger"><?php echo e($errors->first('customer_name')); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Mobile Number <span style="color:red;">*</span></label>
                        <input type="text" name="customer_mobile" class="form-control" value="<?php echo e(old('customer_mobile', $customer->customer_mobile ?? '')); ?>">
                        <?php if($errors->has('customer_mobile')): ?>
                            <span class="text-danger"><?php echo e($errors->first('customer_mobile')); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Email <span style="color:red;">*</span></label>
                        <input type="email" name="customer_email" class="form-control" value="<?php echo e(old('customer_email', $customer->customer_email ?? '')); ?>">
                        <?php if($errors->has('customer_email')): ?>
                            <span class="text-danger"><?php echo e($errors->first('customer_email')); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if(!isset($customer)): ?>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Password <span style="color:red;">*</span></label>
                        <input type="password" name="password" class="form-control">
                        <?php if($errors->has('password')): ?>
                            <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label><br>
                        <input type="checkbox" name="iStatus" value="1" <?php echo e(old('iStatus', $customer->iStatus ?? 1) ? 'checked' : ''); ?>> Active
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success"><?php echo e(isset($customer) ? 'Update' : 'Save'); ?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/customer/form.blade.php ENDPATH**/ ?>
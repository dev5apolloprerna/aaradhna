
<?php $__env->startSection('title', isset($magazine) ? 'Edit Magazine' : 'Add Magazine'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="card">
                <div class="card-body">
            <div class="row">
                
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0"><?php echo e(isset($magazine) ? 'Edit' : 'Add'); ?> Magazine</h4>
                        <div class="page-title-right">
                            <a href="<?php echo e(route('magazine.index')); ?>" class="btn btn-sm btn-primary shadow-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="<?php echo e(isset($magazine) ? route('magazine.update', $magazine->id) : route('magazine.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php if(isset($magazine)): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Title <span style="color:red;">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?php echo e(old('title', $magazine->title ?? '')); ?>">
                        <?php if($errors->has('title')): ?>
                            <span class="text-danger"><?php echo e($errors->first('title')); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Image <span style="color:red;">*</span></label>
                        <input type="file" name="image" class="form-control">
                        <?php if(isset($magazine) && $magazine->image): ?>
                            <img src="<?php echo e(asset($magazine->image)); ?>" width="80" class="mt-2">
                        <?php endif; ?>
                        <?php if($errors->has('image')): ?>
                            <span class="text-danger"><?php echo e($errors->first('image')); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">PDF <span style="color:red;">*</span></label>
                        <input type="file" name="pdf" class="form-control">
                        <?php if(isset($magazine) && $magazine->pdf): ?>
                            <a href="<?php echo e(asset($magazine->pdf)); ?>" target="_blank">Current PDF</a>
                        <?php endif; ?>
                        <?php if($errors->has('pdf')): ?>
                            <span class="text-danger"><?php echo e($errors->first('pdf')); ?></span>
                        <?php endif; ?>
                    </div>
                    <!--<div class="col-md-6 mb-4">
                        <label class="form-label">Month <span style="color:red;">*</span></label>
                        <input type="text" name="month" class="form-control" value="<?php echo e(old('month', $magazine->month ?? '')); ?>">
                        <?php if($errors->has('month')): ?>
                            <span class="text-danger"><?php echo e($errors->first('month')); ?></span>
                        <?php endif; ?>
                    </div>-->
                    <!--<div class="col-md-6 mb-4">
                        <label class="form-label">
                            Month <span style="color:red;">*</span>
                        </label>
                    
                        <select name="month" class="form-control">
                            <option value="">-- Select Month --</option>
                    
                            <?php
                                $months = [
                                    1 => 'January',
                                    2 => 'February',
                                    3 => 'March',
                                    4 => 'April',
                                    5 => 'May',
                                    6 => 'June',
                                    7 => 'July',
                                    8 => 'August',
                                    9 => 'September',
                                    10 => 'October',
                                    11 => 'November',
                                    12 => 'December',
                                ];
                    
                                $selectedMonth = old('month', $magazine->month ?? '');
                            ?>
                    
                            <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e((int)$selectedMonth === $key ? 'selected' : ''); ?>>
                                    <?php echo e($month); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    
                        <?php if($errors->has('month')): ?>
                            <span class="text-danger"><?php echo e($errors->first('month')); ?></span>
                        <?php endif; ?>
                    </div>


                    <div class="col-md-6 mb-4">
                        <label class="form-label">Year <span style="color:red;">*</span></label>
                        <input type="number" name="year" class="form-control" value="<?php echo e(old('year', $magazine->year ?? '')); ?>">
                        <?php if($errors->has('year')): ?>
                            <span class="text-danger"><?php echo e($errors->first('year')); ?></span>
                        <?php endif; ?>
                    </div>-->
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Publish Date <span style="color:red;">*</span></label>
                        <input type="date" name="publish_date" class="form-control" value="<?php echo e(old('year', $magazine->publish_date ?? '')); ?>">
                        <?php if($errors->has('publish_date')): ?>
                            <span class="text-danger"><?php echo e($errors->first('publish_date')); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Status</label><br>
                        <input type="checkbox" name="iStatus" value="1" <?php echo e(old('iStatus', $magazine->iStatus ?? 1) ? 'checked' : ''); ?>> Active
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success"><?php echo e(isset($magazine) ? 'Update' : 'Save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/magazine/form.blade.php ENDPATH**/ ?>
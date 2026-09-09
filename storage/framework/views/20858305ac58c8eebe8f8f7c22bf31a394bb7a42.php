<div class="card">
    <div class="card-body table-responsive">
        <h5><?php echo e($type); ?> Customers</h5>
        <?php if($type !== 'Unsubscribed'): ?>
            <div class="mb-2 " style="float: right;">
                <strong>Total Amount:</strong>
                <?php echo e($customers->sum('amount')); ?>

            </div>
        <?php endif; ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    
                    <?php if($type !== 'Unsubscribed'): ?>
                        <th>Plan Name</th>
                        <th>Days</th>
                        <th>Amount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($customer->customer_name); ?></td>
                        <td><?php echo e($customer->customer_mobile); ?></td>
                        <td><?php echo e($customer->customer_email); ?></td>
                        
                        <?php if($type !== 'Unsubscribed'): ?>
                            <td><?php echo e($customer->plan_name ?? '-'); ?></td>
                            <td><?php echo e($customer->days ?? '-'); ?></td>
                            <td><?php echo e($customer->amount ?? '-'); ?></td>
                            <td><?php echo e(date('d-m-Y',strtotime($customer->start_date))); ?></td>
                            <td><?php echo e(date('d-m-Y',strtotime($customer->end_date))); ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">No <?php echo e(strtolower($type)); ?> customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/customer/subscription-list.blade.php ENDPATH**/ ?>
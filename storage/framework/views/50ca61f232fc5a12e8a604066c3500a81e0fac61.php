

<?php $__env->startSection('title', 'Customer List'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="<?php echo e(route('customer.index')); ?>">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search Customer" value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="<?php echo e(route('customer.create')); ?>" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Add Customer
                    </a>
                </div>
            </div>

            <form id="bulkDeleteForm" method="POST">
                <?php echo csrf_field(); ?>
                <button type="button" class="btn btn-danger btn-sm mb-2" id="deleteAllSelected">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Allowed Article</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?php echo e($customer->customer_id); ?>"></td>
                                    <td><?php echo e($customer->customer_name); ?></td>
                                    <td><?php echo e($customer->customer_mobile); ?></td>
                                    <td><?php echo e($customer->customer_email); ?></td>
                                    <td><?php echo e($customer->free_article); ?></td>
                                    
                                    <!--<td>
                                        <input type="checkbox" class="toggle-status" data-id="<?php echo e($customer->customer_id); ?>" <?php echo e($customer->iStatus ? 'checked' : ''); ?>>
                                    </td>-->
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox"
                                                   class="form-check-input toggle-status"
                                                   data-id="<?php echo e($customer->customer_id); ?>"
                                                   <?php echo e($customer->iStatus ? 'checked' : ''); ?>>
                                        </div>
                                    </td>
                                    <td><?php echo e(\Carbon\Carbon::parse($customer->created_at)->format('d M Y')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('customer.edit', $customer->customer_id)); ?>" class="text-primary"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="deleteRecord('<?php echo e($customer->customer_id); ?>')" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            <?php echo $customers->links(); ?>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $('#checkAll').click(function () {
        $('input[name="ids[]"]').prop('checked', this.checked);
    });

    $('#deleteAllSelected').click(function () {
        if(confirm("Are you sure to delete selected records?")) {
            $('#bulkDeleteForm').attr('action', '<?php echo e(route("customer.bulk-delete")); ?>').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure you want to delete this record?")) {
            $.post("<?php echo e(url('admin/customer')); ?>/" + id, {
                _method: 'DELETE',
                _token: '<?php echo e(csrf_token()); ?>'
            }, function(data) {
                location.reload();
            });
        }
    }
</script>
<script>
$(document).on('change', '.toggle-status', function () {
    let checkbox = $(this);
    let id = checkbox.data('id');
    let status = checkbox.is(':checked') ? 1 : 0;

    $.ajax({
        url: "<?php echo e(route('customer.toggle-status')); ?>",
        type: "POST",
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            id: id,
            status: status
        },
        success: function (response) {
            if (!response.success) {
                alert('Failed to update status');
                checkbox.prop('checked', !status);
            }
            window.location.href="";
            
        },
        error: function () {
            alert('Something went wrong');
            checkbox.prop('checked', !status);
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/customer/index.blade.php ENDPATH**/ ?>
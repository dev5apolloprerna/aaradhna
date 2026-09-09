

<?php $__env->startSection('title', 'Plan Master'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Add Plan</h5></div>
                        <div class="card-body">
                            <form action="<?php echo e(route('plan.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Plan Name <span style="color:red;">*</span></label>
                                    <input type="text" name="plan_name" class="form-control" value="<?php echo e(old('plan_name')); ?>">
                                    <?php if($errors->has('plan_name')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('plan_name')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount <span style="color:red;">*</span></label>
                                    <input type="text" name="plan_amount" class="form-control" value="<?php echo e(old('plan_amount')); ?>">
                                    <?php if($errors->has('plan_amount')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('plan_amount')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Days <span style="color:red;">*</span></label>
                                    <input type="text" name="days" class="form-control" value="<?php echo e(old('days')); ?>">
                                    <?php if($errors->has('days')): ?>
                                        <span class="text-danger"><?php echo e($errors->first('days')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label><br>
                                    <input type="checkbox" name="iStatus" value="1" checked> Active
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Plan List</h5>
                            <form method="GET" action="<?php echo e(route('plan.index')); ?>" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search Plan" value="<?php echo e(request('search')); ?>">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                        <form id="bulkDeleteForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="card-body table-responsive">
                                <button type="button" class="btn btn-danger btn-sm mb-2" id="deleteAllSelected">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Plan Name</th>
                                            <th>Amount</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="<?php echo e($plan->plan_id); ?>"></td>
                                            <td><?php echo e($plan->plan_name); ?></td>
                                            <td><?php echo e($plan->plan_amount); ?></td>
                                            <td><?php echo e($plan->days); ?></td>
                                            <!--<td><input type="checkbox" class="toggle-status" data-id="<?php echo e($plan->plan_id); ?>" <?php echo e($plan->iStatus ? 'checked' : ''); ?>></td>-->
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox"
                                                       class="form-check-input toggle-status"
                                                       data-id="<?php echo e($plan->plan_id); ?>"
                                                       <?php echo e($plan->iStatus ? 'checked' : ''); ?>>
                                                </div>
                                            </td>
                                            <td><?php echo e(\Carbon\Carbon::parse($plan->created_at)->format('d M Y')); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="editPlan(<?php echo e($plan->plan_id); ?>)" class="text-primary"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);" onclick="deleteRecord(<?php echo e($plan->plan_id); ?>)" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    <?php echo $plans->links(); ?>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" id="editForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Plan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="editModalBody">
                                
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
        if(confirm("Are you sure?")) {
            $('#bulkDeleteForm').attr('action', '<?php echo e(route("plan.bulk-delete")); ?>').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure?")) {
            $.post("<?php echo e(url('admin/plan')); ?>/" + id, {
                _method: 'DELETE',
                _token: '<?php echo e(csrf_token()); ?>'
            }, function() {
                location.reload();
            });
        }
    }

    function editPlan(id) {
        $.get("<?php echo e(url('admin/plan')); ?>/" + id + "/edit", function(data) {
            $('#editModalBody').html(data);
            $('#editForm').attr('action', "<?php echo e(url('admin/plan')); ?>/" + id);
            $('#editModal').modal('show');
        });
    }
</script>
<script>
$(document).on('change', '.toggle-status', function () {
    let checkbox = $(this);
    let id = checkbox.data('id');
    let status = checkbox.is(':checked') ? 1 : 0;

    $.ajax({
        url: "<?php echo e(route('plan.toggle-status')); ?>",
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/plan/index.blade.php ENDPATH**/ ?>


<?php $__env->startSection('title', 'Free Article'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row">
                <!--<div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add Free Article</div>
                        <div class="card-body">
                            <form action="<?php echo e(route('admin.free_article.store')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Free Article <span style="color:red;">*</span></label>
                                    <input type="number" name="free_article" class="form-control" value="<?php echo e(old('free_article')); ?>">
                                    <?php if($errors->has('free_article')): ?>
                                        <span class="text-danger">
                                            <?php echo e($errors->first('free_article')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>-->

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Free Article List</h5>
                            <!--<button class="btn btn-sm btn-danger" id="bulkDeleteBtn">Bulk Delete</button>-->
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Free Article</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $free_articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><input type="checkbox" class="record-checkbox" value="<?php echo e($row->id); ?>"></td>
                                                <td><?php echo e($row->free_article); ?></td>
                                              
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary editBtn" data-id="<?php echo e($row->id); ?>"><i class="fas fa-edit"></i></a>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo e($free_articles->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Free Article</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Free Article <span style="color:red;">*</span></label>
                                    <input type="number" name="free_article" id="edit_free_article" class="form-control">
                                </div>
                               
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function () {
    // Edit button click
    $('.editBtn').on('click', function () {
        let id = $(this).data('id');
        let url = "<?php echo e(url('admin/free_article/edit')); ?>/" + id;

        $.get(url, function (data) {
            $('#edit_free_article').val(data.free_article);
            $('#edit_iStatus').val(data.iStatus);
            $('#editForm').attr('action', "<?php echo e(url('admin/free_article')); ?>/" + id);
            $('#editModal').modal('show');
        });
    });

});

function confirmDelete(url) {
    if (confirm('Are you sure you want to delete this record?')) {
        let form = $('<form>', {
            method: 'POST',
            action: url
        });

        let token = $('<input>', {
            type: 'hidden',
            name: '_token',
            value: '<?php echo e(csrf_token()); ?>'
        });

        let method = $('<input>', {
            type: 'hidden',
            name: '_method',
            value: 'DELETE'
        });

        form.append(token, method).appendTo('body').submit();
    }
}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/free_article/index.blade.php ENDPATH**/ ?>
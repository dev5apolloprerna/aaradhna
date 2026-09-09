
<?php $__env->startSection('title', 'Magazine List'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            
            <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Magazine List</h5>
                            <a href="<?php echo e(route('magazine.create')); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Magazine
                            </a>
                        </div>
                        <div class="card-body">
                            <form id="bulkDeleteForm" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="button" class="btn btn-danger btn-sm mb-3" id="deleteAllSelected">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Title</th>
                                                <th>Image</th>
                                                <th>PDF</th>
                                                <th>Month</th>
                                                <th>Year</th>
                                                <th>Publish Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $magazines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $magazine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" value="<?php echo e($magazine->id); ?>"></td>
                                                <td><?php echo e($magazine->title); ?></td>
                                                <td><img src="<?php echo e(asset($magazine->image)); ?>" alt="" width="60"></td>
                                                <td><a href="<?php echo e(asset($magazine->pdf)); ?>" target="_blank">View PDF</a></td>
                                                <td><?php echo e($magazine->month); ?></td>
                                                <td><?php echo e($magazine->year); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($magazine->publish_date)->format('d M Y')); ?></td>
                                                <!--<td>
                                                    <input type="checkbox" class="toggle-status" data-id="<?php echo e($magazine->id); ?>" <?php echo e($magazine->iStatus ? 'checked' : ''); ?>>
                                                </td>-->
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status"
                                                               type="checkbox"
                                                               data-id="<?php echo e($magazine->id); ?>"
                                                               <?php echo e($magazine->iStatus ? 'checked' : ''); ?>>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(route('magazine.edit', $magazine->id)); ?>" class="text-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0);" onclick="deleteRecord('<?php echo e($magazine->id); ?>')" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                                <a href="<?php echo e(route('admin.magazines.articles.index', $magazine->id)); ?>"
                                                       class="text-success ms-2" title="Add Articles">
                                                       <i class="fa fa-file-text"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center mt-3">
                                <?php echo $magazines->links(); ?>

                            </div>
                        </div>
                    </div>
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
        if(confirm("Are you sure you want to delete selected records?")) {
            $('#bulkDeleteForm').attr('action', '<?php echo e(route("magazine.bulk-delete")); ?>').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure you want to delete this record?")) {
            $.post("<?php echo e(url('admin/magazine')); ?>/" + id, {
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
        let id = $(this).data('id');
        let checkbox = $(this);

        $.ajax({
            url: "<?php echo e(route('magazine.toggle-status')); ?>",
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                id: id
            },
            success: function (res) {
                if (!res.success) {
                    alert('Status update failed');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
                window.location.href="";
            },
            error: function () {
                alert('Something went wrong');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/magazine/index.blade.php ENDPATH**/ ?>
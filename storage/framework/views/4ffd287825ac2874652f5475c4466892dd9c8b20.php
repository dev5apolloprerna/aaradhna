
<?php $__env->startSection('title', 'Magazine Article List'); ?>


<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Articles - <?php echo e($magazine->title); ?></h4>
        <a href="<?php echo e(route('magazine.index')); ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>

               <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <div class="row">

        
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header fw-semibold">Add Article</div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.magazines.articles.store', $magazineId)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">Article Title</label>
                            <input type="text" name="article_title" value="<?php echo e(old('article_title')); ?>" class="form-control" required>
                            <?php $__errorArgs = ['article_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Article Image</label>
                            <input type="file" name="article_image" class="form-control" accept="image/*">
                            <?php $__errorArgs = ['article_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Article PDF</label>
                            <input type="file" name="article_pdf" class="form-control" accept="application/pdf" required>
                            <?php $__errorArgs = ['article_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger small"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Paid?</label>
                                <select name="isPaid" class="form-control" required>
                                    <option value="0" <?php echo e(old('isPaid')==='0' ? 'selected' : ''); ?>>Free</option>
                                    <option value="1" <?php echo e(old('isPaid')==='1' ? 'selected' : ''); ?>>Paid</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="iStatus" class="form-control" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">Save</button>
                    </form>

                </div>
            </div>
        </div>

        
        <div class="col-lg-8 mb-3">
            <div class="card">

                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span>Articles List</span>

                    <span class="text-muted small">Total: <?php echo e($articles->total()); ?></span>

                    <form method="POST" action="<?php echo e(route('admin.magazines.articles.bulkDelete', $magazineId)); ?>" id="bulkDeleteForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="ids_json" id="ids_json" value="">
                        <div class="d-flex gap-2 align-items-center">
                             <button type="button" class="btn btn-danger btn-sm mb-3" onclick="bulkDelete()">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>

                        </div>
                        </form>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width:45px;">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Title</th>
                                <th>Paid</th>
                                <th>Views</th>
                                <th>Image</th>
                                <th>PDF</th>
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                    <input type="checkbox" class="rowCheck" value="<?php echo e($a->article_id); ?>">
                                </td>

                                    <td>
                                        <div class="fw-semibold"><?php echo e($a->article_title); ?></div>
                                        
                                    </td>

                                    <td>
                                        <?php if((int)$a->isPaid === 1): ?>
                                            <span class="badge bg-warning text-dark">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Free</span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo e((int)$a->view_count); ?></td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-start">
                                            <div style="width:52px;">
                                                <?php if($a->article_image): ?>
                                                    <img src="<?php echo e(asset($a->article_image)); ?>"
                                                         style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                                                <?php else: ?>
                                                    <div style="width:52px;height:52px;border-radius:8px;border:1px dashed #ccc;"
                                                         class="d-flex align-items-center justify-content-center text-muted small">
                                                        N/A
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <td>
                                            <div class="flex-grow-1">
                                                <?php if($a->article_pdf): ?>
                                                    <a href="<?php echo e(asset($a->article_pdf)); ?>" target="_blank" class="small">
                                                        View PDF
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input statusToggle"
                                                   type="checkbox"
                                                   data-id="<?php echo e($a->article_id); ?>" data-status="<?php echo e((int)$a->iStatus); ?>"

                                                   <?php echo e((int)$a->iStatus === 1 ? 'checked' : ''); ?>>
                                        </div>
                                    </td>


                                    <!-- <td>
                                        <?php if((int)$a->iStatus === 1): ?>
                                            <span class="badge bg-primary">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td> -->

                                    <td>
                                        
                                            <a href="javascript:void(0)"
                                               class="text-info me-3"
                                               style="font-size:16px;"
                                               title="Edit"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editArticleModal"
                                               data-id="<?php echo e($a->article_id); ?>"
                                               data-title="<?php echo e(e($a->article_title)); ?>"
                                               data-paid="<?php echo e((int)$a->isPaid); ?>"
                                               data-status="<?php echo e((int)$a->iStatus); ?>"
                                               data-image-url="<?php echo e($a->article_image ? asset('storage/'.$a->article_image) : ''); ?>"
                                               data-pdf-url="<?php echo e($a->article_pdf ? asset('storage/'.$a->article_pdf) : ''); ?>"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </a>


                                        <form id="deleteForm<?php echo e($a->article_id); ?>"
                                              action="<?php echo e(route('admin.magazines.articles.destroy', [$magazineId, $a->article_id])); ?>"
                                              method="POST"
                                              class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <a href="javascript:void(0)"
                                               class="text-danger"
                                               style="font-size:16px;"
                                               title="Delete"
                                               onclick="confirmDelete(<?php echo e($a->article_id); ?>)">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                                
                                
                                <div class="modal fade" id="editArticleModal" tabindex="-1" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" id="editArticleForm" action="" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                
                                        <div class="modal-header">
                                          <h5 class="modal-title">Edit Article</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                
                                        <div class="modal-body">
                                
                                            <div class="mb-3">
                                                <label class="form-label">Article Title</label>
                                                <input type="text" name="article_title" id="edit_article_title" class="form-control" required>
                                            </div>
                                
                                            <div class="mb-3">
                                                <label class="form-label">Article Image (optional)</label>
                                                <input type="file" name="article_image" class="form-control" accept="image/*">
                                                <div class="small mt-1">
                                                    <a href="#" target="_blank" id="edit_image_preview_link" style="display:none;">Current Image</a>
                                                </div>
                                            </div>
                                
                                            <div class="mb-3">
                                                <label class="form-label">Article PDF (optional)</label>
                                                <input type="file" name="article_pdf" class="form-control" accept="application/pdf">
                                                <div class="small mt-1">
                                                    <a href="#" target="_blank" id="edit_pdf_preview_link" style="display:none;">Current PDF</a>
                                                </div>
                                            </div>
                                
                                
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Paid?</label>
                                                    <select name="isPaid" id="edit_isPaid" class="form-control" required>
                                                        <option value="0">Free</option>
                                                        <option value="1">Paid</option>
                                                    </select>
                                                </div>
                                
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="iStatus" id="edit_iStatus" class="form-control" required>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                
                                        </div>
                                
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-image-url="<?php echo e($a->article_image ? asset('storage/'.$a->article_image) : ''); ?>"
                                 data-pdf-url="<?php echo e($a->article_pdf ? asset('storage/'.$a->article_pdf) : ''); ?>"
                                >Close</button>
                                          <button class="btn btn-primary">Update</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No articles found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <?php echo e($articles->links()); ?>

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

function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this article?")) {
        document.getElementById("deleteForm" + id).submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editArticleModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title') || '';
        const paid = button.getAttribute('data-paid') || '0';
        const status = button.getAttribute('data-status') || '1';

        const imageUrl = button.getAttribute('data-image-url') || '';
        const pdfUrl = button.getAttribute('data-pdf-url') || '';

        // form action
        const form = document.getElementById('editArticleForm');
        form.action = "<?php echo e(url('admin/magazines/'.$magazineId.'/articles')); ?>/" + id;

        // fill simple fields
        document.getElementById('edit_article_title').value = title;
        document.getElementById('edit_isPaid').value = paid;
        document.getElementById('edit_iStatus').value = status;

        // preview links
        const imgLink = document.getElementById('edit_image_preview_link');
        if (imageUrl) {
            imgLink.href = imageUrl;
            imgLink.style.display = "inline";
        } else {
            imgLink.href = "#";
            imgLink.style.display = "none";
        }

        const pdfLink = document.getElementById('edit_pdf_preview_link');
        if (pdfUrl) {
            pdfLink.href = pdfUrl;
            pdfLink.style.display = "inline";
        } else {
            pdfLink.href = "#";
            pdfLink.style.display = "none";
        }
    });
});

</script>

<script>

const addToggle = document.getElementById('addStatusToggle');
const addHidden = document.getElementById('add_iStatus');
if (addToggle && addHidden) {
    addToggle.addEventListener('change', () => {
        addHidden.value = addToggle.checked ? "1" : "0";
        addToggle.nextElementSibling.textContent = addToggle.checked ? "Active" : "Inactive";
    });
}

/** ✅ Edit modal toggle -> hidden iStatus */
const editToggle = document.getElementById('editStatusToggle');
const editHidden = document.getElementById('edit_iStatus');
const editLabel = document.getElementById('editStatusLabel');
if (editToggle && editHidden && editLabel) {
    editToggle.addEventListener('change', () => {
        editHidden.value = editToggle.checked ? "1" : "0";
        editLabel.textContent = editToggle.checked ? "Active" : "Inactive";
    });
}

/** ✅ select all */
const selectAll = document.getElementById('selectAll');
if (selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.rowCheck').forEach(ch => ch.checked = selectAll.checked);
    });
}

/** ✅ bulk delete */
function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.rowCheck:checked')).map(x => x.value);
    if (!ids.length) {
        alert("Please select at least one article.");
        return;
    }
    if (!confirm("Delete selected articles?")) return;

    // build hidden inputs dynamically for Laravel
    const form = document.getElementById('bulkDeleteForm');
    // remove old hidden inputs
    form.querySelectorAll('input[name="ids[]"]').forEach(i => i.remove());
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    form.submit();
}


$(document).on('change', '.statusToggle', function () {
        let id = $(this).data('id');
        let checkbox = $(this);

        $.ajax({
            url: "<?php echo e(url('admin/magazines/'.$magazineId.'/articles')); ?>/" + id + "/toggle-status",
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/article_master/index.blade.php ENDPATH**/ ?>
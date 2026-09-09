

<?php $__env->startSection('title', 'Article wise view detail'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <h4>Article Views Detail</h4>

      <div class="row mb-3">
        <div class="col-md-6">
          <div class="mb-3">
            <b><?php echo e($article->article_title); ?></b> (ID: <?php echo e($article->article_id); ?>)<br>
            Magazine: <?php echo e($article->magazine_title ?? '-'); ?>

            <?php if(!empty($article->month) && !empty($article->year)): ?>
              (<?php echo e($article->month); ?> - <?php echo e($article->year); ?>)
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-6 text-end">
          <a href="<?php echo e(route('admin.reports.articleWisePdfViews') . (request()->getQueryString() ? '?' . request()->getQueryString() : '')); ?>"
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
                <th>Customer ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>View Date & Time</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td><?php echo e($rows->firstItem() + $i); ?></td>
                  <td><?php echo e($r->customer_id); ?></td>
                  <td><?php echo e($r->customer_name); ?></td>
                  <td><?php echo e($r->customer_mobile); ?></td>
                  <td><?php echo e($r->customer_email); ?></td>
                  <td>
                    <?php if(!empty($r->date_time)): ?>
                      <?php echo e(\Carbon\Carbon::parse($r->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?>

                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center">No views found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>

          <?php echo e($rows->links()); ?>

        </div>
      </div>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/article_pdf_views_detail.blade.php ENDPATH**/ ?>
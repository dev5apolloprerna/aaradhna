

<?php $__env->startSection('title', 'Customer Article View Detail'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <h4>Customer Article Views Detail</h4>

      <div class="row mb-3">
        <div class="col-md-6">
          <div class="mb-3">
            <b><?php echo e($customer->customer_name); ?></b> (<?php echo e($customer->customer_id); ?>) <br>
            <?php echo e($customer->customer_mobile); ?> | <?php echo e($customer->customer_email); ?>

          </div>
        </div>
        <div class="col-md-6 text-end">
          <a href="<?php echo e(route('admin.reports.userWiseArticleViews') . (request()->getQueryString() ? '?' . request()->getQueryString() : '')); ?>"
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
                <th>Article</th>
                <th>Magazine</th>
                <th>Month/Year</th>
                <th>View Date Time</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td><?php echo e($rows->firstItem() + $i); ?></td>
                  <td><?php echo e($r->article_title); ?> (ID: <?php echo e($r->article_id); ?>)</td>
                  <td><?php echo e($r->magazine_title ?? '-'); ?></td>
                  <td>
                    <?php if(!empty($r->month) && !empty($r->year)): ?>
                      <?php echo e($r->month); ?> / <?php echo e($r->year); ?>

                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if(!empty($r->date_time)): ?>
                      <?php echo e(\Carbon\Carbon::parse($r->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?>

                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center">No history found</td></tr>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/user_article_views_detail.blade.php ENDPATH**/ ?>
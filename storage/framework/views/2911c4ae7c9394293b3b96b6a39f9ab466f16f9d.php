

<?php $__env->startSection('title', 'Article wise view list'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h5>Article View Report</h5>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <form method="GET" class="d-flex" action="<?php echo e(route('admin.reports.articleWisePdfViews')); ?>">
                <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" class="form-control"
                       placeholder="Search Article / Magazine / ID">
                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                <a href="<?php echo e(route('admin.reports.articleWisePdfViews')); ?>" class="btn btn-secondary mx-2">Reset</a>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-body table-responsive">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr>
                    <th>Article ID</th>
                    <th>Article Title</th>
                    <th>Magazine</th>
                    <th>Total Views</th>
                    <th>Unique Users</th>
                    <th>Last View Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                      <td><?php echo e($a->article_id); ?></td>
                      <td><?php echo e($a->article_title); ?></td>
                      <td>
                        <?php echo e($a->magazine_title ?? '-'); ?>

                        <?php if(!empty($a->month) && !empty($a->year)): ?>
                          <br><small><?php echo e($a->month); ?> / <?php echo e($a->year); ?></small>
                        <?php endif; ?>
                      </td>
                      <td><?php echo e($a->total_views ?? 0); ?></td>
                      <td><?php echo e($a->unique_users ?? 0); ?></td>
                      <td>
                        <?php if(!empty($a->last_view_time)): ?>
                          <?php echo e(\Carbon\Carbon::parse($a->last_view_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?>

                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                      <td>
                        <a class="btn btn-sm btn-info"
                           href="<?php echo e(route('admin.reports.articlePdfViewsDetail', $a->article_id) . (request()->getQueryString() ? '?' . request()->getQueryString() : '')); ?>">
                          View Detail
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center">No data found</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <?php echo e($articles->links()); ?>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/article_wise_pdf_views.blade.php ENDPATH**/ ?>
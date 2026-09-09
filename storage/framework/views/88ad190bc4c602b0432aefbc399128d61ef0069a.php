

<?php $__env->startSection('title', 'Customer Article Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h5>Customer Article View Report</h5>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <form method="GET" class="d-flex" action="<?php echo e(route('admin.reports.userWiseArticleViews')); ?>">
                <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" class="form-control" placeholder="Search Name / Mobile / Email">
                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                <a href="<?php echo e(route('admin.reports.userWiseArticleViews')); ?>" class="btn btn-secondary mx-2">Reset</a>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-body table-responsive">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Total Article Views</th>
                    <th>Last View Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                      <td><?php echo e($c->customer_id); ?></td>
                      <td><?php echo e($c->customer_name); ?></td>
                      <td><?php echo e($c->customer_mobile); ?></td>
                      <td><?php echo e($c->customer_email); ?></td>
                      <td><?php echo e($c->article_count ?? 0); ?></td>
                      <td>
                        <?php if(!empty($c->last_view_time)): ?>
                          <?php echo e(\Carbon\Carbon::parse($c->last_view_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?>

                        <?php else: ?>
                          -
                        <?php endif; ?>
                      </td>
                      <td>
                        <a class="btn btn-sm btn-info"
                           href="<?php echo e(route('admin.reports.userArticleViewsDetail', $c->customer_id) . '?' . request()->getQueryString()); ?>">
                          View Detail
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center">No data found</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>

              <?php echo e($customers->links()); ?>

            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/user_wise_article_views.blade.php ENDPATH**/ ?>
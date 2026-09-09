    

    <?php $__env->startSection('title', isset($customer) ? 'Edit Customer' : 'Add Customer'); ?>

    <?php $__env->startSection('content'); ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php echo $__env->make('common.alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="card"> 
                  <div class="card-header">
                    <h5> Customers Login Report
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex" action="<?php echo e(route('admin.customers_login-history.index')); ?>">
                                <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" class="form-control"
                                placeholder="Search Name">
                                
                                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                                <a href="<?php echo e(route('admin.customers_login-history.index')); ?>" class="btn btn-secondary mx-2">Reset</a>

                            </form>
                        </div>
                    </div>




                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered align-middle">
                                
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Last Login</th>
                                        <th>Login Count</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($c->customer_id); ?></td>
                                        <td><?php echo e($c->customer_name); ?></td>
                                        <td><?php echo e($c->customer_mobile); ?></td>
                                        <td><?php echo e($c->customer_email); ?></td>
                                        <td>
                                            <?php if($c->lastLogin && $c->lastLogin->login_date_time): ?>
                                            <?php echo e($c->lastLogin->login_date_time->timezone('Asia/Kolkata')->format('d-m-Y h:i A')); ?>

                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e($c->login_count); ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-info"
                                            href="<?php echo e(route('admin.customers.loginHistory', $c->customer_id) . (request()->getQueryString() ? '?' . request()->getQueryString() : '')); ?>">
                                            Login History
                                        </a>
                                    </td>
                   <!-- <td>
                        <button type="button"
                                class="btn btn-sm btn-info btn-login-history"
                                data-id="<?php echo e($c->customer_id); ?>"
                                data-name="<?php echo e($c->customer_name); ?>">
                            Login History
                        </button>

                    </td>
                                    -->            </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                            <?php echo e($customers->links()); ?>

                        </div>
                        <div class="modal fade" id="loginHistoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="loginHistoryTitle">Login History</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                id="close-modal"></button>
                            </div>

                            <div class="modal-body">
                                <div id="loginHistoryLoader" style="display:none;">Loading...</div>

                                <div class="table-responsive">
                                  <table class="table table-bordered mb-0">
                                    <thead>
                                      <tr>
                                        <th>#</th>
                                        <th>Login Date Time</th>
                                    </tr>
                                </thead>
                                <tbody id="loginHistoryBody">
                                  <tr><td colspan="2" class="text-center">No data</td></tr>
                              </tbody>
                          </table>
                      </div>
                  </div>

                  <div class="modal-footer">
                   <button type="button" class="btn btn-primary mx-2" data-bs-dismiss="modal">Cancle</button>
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
    $(document).on('click', '.btn-login-history', function () {
        let customerId = $(this).data('id');
        let customerName = $(this).data('name');

        $('#loginHistoryTitle').text('Login History - ' + customerName );
        $('#loginHistoryBody').html('<tr><td colspan="2" class="text-center">Loading...</td></tr>');
        $('#loginHistoryModal').modal('show');

        let url = "<?php echo e(route('admin.customers.loginHistoryAjax', ':id')); ?>".replace(':id', customerId);

        $.get(url, function (res) {
            if (!res.success) {
                $('#loginHistoryBody').html('<tr><td colspan="2" class="text-center text-danger">Failed to load</td></tr>');
                return;
            }

            if (!res.logs || res.logs.length === 0) {
                $('#loginHistoryBody').html('<tr><td colspan="2" class="text-center">No login history</td></tr>');
                return;
            }

            let rows = '';
            res.logs.forEach(function (item, index) {
                rows += `<tr>
                            <td>${index + 1}</td>
                            <td>${item.login_date_time ?? '-'}</td>
            </tr>`;
        });

            $('#loginHistoryBody').html(rows);
        }).fail(function () {
            $('#loginHistoryBody').html('<tr><td colspan="2" class="text-center text-danger">Server error</td></tr>');
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/admin/report/customer_login.blade.php ENDPATH**/ ?>
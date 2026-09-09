<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
</head>
<body style="font-family:Arial,sans-serif;">
<p>Hello <?php echo e($customer->customer_name ?? 'Customer'); ?>,</p>

<p>Your temporary password is:</p>

<h2 style="letter-spacing:1px;"><?php echo e($tempPassword); ?></h2>

<p>This temporary password is valid for <?php echo e($minutes); ?> minutes.</p>

<p>Please login using this temporary password and immediately set a new password.</p>

<p>Thanks,<br><?php echo e(config('app.name')); ?></p>
</body>
</html>
<?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/emails/customer_reset_password.blade.php ENDPATH**/ ?>
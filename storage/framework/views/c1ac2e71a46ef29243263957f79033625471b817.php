<?php $__env->startSection('title', $article->article_title ?? 'Sadhana Weekly'); ?>
<?php $__env->startSection('og:title', $article->article_title ?? 'Sadhana Weekly'); ?>
<?php $__env->startSection('og:image', asset($article->article_image ?? $magazine->image)); ?>

<?php $__env->startSection('content'); ?>
    <?php if($article): ?>
    <iframe
        src="<?php echo e(asset($article->article_pdf)); ?>"
        class="pdf-viewer"
        title="PDF Viewer">
    </iframe>
    <?php else: ?>
        <div style="text-align:center; padding:40px;">
            <h2>📄 Article not available</h2>
            <p>The requested PDF could not be found.</p>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pdf', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u196010065/domains/sadhanaweekly.co.in/sadhna/resources/views/pdf-viewer.blade.php ENDPATH**/ ?>
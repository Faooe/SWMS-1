<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        <?php echo $__env->yieldContent('title', 'SWMS'); ?>

    </title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">

    
    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css',
        'resources/js/app.js'
    ]); ?>

    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


</head>

<body
    class="bg-slate-100 font-[Inter] antialiased">

    <div
        class="layout">

        
        <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div
            id="page-wrapper"
            class="page-wrapper">

            
            <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            
            <main
                class="page-content">

                <?php echo $__env->yieldContent('content'); ?>

            </main>

        </div>

    </div>

    
    <div
        id="sidebar-overlay"
        class="sidebar-overlay hidden">
    </div>

    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


</body>

</html><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/layouts/app.blade.php ENDPATH**/ ?>
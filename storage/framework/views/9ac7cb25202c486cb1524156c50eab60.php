



<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Company Information

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Informasi utama perusahaan.

        </p>

    </div>

    <div class="grid gap-5 md:grid-cols-2">

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Company Code','name' => 'code','value' => $company->code ?? '','placeholder' => 'Contoh: ABC','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Company Code','name' => 'code','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->code ?? ''),'placeholder' => 'Contoh: ABC','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Company Name','name' => 'name','value' => $company->name ?? '','placeholder' => 'Nama Perusahaan','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Company Name','name' => 'name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->name ?? ''),'placeholder' => 'Nama Perusahaan','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Email','name' => 'email','type' => 'email','value' => $company->email ?? '','placeholder' => 'company@email.com']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','name' => 'email','type' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->email ?? ''),'placeholder' => 'company@email.com']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Phone','name' => 'phone','value' => $company->phone ?? '','placeholder' => '+62xxxxxxxx']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','name' => 'phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->phone ?? ''),'placeholder' => '+62xxxxxxxx']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Website','name' => 'website','value' => $company->website ?? '','placeholder' => 'https://company.com']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Website','name' => 'website','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->website ?? ''),'placeholder' => 'https://company.com']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginaldc723fd52a2a983147bc24be67950fe5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldc723fd52a2a983147bc24be67950fe5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.file','data' => ['label' => 'Company Logo','name' => 'logo','accept' => '.jpg,.jpeg,.png,.svg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.file'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Company Logo','name' => 'logo','accept' => '.jpg,.jpeg,.png,.svg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldc723fd52a2a983147bc24be67950fe5)): ?>
<?php $attributes = $__attributesOriginaldc723fd52a2a983147bc24be67950fe5; ?>
<?php unset($__attributesOriginaldc723fd52a2a983147bc24be67950fe5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldc723fd52a2a983147bc24be67950fe5)): ?>
<?php $component = $__componentOriginaldc723fd52a2a983147bc24be67950fe5; ?>
<?php unset($__componentOriginaldc723fd52a2a983147bc24be67950fe5); ?>
<?php endif; ?>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>





<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="mb-4 flex items-start justify-between">

        <div>

            <h2 class="text-xl font-bold text-slate-800">

                Company Location

            </h2>

            <p class="mt-1 text-sm text-slate-500">

                Cari lokasi atau tandai di peta, alamat di bawah akan terisi otomatis.

            </p>

        </div>

        <span
            class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">

            GPS Ready

        </span>

    </div>

    
    <div class="relative">

        <i
            data-lucide="search"
            class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400">
        </i>

        <input

            id="search-address"

            type="text"

            placeholder="Cari alamat atau nama tempat..."

            class="w-full rounded-2xl border border-slate-300 py-3 pl-12 pr-4 text-sm shadow-sm transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">

    </div>

    <p
        id="search-status"
        class="mt-2 text-sm text-slate-500">

        Mulai ketik untuk mencari lokasi secara otomatis.

    </p>

    
    <div class="mt-4 flex flex-wrap gap-3">

        <button

            type="button"

            id="btn-current-location"

            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">

            <i
                data-lucide="locate-fixed"
                class="h-4 w-4">
            </i>

            Gunakan Lokasi Saya

        </button>

        <button

            type="button"

            id="btn-reset"

            class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold transition hover:bg-slate-100">

            <i
                data-lucide="rotate-ccw"
                class="h-4 w-4">
            </i>

            Reset Lokasi

        </button>

        <button

            type="button"

            id="btn-clear-polygon"

            class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100">

            <i
                data-lucide="trash-2"
                class="h-4 w-4">
            </i>

            Clear Polygon

        </button>

    </div>

    <p
        id="polygon-status"
        class="mt-3 text-sm text-slate-500">

        Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.

    </p>

    
    <div
        id="company-map"
        class="mt-4 h-56 w-full overflow-hidden rounded-2xl border border-slate-200 shadow-sm">

    </div>

    <input
        hidden
        id="latitude"
        name="latitude"
        value="<?php echo e(old('latitude', $company->latitude ?? '-3.319437')); ?>">

    <input
        hidden
        id="longitude"
        name="longitude"
        value="<?php echo e(old('longitude', $company->longitude ?? '114.590752')); ?>">

    <input
        hidden
        id="polygon"
        name="polygon"
        value="<?php echo e(old('polygon', isset($company) && $company->headOffice?->polygon ? json_encode($company->headOffice->polygon) : '')); ?>">
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>





<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Company Address

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Informasi lokasi perusahaan.

        </p>

    </div>

    <div class="space-y-5">

        <?php if (isset($component)) { $__componentOriginal62d1193389a71cd99ff302a00abbf991 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal62d1193389a71cd99ff302a00abbf991 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.textarea','data' => ['label' => 'Address','name' => 'address','id' => 'address','rows' => '4','value' => $company->address ?? '','placeholder' => 'Alamat perusahaan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Address','name' => 'address','id' => 'address','rows' => '4','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->address ?? ''),'placeholder' => 'Alamat perusahaan']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $attributes = $__attributesOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__attributesOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal62d1193389a71cd99ff302a00abbf991)): ?>
<?php $component = $__componentOriginal62d1193389a71cd99ff302a00abbf991; ?>
<?php unset($__componentOriginal62d1193389a71cd99ff302a00abbf991); ?>
<?php endif; ?>

        <div class="grid gap-5 md:grid-cols-3">

            <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Province','name' => 'province','id' => 'province','value' => $company->province ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Province','name' => 'province','id' => 'province','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->province ?? '')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'City','name' => 'city','id' => 'city','value' => $company->city ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'City','name' => 'city','id' => 'city','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->city ?? '')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

            <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Postal Code','name' => 'postal_code','id' => 'postal_code','value' => $company->postal_code ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Postal Code','name' => 'postal_code','id' => 'postal_code','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->postal_code ?? '')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>





<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="mb-6">

        <h2 class="text-xl font-bold text-slate-800">

            Super Administrator

        </h2>

        <p class="mt-1 text-sm text-slate-500">

            Akun administrator utama perusahaan.

        </p>

    </div>

    <div class="grid gap-5 md:grid-cols-2">

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Full Name','name' => 'admin_name','value' => $company->admin_name ?? '','placeholder' => 'Nama Lengkap','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Full Name','name' => 'admin_name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->admin_name ?? ''),'placeholder' => 'Nama Lengkap','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Username','name' => 'admin_username','value' => $company->admin_username ?? '','placeholder' => 'Username','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Username','name' => 'admin_username','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->admin_username ?? ''),'placeholder' => 'Username','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Email','name' => 'admin_email','type' => 'email','value' => $company->admin_email ?? '','placeholder' => 'admin@email.com','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Email','name' => 'admin_email','type' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->admin_email ?? ''),'placeholder' => 'admin@email.com','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.input','data' => ['label' => 'Phone','name' => 'admin_phone','value' => $company->admin_phone ?? '','placeholder' => '+62xxxxxxxx']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Phone','name' => 'admin_phone','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($company->admin_phone ?? ''),'placeholder' => '+62xxxxxxxx']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $attributes = $__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__attributesOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46)): ?>
<?php $component = $__componentOriginal65bd7e7dbd93cec773ad6501ce127e46; ?>
<?php unset($__componentOriginal65bd7e7dbd93cec773ad6501ce127e46); ?>
<?php endif; ?>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>





<?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'border-blue-100 bg-blue-50']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-blue-100 bg-blue-50']); ?>

    <div class="flex items-start gap-4">

        <div
            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">

            <i
                data-lucide="info"
                class="h-5 w-5 text-blue-600">
            </i>

        </div>

        <div>

            <h3
                class="font-semibold text-blue-800">

                Informasi

            </h3>

            <ul
                class="mt-2 list-disc space-y-1 pl-5 text-sm text-blue-700">

                <li>Password Super Administrator akan dibuat otomatis oleh sistem.</li>

                <li>Head Office akan dibuat otomatis.</li>

                <li>Subscription awal menggunakan paket <strong>Free</strong>.</li>

                <li>Maksimal karyawan awal adalah <strong>50 orang</strong>.</li>

                <li>Radius absensi Head Office otomatis <strong>200 meter</strong>.</li>

            </ul>

        </div>

    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

    



<div class="flex justify-end gap-3">

    <a href="<?php echo e(route('platform.companies.index')); ?>">

        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary']); ?>

            Cancel

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

    </a>

    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>

        <i
            data-lucide="building-2"
            class="h-5 w-5">
        </i>

        <?php echo e(isset($company) ? 'Update Company' : 'Create Company'); ?>


     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

</div>

<?php $__env->startPush('scripts'); ?>
<script>

document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Element
    |--------------------------------------------------------------------------
    */

    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    const searchInput = document.getElementById('search-address');
    const searchStatus = document.getElementById('search-status');

    const currentButton = document.getElementById('btn-current-location');
    const resetButton = document.getElementById('btn-reset');

    /*
    |--------------------------------------------------------------------------
    | Initial Coordinate
    |--------------------------------------------------------------------------
    */

    let latitude = parseFloat(latitudeInput.value);
    let longitude = parseFloat(longitudeInput.value);

    const defaultLat = latitude;
    const defaultLng = longitude;

    /*
    |--------------------------------------------------------------------------
    | Leaflet Map
    |--------------------------------------------------------------------------
    */

    const map = L.map('company-map', {

        zoomControl: true,

    }).setView([latitude, longitude], 15);

    L.tileLayer(

        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            maxZoom: 19,

            attribution: '&copy; OpenStreetMap',

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Polygon Draw Layer
    |--------------------------------------------------------------------------
    */

    const polygonInput = document.getElementById('polygon');

    const polygonStatus = document.getElementById('polygon-status');

    const clearPolygonButton = document.getElementById('btn-clear-polygon');

    const drawnItems = new L.FeatureGroup();

    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({

        draw: {

            polygon: {

                allowIntersection: false,

                showArea: true,

                shapeOptions: {

                    color: '#f59e0b',

                    fillColor: '#fbbf24',

                    fillOpacity: .25,

                },

            },

            polyline: false,

            rectangle: false,

            circle: false,

            circlemarker: false,

            marker: false,

        },

        edit: {

            featureGroup: drawnItems,

            remove: true,

        },

    });

    map.addControl(drawControl);

    function updatePolygonStatus(hasPolygon) {

        if (!polygonStatus) return;

        polygonStatus.textContent = hasPolygon

            ? 'Area polygon aktif — validasi absensi Head Office memakai bentuk area ini, bukan radius bulat.'

            : 'Belum ada area polygon. Gunakan tools gambar poligon di pojok kiri atas peta (opsional), atau biarkan kosong untuk pakai radius bulat seperti biasa.';

    }

    function savePolygonFromLayer(layer) {

        const latlngs = layer.getLatLngs()[0];

        const polygon = latlngs.map((point) => [point.lat, point.lng]);

        polygonInput.value = JSON.stringify(polygon);

        updatePolygonStatus(true);

    }

    map.on(L.Draw.Event.CREATED, function (event) {

        drawnItems.clearLayers();

        drawnItems.addLayer(event.layer);

        savePolygonFromLayer(event.layer);

    });

    map.on(L.Draw.Event.EDITED, function (event) {

        event.layers.eachLayer(function (layer) {

            savePolygonFromLayer(layer);

        });

    });

    map.on(L.Draw.Event.DELETED, function () {

        polygonInput.value = '';

        updatePolygonStatus(false);

    });

    clearPolygonButton?.addEventListener('click', function () {

        drawnItems.clearLayers();

        polygonInput.value = '';

        updatePolygonStatus(false);

    });

    if (polygonInput.value) {

        try {

            const existingPolygon = JSON.parse(polygonInput.value);

            const latlngs = existingPolygon.map((point) => [point[0], point[1]]);

            const layer = L.polygon(latlngs, {

                color: '#f59e0b',

                fillColor: '#fbbf24',

                fillOpacity: .25,

            });

            drawnItems.addLayer(layer);

            updatePolygonStatus(true);

        } catch (error) {

            console.error('Failed to load existing polygon:', error);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Marker
    |--------------------------------------------------------------------------
    */

    const marker = L.marker(

        [latitude, longitude],

        {

            draggable: true,

        }

    ).addTo(map);

    /*
    |--------------------------------------------------------------------------
    | Update UI
    |--------------------------------------------------------------------------
    */

    async function updateLocation(lat, lng)
    {

        latitude = lat;
        longitude = lng;

        latitudeInput.value = lat.toFixed(7);
        longitudeInput.value = lng.toFixed(7);

        marker.setLatLng([lat, lng]);

        map.flyTo(

            [lat, lng],

            map.getZoom(),

            {

                duration: 1.2,

            }

        );

        await reverseGeocode(lat, lng);

    }

    /*
    |--------------------------------------------------------------------------
    | Reverse Geocoding
    |--------------------------------------------------------------------------
    */

    async function reverseGeocode(lat, lng)
    {

        try{

            const response = await fetch(

                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`

            );

            const data = await response.json();

            if(!data.address){

                return;

            }

            const address = data.address;

            const addressField = document.getElementById('address');

            if(addressField){

                addressField.value = data.display_name ?? '';

            }

            const provinceField = document.getElementById('province');

            if(provinceField){

                provinceField.value =

                    address.state ??

                    address.province ??

                    '';

            }

            const cityField = document.getElementById('city');

            if(cityField){

                cityField.value =

                    address.city ??

                    address.town ??

                    address.county ??

                    address.municipality ??

                    '';

            }

            const postalField = document.getElementById('postal_code');

            if(postalField){

                postalField.value =

                    address.postcode ??

                    '';

            }

        }

        catch(error){

            console.log(error);

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Click Map
    |--------------------------------------------------------------------------
    */

    map.on('click', function(e){

        updateLocation(

            e.latlng.lat,

            e.latlng.lng

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Drag Marker
    |--------------------------------------------------------------------------
    */

    marker.on('dragend', function(e){

        const position = e.target.getLatLng();

        updateLocation(

            position.lat,

            position.lng

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Search Address
    |--------------------------------------------------------------------------
    */

    let timeout = null;

    searchInput.addEventListener('keyup', function(){

        clearTimeout(timeout);

        timeout = setTimeout(searchLocation, 700);

    });

    async function searchLocation()
    {

        const keyword = searchInput.value.trim();

        if(keyword === ''){

            searchStatus.innerHTML = 'Mulai ketik untuk mencari lokasi secara otomatis.';

            return;

        }

        searchStatus.innerHTML = 'Mencari...';

        try{

            const response = await fetch(

                'https://nominatim.openstreetmap.org/search?format=json&q=' +

                encodeURIComponent(keyword)

            );

            const data = await response.json();

            if(data.length > 0){

                const lat = parseFloat(data[0].lat);

                const lng = parseFloat(data[0].lon);

                updateLocation(lat, lng);

                map.setView([lat, lng], 17);

                searchStatus.innerHTML =

                    'Lokasi ditemukan ✔';

            }else{

                searchStatus.innerHTML =

                    'Lokasi tidak ditemukan';

            }

        }catch(error){

            console.error(error);

            searchStatus.innerHTML =

                'Pencarian gagal';

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Current Location
    |--------------------------------------------------------------------------
    */

    currentButton.addEventListener('click', function(){

        if(!navigator.geolocation){

            alert('Browser tidak mendukung GPS.');

            return;

        }

        currentButton.disabled = true;

        currentButton.innerHTML = 'Memuat GPS...';

        navigator.geolocation.getCurrentPosition(

            function(position){

                updateLocation(

                    position.coords.latitude,

                    position.coords.longitude

                );

                map.setView(

                    [

                        position.coords.latitude,

                        position.coords.longitude

                    ],

                    18

                );

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Gunakan Lokasi Saya';

            },

            function(){

                alert('Tidak dapat mengambil lokasi kamu.');

                currentButton.disabled = false;

                currentButton.innerHTML =

                    'Gunakan Lokasi Saya';

            }

        );

    });

    /*
    |--------------------------------------------------------------------------
    | Reset Location
    |--------------------------------------------------------------------------
    */

    resetButton.addEventListener('click', function(){

        updateLocation(defaultLat, defaultLng);

        map.setView([defaultLat, defaultLng], 15);

    });

    /*
    |--------------------------------------------------------------------------
    | Refresh Lucide
    |--------------------------------------------------------------------------
    */

    if(window.lucide){

        lucide.createIcons();

    }

});

</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/platform/company/_form.blade.php ENDPATH**/ ?>
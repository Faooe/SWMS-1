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

    <form
        method="GET"
        action="<?php echo e(route('offices.index')); ?>">

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">

            
            <div class="xl:col-span-2">

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Search

                </label>

                <input
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Office name, code..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-100">

            </div>

            
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Province

                </label>

                <select
                    name="province"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All Province

                    </option>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option
                            value="<?php echo e($province); ?>"
                            <?php if(request('province')==$province): echo 'selected'; endif; ?>>

                            <?php echo e($province); ?>


                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </select>

            </div>

            
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    City

                </label>

                <select
                    name="city"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All City

                    </option>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option
                            value="<?php echo e($city); ?>"
                            <?php if(request('city')==$city): echo 'selected'; endif; ?>>

                            <?php echo e($city); ?>


                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </select>

            </div>

            
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Status

                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <option value="">

                        All Status

                    </option>

                    <option
                        value="1"
                        <?php if(request('status')==='1'): echo 'selected'; endif; ?>>

                        Active

                    </option>

                    <option
                        value="0"
                        <?php if(request('status')==='0'): echo 'selected'; endif; ?>>

                        Inactive

                    </option>

                </select>

            </div>

            
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-600">

                    Per Page

                </label>

                <select
                    name="per_page"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10,25,50,100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option
                            value="<?php echo e($item); ?>"
                            <?php if(request('per_page',10)==$item): echo 'selected'; endif; ?>>

                            <?php echo e($item); ?>


                        </option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </select>

            </div>

        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">

            <button
                type="submit"
                class="rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white hover:bg-blue-700">

                Search

            </button>

            <a
                href="<?php echo e(route('offices.index')); ?>"
                class="rounded-xl border border-slate-300 px-6 py-3 hover:bg-slate-100">

                Reset

            </a>

        </div>

    </form>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/office/partials/filters.blade.php ENDPATH**/ ?>
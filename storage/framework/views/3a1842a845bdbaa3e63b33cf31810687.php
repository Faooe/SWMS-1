<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('generated_username') || session('generated_password')): ?>

<div
    x-data="{ open: true, copied: null }"

    x-show="open"

    x-transition.opacity

    class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">

    <div

        @click.outside="open = false"

        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"

        class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">

        
        <div

            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">

            <i
                data-lucide="check-circle-2"
                class="h-9 w-9 text-green-600">
            </i>

        </div>

        
        <h2

            class="mt-5 text-center text-2xl font-bold text-slate-800">

            Company Berhasil Dibuat

        </h2>

        <p

            class="mt-2 text-center text-sm text-slate-500">

            Simpan kredensial Super Administrator berikut. Password hanya ditampilkan sekali.

        </p>

        
        <div class="mt-6 space-y-4">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('generated_username')): ?>

                <div>

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                        Username

                    </label>

                    <div

                        class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">

                        <span class="font-mono text-sm font-semibold text-slate-800">

                            <?php echo e(session('generated_username')); ?>


                        </span>

                        <button

                            type="button"

                            @click="
                                navigator.clipboard.writeText('<?php echo e(session('generated_username')); ?>');
                                copied = 'username';
                                setTimeout(() => copied = null, 2000);
                            "

                            class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-200 hover:text-slate-700">

                            <i
                                x-show="copied !== 'username'"
                                data-lucide="copy"
                                class="h-4 w-4">
                            </i>

                            <i
                                x-show="copied === 'username'"
                                x-cloak
                                data-lucide="check"
                                class="h-4 w-4 text-green-600">
                            </i>

                        </button>

                    </div>

                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('generated_password')): ?>

                <div>

                    <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">

                        Password

                    </label>

                    <div

                        class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">

                        <span class="font-mono text-lg font-bold text-slate-800">

                            <?php echo e(session('generated_password')); ?>


                        </span>

                        <button

                            type="button"

                            @click="
                                navigator.clipboard.writeText('<?php echo e(session('generated_password')); ?>');
                                copied = 'password';
                                setTimeout(() => copied = null, 2000);
                            "

                            class="flex h-8 w-8 items-center justify-center rounded-lg text-amber-600 transition hover:bg-amber-100">

                            <i
                                x-show="copied !== 'password'"
                                data-lucide="copy"
                                class="h-4 w-4">
                            </i>

                            <i
                                x-show="copied === 'password'"
                                x-cloak
                                data-lucide="check"
                                class="h-4 w-4">
                            </i>

                        </button>

                    </div>

                </div>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>

        
        <div

            class="mt-5 flex gap-3 rounded-xl bg-blue-50 p-4">

            <i
                data-lucide="info"
                class="h-5 w-5 shrink-0 text-blue-600">
            </i>

            <p class="text-xs text-blue-700">

                Bagikan kredensial ini secara aman kepada Super Administrator perusahaan. Password wajib diganti saat login pertama.

            </p>

        </div>

        
        <button

            type="button"

            @click="open = false"

            class="mt-6 w-full rounded-xl bg-slate-900 py-3 font-semibold text-white transition hover:bg-slate-800">

            Saya Sudah Menyimpannya

        </button>

    </div>

</div>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?><?php /**PATH C:\Users\Husin\Documents\SWMS_Backup3\resources\views/components/platform/company-created-modal.blade.php ENDPATH**/ ?>
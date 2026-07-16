import lottie from "lottie-web";

let loadingAnim = null;
let completeAnim = null;
let completeTimeout = null;

/*
|--------------------------------------------------------------------------
| Init Lottie Animations
|--------------------------------------------------------------------------
*/
function initAnimations() {

    const loadingContainer = document.getElementById("lottie-loading");
    const completeContainer = document.getElementById("lottie-complete");

    if (loadingContainer && !loadingAnim) {
        loadingAnim = lottie.loadAnimation({
            container: loadingContainer,
            renderer: "svg",
            loop: true,
            autoplay: false,
            path: "/lottie/loading.json",
        });
    }

    if (completeContainer && !completeAnim) {
        completeAnim = lottie.loadAnimation({
            container: completeContainer,
            renderer: "svg",
            loop: false,
            autoplay: false,
            path: "/lottie/complete.json",
        });
    }
}

/*
|--------------------------------------------------------------------------
| Show / Hide Loading Overlay
|--------------------------------------------------------------------------
*/
export function showLoading() {

    const overlay = document.getElementById("loading-overlay");
    if (!overlay) return;

    overlay.classList.remove("hidden");
    loadingAnim?.goToAndPlay(0, true);
}

export function hideLoading() {

    const overlay = document.getElementById("loading-overlay");
    if (!overlay) return;

    overlay.classList.add("hidden");
    loadingAnim?.stop();
}

/*
|--------------------------------------------------------------------------
| Show Complete Animation (auto-hide)
|--------------------------------------------------------------------------
*/
export function showComplete(duration = 1500) {

    const overlay = document.getElementById("complete-overlay");
    if (!overlay) return;

    clearTimeout(completeTimeout);

    overlay.classList.remove("hidden");
    completeAnim?.goToAndPlay(0, true);

    completeTimeout = setTimeout(() => {
        overlay.classList.add("hidden");
        completeAnim?.stop();
    }, duration);
}

/*
|--------------------------------------------------------------------------
| Bootstrap: hook into Livewire + DOM lifecycle
|--------------------------------------------------------------------------
*/
export function bootLoadingAnimation() {

    initAnimations();

    // Re-init lottie containers setelah Livewire navigate (SPA-like)
    document.addEventListener("livewire:navigated", () => {
        initAnimations();
    });

    // Tampilkan loading saat mulai pindah halaman
    document.addEventListener("livewire:navigate", () => {
        showLoading();
    });

    // Sembunyikan loading setelah halaman baru selesai render
    document.addEventListener("livewire:navigated", () => {
        hideLoading();
    });

    // Custom event dari komponen Livewire: $this->dispatch('action-complete')
    document.addEventListener("livewire:init", () => {
        Livewire.on("action-complete", () => {
            showComplete();
        });

        Livewire.on("action-loading", () => {
            showLoading();
        });

        Livewire.on("action-loading-done", () => {
            hideLoading();
        });
    });
}
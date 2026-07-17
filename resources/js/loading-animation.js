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

    // Loading overlay always wins over a still-visible complete overlay.
    document.getElementById("complete-overlay")?.classList.add("hidden");
    clearTimeout(completeTimeout);

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
export function showComplete(duration = 1500, label = null) {

    const overlay = document.getElementById("complete-overlay");
    if (!overlay) return;

    hideLoading();

    if (label) {
        const labelEl = document.getElementById("complete-overlay-label");
        if (labelEl) labelEl.textContent = label;
    }

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
| Auto-trigger complete animation from a server-rendered flash message
|--------------------------------------------------------------------------
|
| Controllers across the app already do `->with('success', '...')`.
| <body data-flash-success="true|false"> is rendered fresh on every
| full page load (and after a Livewire redirect/navigate swaps the body),
| so we don't need to touch every controller individually.
|
*/
function checkFlashSuccess() {

    if (document.body?.dataset.flashSuccess === "true") {
        showComplete();
    }
}

/*
|--------------------------------------------------------------------------
| Global hooks for plain (non-Livewire) forms & buttons
|--------------------------------------------------------------------------
|
| Any <form> submit shows the loading animation automatically.
| Opt out per-form with `data-no-loading`.
| Any element (button/link) with `data-loading-click` shows it on click,
| useful for plain <a href="..."> links that navigate the page.
|
*/
function bindGlobalTriggers() {

    document.addEventListener("submit", (event) => {

        const form = event.target;

        if (!(form instanceof HTMLFormElement)) return;
        if (form.hasAttribute("data-no-loading")) return;
        if (form.hasAttribute("wire:submit") || form.hasAttribute("wire:submit.prevent")) return;
        if (event.defaultPrevented) return; // e.g. an onsubmit="return confirm(...)" that was cancelled

        showLoading();

    });

    document.addEventListener("click", (event) => {

        const trigger = event.target.closest("[data-loading-click]");

        if (!trigger || event.defaultPrevented) return;

        showLoading();

    });
}

/*
|--------------------------------------------------------------------------
| Bootstrap: hook into Livewire + DOM lifecycle
|--------------------------------------------------------------------------
*/
export function bootLoadingAnimation() {

    initAnimations();
    bindGlobalTriggers();
    checkFlashSuccess();

    // Re-init lottie containers setelah Livewire navigate (SPA-like)
    document.addEventListener("livewire:navigated", () => {
        initAnimations();
        hideLoading();
        checkFlashSuccess();
    });

    // Tampilkan loading saat mulai pindah halaman
    document.addEventListener("livewire:navigate", () => {
        showLoading();
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

    // Livewire requests that are NOT explicitly wrapped with the events
    // above still get a subtle loading state automatically.
    document.addEventListener("livewire:init", () => {
        Livewire.hook("request", ({ fail }) => {
            fail(() => hideLoading());
        });
    });
}

// Expose a small public API so plain Blade views / inline scripts
// (non-Livewire pages) can trigger the animations manually, e.g:
//   window.SWMS.showLoading();
//   window.SWMS.showComplete();
window.SWMS = { showLoading, hideLoading, showComplete };

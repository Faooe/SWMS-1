import { initNotifications } from "./notifications";

import {
    compressAssignmentPhoto,
    compressImageForUpload,
    compressMediaForUpload,
    compressVideoForUpload,
    formatFileSize,
} from "./assignment-photo-compress";

// Dipasang ke window supaya bisa dipanggil dari inline <script> di Blade
// (mis. resources/views/employee/assignments/partials/actions.blade.php)
// yang bukan ES module -- lihat komentar di assignment-photo-compress.js.
window.compressAssignmentPhoto = compressAssignmentPhoto;
window.compressImageForUpload = compressImageForUpload;
window.compressMediaForUpload = compressMediaForUpload;
window.compressVideoForUpload = compressVideoForUpload;
window.formatFileSize = formatFileSize;


// Livewire v3 sudah membawa & menjalankan Alpine.js sendiri secara
// internal (via @livewireScripts) -- jangan import & start Alpine lagi
// di sini, nanti kedetect "multiple instances of Alpine running".
// Alpine.store/Alpine.data harus didaftarkan SEBELUM Alpine jalan,
// makanya dipasang lewat event "alpine:init" (dipicu Livewire tepat
// sebelum Alpine.start() internal-nya dipanggil).
document.addEventListener("alpine:init", () => {
    initNotifications(window.Alpine);
});

import { createIcons, icons } from "lucide";

import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

window.Chart = Chart;
import { bootLoadingAnimation } from "./loading-animation";

document.addEventListener("livewire:navigated", () => {
    createIcons({ icons });
});

document.addEventListener("livewire:init", () => {
    // Re-create Lucide icons after every Livewire DOM update (search,
    // filter, sort, pagination, toggle, delete, etc). "morph.updated" is a
    // Livewire JS hook (Livewire.hook), not a DOM CustomEvent - it cannot
    // be caught with document.addEventListener.
    Livewire.hook("morph.updated", () => {
        createIcons({ icons });
    });
});

document.addEventListener("DOMContentLoaded", () => {

    createIcons({ icons });
    bootLoadingAnimation();

    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const body = document.body;

    const sidebar = document.getElementById("sidebar");

    const overlay = document.getElementById("sidebar-overlay");

    const collapseButton = document.getElementById("sidebar-collapse");

    const mobileButton = document.getElementById("sidebar-toggle");

    /*
    |--------------------------------------------------------------------------
    | Restore Desktop State
    |--------------------------------------------------------------------------
    */

    if (

        window.innerWidth >= 1024 &&

        localStorage.getItem("sidebar") === "collapsed"

    ) {

        body.classList.add("sidebar-collapsed");

        changeCollapseIcon();

    }

    /*
    |--------------------------------------------------------------------------
    | Desktop Collapse
    |--------------------------------------------------------------------------
    */

    collapseButton?.addEventListener("click", () => {

        body.classList.toggle("sidebar-collapsed");

        localStorage.setItem(

            "sidebar",

            body.classList.contains("sidebar-collapsed")

                ? "collapsed"

                : "expanded"

        );

        changeCollapseIcon();

    });

    /*
    |--------------------------------------------------------------------------
    | Mobile Drawer
    |--------------------------------------------------------------------------
    */

    mobileButton?.addEventListener("click", () => {

        sidebar.classList.add("show");

        overlay.classList.remove("hidden");

    });

    /*
    |--------------------------------------------------------------------------
    | Overlay Close
    |--------------------------------------------------------------------------
    */

    overlay?.addEventListener("click", () => {

        sidebar.classList.remove("show");

        overlay.classList.add("hidden");

    });

    /*
    |--------------------------------------------------------------------------
    | Window Resize
    |--------------------------------------------------------------------------
    */

    window.addEventListener("resize", () => {

        if (window.innerWidth >= 1024) {

            sidebar.classList.remove("show");

            overlay.classList.add("hidden");

        }

    });

    /*
    |--------------------------------------------------------------------------
    | Collapse Icon
    |--------------------------------------------------------------------------
    */

    function changeCollapseIcon() {

        if (!collapseButton) return;

        const icon = collapseButton.querySelector("i");

        if (!icon) return;

        icon.setAttribute(

            "data-lucide",

            body.classList.contains("sidebar-collapsed")

                ? "panel-left-open"

                : "panel-left-close"

        );

        createIcons({

            icons

        });

    }

});

document.addEventListener('DOMContentLoaded', () => {
  // Pemberitahuan non-blocking agar pengguna tahu foto besar sedang
  // diperkecil otomatis sebelum form dikirim.
  window.addEventListener('swms:file-compression-start', (event) => {
    const detail = event.detail || {};
    const maxKb = Math.round((detail.maxBytes || 204800) / 1024);
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-[100] max-w-sm rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-800 shadow-xl';
    const label = detail.kind === 'video' ? 'Video' : 'Foto';
    toast.textContent = `${label} melebihi ${maxKb >= 1024 ? `${(maxKb / 1024).toFixed(0)} MB` : `${maxKb} KB`}. File sedang dikompres otomatis...`;
    document.body.appendChild(toast);
    window.setTimeout(() => toast.remove(), 4200);
  });

  window.addEventListener('swms:file-compression-unavailable', (event) => {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-[100] max-w-sm rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 shadow-xl';
    toast.textContent = event.detail?.message || 'Kompresi video otomatis tidak tersedia di browser ini.';
    document.body.appendChild(toast);
    window.setTimeout(() => toast.remove(), 5200);
  });

  window.addEventListener('swms:file-duration-invalid', () => {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-[100] max-w-sm rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800 shadow-xl';
    toast.textContent = 'Video tidak dapat dipilih karena durasinya lebih dari 1 menit.';
    document.body.appendChild(toast);
    window.setTimeout(() => toast.remove(), 5200);
  });

  document.querySelectorAll('input[type="file"][data-compress-media]').forEach((input) => {
    input.addEventListener('change', async () => {
      const files = Array.from(input.files || []);
      if (!files.length || !window.compressMediaForUpload) return;
      const dt = new DataTransfer();
      for (const file of files) {
        try {
          dt.items.add(await window.compressMediaForUpload(file));
        } catch (error) {
          console.warn('File media tidak dipilih:', error);
        }
      }
      input.files = dt.files;
    });
  });

  document.querySelectorAll('input[type="file"][data-compress-image]').forEach((input) => {
    input.addEventListener('change', async () => {
      const file = input.files?.[0];
      if (!file || !file.type.startsWith('image/') || !window.compressImageForUpload) return;
      const compressed = await window.compressImageForUpload(file);
      const dt = new DataTransfer(); dt.items.add(compressed); input.files = dt.files;
      if (input.dataset.autoSubmit === 'true' && input.form) input.form.submit();
    });
  });
});

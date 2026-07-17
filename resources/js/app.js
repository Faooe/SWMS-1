import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

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
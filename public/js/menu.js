function initializeSidebarToggle() {
    const hamBurger = document.querySelector("#toggle-btn");
    const sidebar = document.querySelector("#sidebar");
    const content = document.querySelector(".content");

    if (hamBurger && sidebar && content) {
        hamBurger.removeEventListener("click", toggleSidebar);
        hamBurger.addEventListener("click", function() {
            toggleSidebar(sidebar, content, hamBurger);
        });
    } else {
        console.error("Éléments #toggle-btn ou #sidebar introuvables");
    }
}
function toggleSidebar(sidebar, content, hamBurger) {
    if (sidebar && content && hamBurger) {
        const isExpanded = sidebar.classList.toggle("expand");
        content.style.marginLeft = isExpanded ? "180px" :"70px";
        hamBurger.setAttribute("aria-expanded", isExpanded);
    }
}

function initializeOffcanvasToggle() {
    const menuToggle = document.querySelector("#menuToggle");
    const menuClose = document.querySelector("#menuClose");
    const offcanvas = document.querySelector("#mobileMenu");

    if (menuToggle && menuClose && offcanvas) {
        menuToggle.removeEventListener("click", openOffcanvas);
        menuClose.removeEventListener("click", closeOffcanvas);

        menuToggle.addEventListener("click", function () {
            openOffcanvas(offcanvas);
        });

        menuClose.addEventListener("click", function () {
            closeOffcanvas(offcanvas);
        });

        document.removeEventListener("click", handleOutsideClick);
        document.addEventListener("click", function (e) {
            handleOutsideClick(e, offcanvas, menuToggle);
        });
    } else {
        console.error("Éléments pour le offcanvas introuvables");
    }
}

function openOffcanvas(offcanvas) {
    if (offcanvas) {
        offcanvas.classList.add("show");
    }
}

function closeOffcanvas(offcanvas) {
    if (offcanvas) {
        offcanvas.classList.remove("show");
    }
}

function handleOutsideClick(event, offcanvas, toggleButton) {
    if (
        offcanvas.classList.contains("show") &&
        !offcanvas.contains(event.target) &&
        event.target !== toggleButton
    ) {
        closeOffcanvas(offcanvas);
    }
}

function initializeMenu() {
    initializeSidebarToggle();
    initializeOffcanvasToggle();
}

document.addEventListener("turbo:load", initializeMenu);

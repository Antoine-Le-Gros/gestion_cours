document.addEventListener("turbo:load", initializeMenu);

function initializeMenu() {
    initializeSidebarToggle();
    initializeOffcanvasToggle();
}

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
        menuToggle.addEventListener("click", function () {
            openOffcanvas(offcanvas);
            menuToggle.style.display = 'none';
        });

        menuClose.addEventListener("click", function () {
            closeOffcanvas(offcanvas);
            menuToggle.style.display = '';
        });

        document.addEventListener("click", function (e) {
            if (offcanvas.classList.contains("show") &&
                !offcanvas.contains(e.target) &&
                !menuToggle.contains(e.target)) {
                closeOffcanvas(offcanvas);
                menuToggle.style.display = '';
            }
        });
    } else {
        console.error("Éléments pour le offcanvas introuvables");
    }
}

function openOffcanvas(offcanvas) {
    offcanvas.classList.add("show");
    document.body.classList.add("offcanvas-open");
}

function closeOffcanvas(offcanvas) {
    offcanvas.classList.remove("show");
    document.body.classList.remove("offcanvas-open");
}




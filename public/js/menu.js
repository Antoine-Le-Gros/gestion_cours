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

document.addEventListener("DOMContentLoaded", initializeSidebarToggle);
document.addEventListener("turbo:load", initializeSidebarToggle);

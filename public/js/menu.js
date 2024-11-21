function initializeSidebarToggle() {
    const hamBurger = document.querySelector("#toggle-btn");
    const sidebar = document.querySelector("#sidebar");

    if (hamBurger && sidebar) {
        hamBurger.removeEventListener("click", toggleSidebar);
        hamBurger.addEventListener("click", toggleSidebar);
    } else {
        console.error("Éléments #toggle-btn ou #sidebar introuvables");
    }
}
function toggleSidebar() {
    const sidebar = document.querySelector("#sidebar");
    const hamBurger = document.querySelector("#toggle-btn");

    if (sidebar && hamBurger) {
        const isExpanded = sidebar.classList.toggle("expand");
        hamBurger.setAttribute("aria-expanded", isExpanded);
    }
}

document.addEventListener("DOMContentLoaded", initializeSidebarToggle);
document.addEventListener("turbo:load", initializeSidebarToggle);

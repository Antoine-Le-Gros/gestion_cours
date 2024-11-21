function initializeSidebarToggle() {
    const hamBurger = document.querySelector("#toggle-btn");
    const sidebar = document.querySelector("#sidebar");
    const content = document.querySelector(".content");

    if (hamBurger && sidebar && content) {
        hamBurger.addEventListener("click", function() {
            toggleSidebar(sidebar, content, hamBurger);
        });
    } else {
        console.error("Éléments #toggle-btn ou #sidebar introuvables");
    }
}
function toggleSidebar(sidebar, content, hamBurger) {
    if (sidebar.classList.contains("expand")) {
        sidebar.classList.remove("expand");
        content.style.marginLeft = "70px";
    } else {
        sidebar.classList.add("expand");
        content.style.marginLeft = "180px";
    }
}

document.addEventListener("DOMContentLoaded", initializeSidebarToggle);
document.addEventListener("turbo:load", initializeSidebarToggle);

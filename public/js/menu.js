document.addEventListener("DOMContentLoaded", function () {
    const hamBurger = document.querySelector("#toggle-btn");
    const sidebar = document.querySelector("#sidebar");

    if (hamBurger && sidebar) {
        hamBurger.addEventListener("click", function () {
            const isExpanded = sidebar.classList.toggle("expand");
            hamBurger.setAttribute("aria-expanded", isExpanded);
        });
    } else {
        console.error("Éléments #toggle-btn ou #sidebar introuvables");
    }
});

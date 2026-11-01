const sidebarMenu = document.getElementById("sidebarMenu");
const sidebarToggles = [
  document.getElementById("sidebarToggle"),
  document.getElementById("navbarSidebarToggle"),
].filter(Boolean);

sidebarToggles.forEach((sidebarToggle) =>
  sidebarToggle.addEventListener("click", () => {
    const isCollapsed = sidebarMenu.classList.toggle("sidebar-collapsed");
    sidebarToggles.forEach((toggle) => {
      toggle.setAttribute("aria-expanded", String(!isCollapsed));
      const icon = toggle.querySelector("i");
      if (icon) {
        icon.classList.toggle("bi-chevron-left", !isCollapsed);
        icon.classList.toggle("bi-chevron-right", isCollapsed);
      }
    });
  }),
);

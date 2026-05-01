(function () {
  const sidebar       = document.getElementById('sidebar');
  const toggleBtn     = document.getElementById('sidebarToggle');
  const toggleIcon    = document.getElementById('toggleIcon');
  const overlay       = document.getElementById('sidebarOverlay');
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');

  if (!sidebar) return;

  // Restore desktop collapsed state
  let collapsed = localStorage.getItem('aits_sidebar_collapsed') === 'true';
  if (collapsed) applyCollapsed(false);

  // Desktop toggle
  toggleBtn?.addEventListener('click', () => {
    collapsed = !collapsed;
    localStorage.setItem('aits_sidebar_collapsed', collapsed);
    applyCollapsed(true);
  });

  function applyCollapsed(animate) {
    if (!animate) sidebar.style.transition = 'none';
    sidebar.classList.toggle('collapsed', collapsed);
    if (toggleIcon) {
      toggleIcon.className = collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left';
    }
    if (!animate) requestAnimationFrame(() => sidebar.style.transition = '');
  }

  // Mobile open
  mobileMenuBtn?.addEventListener('click', () => {
    sidebar.classList.add('mobile-open');
    overlay?.classList.add('active');
  });

  // Mobile close on overlay click
  overlay?.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
  });
})();
document.addEventListener('DOMContentLoaded', function () {
    const sidebar     = document.querySelector('#sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn   = document.getElementById('sidebarToggleTop');

    // Mobile overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 d-none';
    document.body.appendChild(overlay);

    /* --------------------------------------------------------
       Core helper: apply collapsed / expanded state
    -------------------------------------------------------- */
    function setSidebarState(collapsed) {
        if (!sidebar) return;
        if (collapsed) {
            sidebar.classList.add('collapsed');
            if (mainContent) mainContent.classList.add('expanded');
            if (window.innerWidth < 992) {
                overlay.classList.remove('d-none');
                document.body.style.overflow = 'hidden';
            }
        } else {
            sidebar.classList.remove('collapsed');
            if (mainContent) mainContent.classList.remove('expanded');
            overlay.classList.add('d-none');
            document.body.style.overflow = '';
        }
    }

    /* --------------------------------------------------------
       Initial state
    -------------------------------------------------------- */
    const path       = window.location.pathname;
    const isFormPage = /\/(create|edit)(\/|$)/.test(path);
    const isMobile   = window.innerWidth < 992;

    if (isFormPage) {
        // Form pages: auto-hide regardless of preference
        setSidebarState(true);
    } else if (isMobile) {
        // Mobile: always start hidden
        setSidebarState(true);
    } else {
        // Desktop: restore user preference from localStorage
        const saved = localStorage.getItem('sidebar_collapsed');
        setSidebarState(saved === 'true');
    }

    /* --------------------------------------------------------
       Toggle button (hamburger)
    -------------------------------------------------------- */
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const willCollapse = !sidebar.classList.contains('collapsed');
            setSidebarState(willCollapse);
            // Persist preference only on non-form desktop pages
            if (!isFormPage && window.innerWidth >= 992) {
                localStorage.setItem('sidebar_collapsed', willCollapse ? 'true' : 'false');
            }
        });
    }

    /* --------------------------------------------------------
       Mobile: close when overlay or nav-link is clicked
    -------------------------------------------------------- */
    overlay.addEventListener('click', () => setSidebarState(true));

    if (sidebar) {
        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) setSidebarState(true);
            });
        });
    }
});
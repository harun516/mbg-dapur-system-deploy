document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('#sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleBtn = document.getElementById('sidebarToggleTop');
    
    // Inisialisasi Overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 d-none';
    document.body.appendChild(overlay);

    function toggleSidebar() {
        const isMobile = window.innerWidth < 992;

        if (isMobile) {
            sidebar.classList.toggle('collapsed'); // Sifatnya show/hide dari kiri
            overlay.classList.toggle('d-none');
            document.body.style.overflow = sidebar.classList.contains('collapsed') ? '' : 'hidden';
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    }

    // Event Listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            toggleSidebar();
        });
    }

    overlay.addEventListener('click', toggleSidebar);

    // Initial check untuk mobile agar tidak flicker
    if (window.innerWidth < 992) {
        sidebar.classList.add('collapsed');
    }
});
// Import Bootstrap bundle (already includes popper.js)
import './bootstrap';

// Sidebar toggle is now handled by Bootstrap Offcanvas component via data attributes
// No custom JS needed - data-bs-toggle="offcanvas" on the button handles everything
// import './sidebar-toggle.js'; // REMOVED - using Bootstrap Offcanvas instead
import './dapur/menu/notif-menu';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;

window.Alpine = Alpine;

Alpine.start();

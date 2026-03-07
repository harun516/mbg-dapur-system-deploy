import './bootstrap';
// Import sidebar toggle custom
import './sidebar-toggle.js';
import './dapur/menu/notif-menu';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;

window.Alpine = Alpine;

Alpine.start();

import './bootstrap';

import Alpine from 'alpinejs';

// Solo inicializar Alpine si no está ya inicializado
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}

import './bootstrap';
import {initStartAppointment} from "./pages/start-appointment.js";

document.addEventListener('DOMContentLoaded', () => {
    const p = window.location.pathname;
    window.currentUserRole = window.currentUserRole || '';
    if (p.startsWith('/users')) {
        import('./pages/users').then(m => m.initUsers());
    } else if (p.startsWith('/clients')) {
        import('./pages/users.js').then(m => m.initClients());
    } else if (p.startsWith('/pets')) {
        import('./pages/pets').then(m => m.initPets());
    } else if (p.startsWith('/services')) {
        import('./pages/services').then(m => m.initServices());
    } else if (p.startsWith('/veterinarians')) {
        import('./pages/veterinarians').then(m => m.initVets());
    } else if (p.startsWith('/about')) {
        import('./pages/about').then(m => m.initAbout());
    } else if (p.startsWith('/my-appointments')) {
        import('./pages/myAppointments').then(m => m.initMyAppointments());
    }else if (p.startsWith('/news')) {
        import('./pages/news').then(m => m.initNews());
    }else if (p.startsWith('/change-roles')) {
        import('./pages/changeRoles').then(m => m.initChange());
    } else if (p.startsWith('/appointments/start')) {
        import('./pages/start-appointment.js').then(m => m.initStartAppointment());
    }else if (p.startsWith('/appointments')) {
        import('./pages/appointments').then(m => m.initAppointments());
    }
});
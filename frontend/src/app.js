import './bootstrap';
document.addEventListener('DOMContentLoaded', () => {
    const p = window.location.pathname;
    if (p.startsWith('/clients')) {
        import('./pages/clients').then(m => m.initClients());         // CHANGED
    } else if (p.startsWith('/appointments')) {
        import('./pages/appointments').then(m => m.initAppointments()); // CHANGED
    } else if (p.startsWith('/pets')) {
        import('./pages/pets').then(m => m.initPets());               // CHANGED
    } else if (p.startsWith('/services')) {
        import('./pages/services').then(m => m.initServices());       // CHANGED
    } else if (p.startsWith('/veterinarians')) {
        import('./pages/veterinarians').then(m => m.initVets());      // CHANGED
    }
});

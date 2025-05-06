import { createApp } from 'vue';
import App from './Vue/App.vue';

// глобальные компоненты
import ClientsApp        from './Vue/ClientsApp.vue';
import PetsApp           from './Vue/PetsApp.vue';
import AppointmentsApp   from './Vue/AppointmentsApp.vue';
import ServicesApp       from './Vue/ServicesApp.vue';
import VeterinariansApp  from './Vue/VeterinariansApp.vue';

const app = createApp(App);

app.component('clients-app',       ClientsApp);
app.component('pets-app',          PetsApp);
app.component('appointments-app',  AppointmentsApp);
app.component('services-app',      ServicesApp);
app.component('veterinarians-app', VeterinariansApp);

app.mount('#app');

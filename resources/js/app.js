import './bootstrap';

import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css'


import LoginForm from './resources/login-form.vue';
import RegisterForm from "./resources/register-form.vue";
import ListConversations from "./resources/list-conversations.vue";

Vue.use(Vuetify);

const vuetify = new Vuetify();

Vue.component('login-form', LoginForm);
Vue.component('register-form', RegisterForm);
Vue.component('list-conversations', ListConversations);



new Vue({
    vuetify,
    el: '#app',
    components: { LoginForm, RegisterForm, ListConversations },
});


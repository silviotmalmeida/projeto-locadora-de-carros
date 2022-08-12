/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;

import Vue from "vue";
// importando o Vuex para permitir a utilização da store pelos componentes
import Vuex from "vuex";
Vue.use(Vuex);

// criando a store comportilhada pelos componentes
const store = new Vuex.Store({
    state: {
        // atributos globais da aplicação, acessíveis a todos os componentes
        selectedBrand: {},
        // status da requisição (sucess ou error)
        request_status: "",
        // mensagens a serem exibidas nos alerts
        request_messages: [],
    },
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// registro dos componentes a serem utilizados
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "login-component",
    require("./components/LoginComponent.vue").default
);
Vue.component(
    "home-component",
    require("./components/HomeComponent.vue").default
);
Vue.component(
    "brands-component",
    require("./components/BrandsComponent.vue").default
);
Vue.component(
    "input-container-component",
    require("./components/InputContainerComponent.vue").default
);
Vue.component(
    "table-component",
    require("./components/TableComponent.vue").default
);
Vue.component(
    "card-component",
    require("./components/CardComponent.vue").default
);
Vue.component(
    "modal-component",
    require("./components/ModalComponent.vue").default
);
Vue.component(
    "alert-component",
    require("./components/AlertComponent.vue").default
);
Vue.component(
    "pagination-component",
    require("./components/PaginationComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// criando um filtro de formatação global para converter o datetime
// para o formato dd/mm/aaaa hh:mm
Vue.filter("formatDateTime", function (value) {
    // se não foi passado nenhum valor, retorna vazio
    if (!value) return "";

    // formatando como dd/mm/aaaa hh:mm
    return new Intl.DateTimeFormat("pt-BR", {
        day: "numeric",
        month: "numeric",
        year: "numeric",
        hour: "numeric",
        minute: "numeric",
    }).format(new Date(value));
});

const app = new Vue({
    el: "#app",

    // adicionando a store ao app
    store,
});

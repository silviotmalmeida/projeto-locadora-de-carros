const { default: axios } = require("axios");

window._ = require("lodash");

try {
    require("bootstrap");
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

// definindo os interceptors do axios
//
// interceptando os requests
axios.interceptors.request.use(
    // em caso de sucesso
    (config) => {
        // altera o fluxo da requisição
        //
        // obtendo o token através dos cookies para anexá-lo explicitamento no
        // header das requisições
        let token = document.cookie
            // removendo os espaços
            .replace(/\s/g, "")
            // separando os cookies
            .split(";")
            // encontrando o token de autorização
            .find((key) => {
                return key.startsWith("token=");
            });
        // obtendo o corpo do token
        token = token.split("=")[1];
        // incluindo o prefixo 'Bearer ' ao token
        token = "Bearer " + token;

        // definindo de forma global os headers comuns a todas as requisições
        config.headers.Accept = "application/json";
        config.headers.Authorization = token;

        // prossegue com a requisição
        return config;
    },

    // em caso de erro
    (error) => {
        // altera o fluxo da requisição
        console.log("Erro no request: ", error);

        // prossegue com a rejeição
        return Promise.reject(error);
    }
);

// interceptando as responses
axios.interceptors.response.use(
    // em caso de sucesso
    (response) => {
        // altera o fluxo da resposta
        console.log("Antes da resposta ao frontend", response);

        // prossegue com a resposta
        return response;
    },

    // em caso de erro
    (error) => {
        // altera o fluxo da reposta
        //
        // se o token estiver expirado
        if (
            error.response.status == 401 &&
            error.response.data.message == "Token has expired"
        ) {
            // faz-se uma requisição de refresh do token
            axios
                .post("http://0.0.0.0:8080/api/refresh")
                // em caso de sucesso
                .then((response) => {
                    // atualiza o cookie da sessão para permitir as requisições com autenticação
                    document.cookie = "token=" + response.data.token;
                    // forçando a atualização da página
                    window.location.reload();
                });
        }

        // prossegue com a rejeição
        return Promise.reject(error);
    }
);

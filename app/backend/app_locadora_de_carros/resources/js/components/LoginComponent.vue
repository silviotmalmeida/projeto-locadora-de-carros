<template>
    <!-- trecho de código que representa o html do componente -->

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">

                        <!-- criando o formulário -->
                        <!-- foi incluido o @submit.prevent para interceptar o evento padrão de submit, incluindo o comportamento do método login() -->
                        <form method="POST" action="" @submit.prevent="login($event)">

                            <!-- inserindo o csrf_token -->
                            <!-- o atributo :value insere no atributo value da tag o valor da variável referenciada -->
                            <input type="hidden" name="_token" :value="csrf_token">

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">E-mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required
                                        autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Senha</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required
                                        autocomplete="current-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">Lembrar de mim</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Login</button>

                                    <a class="btn btn-link" href="">Esqueci a
                                        senha...</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

export default {

    // propriedades a serem recebidas para criação do componente
    // as propriedades são definidas como atributos na tag do componente
    props: ['csrf_token'],

    // função que retorna o estado inicial das variáveis do componente
    data: function () {
        return {
            
            email: '',
            
            password: '',
        };
    },

    // comportamentos do componente
    methods: {

        // método para obter o token de autorização da api e simultaneamente logar no admin web
        login($e) {

            let url = 'http://0.0.0.0:8080/api/login';
            let configuration = {
                method: 'post',
                body: {
                    'email': '',
                    'password': ''
                }
            }
            fetch(url, configuration);
            console.log('teste');
        }
    }
}

</script>

<style>
/* trecho de código que representa o css do componente */
</style>

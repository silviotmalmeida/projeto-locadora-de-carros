<template>
  <!-- trecho de código que representa o html do componente -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- card de login -->
        <card-component title="Login">
          <!-- inserindo o content -->
          <template v-slot:content>
            <!-- criando o formulário -->
            <!-- foi incluido o @submit.prevent para interceptar o evento padrão de submit, substituindo-o pelo comportamento do método login() -->
            <form method="POST" action="" @submit.prevent="login($event)">
              <!-- inserindo o csrf_token -->
              <!-- o atributo :value insere no atributo value da tag o valor da variável referenciada -->
              <input type="hidden" name="_token" :value="csrf_token" />

              <!-- inserindo o campo de e-mail -->
              <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end"
                  >E-mail</label
                >

                <!-- foi incluído o recurso de two-way-data-binding
                com o uso do atributo v-model vinculado à variável email -->
                <div class="col-md-6">
                  <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    value=""
                    required
                    autocomplete="email"
                    autofocus
                    v-model="email"
                  />
                </div>
              </div>

              <!-- inserindo o campo de senha -->
              <div class="row mb-3">
                <label
                  for="password"
                  class="col-md-4 col-form-label text-md-end"
                  >Senha</label
                >

                <!-- foi incluído o recurso de two-way-data-binding
                com o uso do atributo v-model vinculado à variável password -->
                <div class="col-md-6">
                  <input
                    id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    required
                    autocomplete="current-password"
                    v-model="password"
                  />
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6 offset-md-4">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="remember"
                      id="remember"
                    />

                    <label class="form-check-label" for="remember"
                      >Lembrar de mim</label
                    >
                  </div>
                </div>
              </div>

              <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-primary">Login</button>

                  <a class="btn btn-link" href="">Esqueci a senha...</a>
                </div>
              </div>
            </form>
          </template>
        </card-component>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["csrf_token"],

  // função que retorna o estado inicial das variáveis do componente
  data: function () {
    return {
      // inicializando as variáveis
      email: "",
      password: "",
    };
  },

  // comportamentos do componente
  methods: {
    // método para obter o token de autorização da api e simultaneamente logar na sessão do admin web
    login(e) {
      // definindo a url de login na api
      let url = "http://0.0.0.0:8080/api/login";

      // definindo as configurações da requisição
      let configuration = {
        // definindo o método
        method: "post",

        // definindo o body
        body: new URLSearchParams({
          email: this.email,
          password: this.password,
        }),
      };

      // executando a requisição
      fetch(url, configuration)
        // convertendo a resposta para json
        .then((response) => response.json())
        // obtendo os dados da resposta
        .then((data) => {
          // se o token for recebido com sucesso
          if (data.token) {
            // cria um cookie da sessão para permitir as requisições com autenticação
            document.cookie = "token=" + data.token + ";SameSite=Lax";
          }

          // executa o comportamento original do evento submit
          // autenticando a sessão no admin web
          e.target.submit();
        });
    },
  },
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

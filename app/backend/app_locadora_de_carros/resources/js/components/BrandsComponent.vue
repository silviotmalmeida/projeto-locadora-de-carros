<template>
  <!-- trecho de código que representa o html do componente -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- card de busca -->
        <card-component title="Busca de Marcas">
          <!-- inserindo o content -->
          <template v-slot:content>
            <!-- inserindo o formulário de busca -->
            <div class="row">
              <div class="col mb-3">
                <!-- incluindo o componente de input para o ID-->
                <input-container-component
                  label="ID"
                  inputId="inputID"
                  helpID="helpID"
                  helpText="Opcional. Informe o ID da marca."
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    placeholder="ID"
                    aria-describedby="helpID"
                  />
                </input-container-component>
              </div>

              <div class="col mb-3">
                <!-- incluindo o componente de input para o Nome-->
                <input-container-component
                  label="Nome"
                  inputId="inputNAME"
                  helpID="helpNAME"
                  helpText="Opcional. Informe o nome da marca."
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNAME"
                    placeholder="Nome"
                    aria-describedby="helpNAME"
                  />
                </input-container-component>
              </div>
            </div>
          </template>

          <!-- inserindo o footer -->
          <template v-slot:footer>
            <!-- inserindo o botão de pesquisar -->
            <button type="submit" class="btn btn-primary float-end">
              Pesquisar
            </button>
          </template>
        </card-component>

        <!-- card de listagem -->
        <card-component title="Listagem de Marcas">
          <!-- inserindo o content -->
          <template v-slot:content>
            <!-- inserindo a tabela com os resultados da busca-->
            <table-component></table-component>
          </template>

          <!-- inserindo o footer -->
          <template v-slot:footer>
            <!-- inserindo o botão de adicionar -->
            <button
              type="submit"
              class="btn btn-primary float-end"
              data-bs-toggle="modal"
              data-bs-target="#brandModal"
            >
              Adicionar Nova Marca
            </button>
          </template>
        </card-component>
      </div>
    </div>

    <modal-component id="brandModal" title="Adicionar Nova Marca">
      <!-- inserindo o alert -->
      <template v-slot:alert>
        <!-- alert que será exibido no sucesso da requisição -->
        <alert-component
          type="success"
          text="Cadastro realizado com sucesso!"
          v-if="request_status == 'success'"
        ></alert-component>

        <!-- alert que será exibido no erro da requisição -->
        <alert-component
          type="danger"
          text="Erro durante cadastro:"
          :details="request_errors_messages"
          v-if="request_status == 'error'"
        ></alert-component>
      </template>

      <!-- inserindo o content -->
      <template v-slot:content>
        <!-- incluindo o componente de input para o Nome-->
        <div class="form-group">
          <input-container-component
            label="Nome"
            inputId="inputNewNAME"
            helpID="helpNewNAME"
            helpText="Obrigatório. Informe o nome da marca."
          >
            <!-- foi utilizado o v-model (two-way-databinding) para a variável brandName -->
            <input
              type="text"
              class="form-control"
              id="inputNewNAME"
              placeholder="Nome"
              aria-describedby="helpNewNAME"
              v-model="brandName"
            />
          </input-container-component>
        </div>

        {{ brandName }}

        <!-- espaçamento entre os inputs -->
        <div class="mb-3"></div>

        <!-- incluindo o componente de input para a Imagem-->
        <div class="form-group">
          <input-container-component
            label="Imagem"
            inputId="inputNewIMAGE"
            helpID="helpNewIMAGE"
            helpText="Opcional. Adicione a imagem da marca (formato .png)."
          >
            <!-- como não é possivel utilizar o v-model em inputs de tipo text,
            utilizamos o @change para capturarmos o evento onChange do input
            e dispararmos o método getImage() -->
            <input
              type="file"
              class="form-control"
              id="inputNewIMAGE"
              placeholder="Imagem"
              aria-describedby="helpNewIMAGE"
              @change="getImage($event)"
            />
          </input-container-component>
        </div>

        {{ brandImage }}
      </template>

      <!-- inserindo o footer -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>

        <!-- inserindo o botão de salvar -->
        <button type="button" class="btn btn-primary" @click="save()">
          Salvar
        </button>
      </template>
    </modal-component>
  </div>
</template>

<script>
import AlertComponent from "./AlertComponent.vue";
export default {
  components: { AlertComponent },
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: [],

  // função que retorna o estado inicial das variáveis do componente
  data: function () {
    return {
      // inicializando as variáveis
      brandName: "",
      brandImage: [],
      request_status: "",
      request_errors_messages: [],
    };
  },

  // comportamentos do componente
  methods: {
    // método responsável por popular a variável brandImage
    getImage(e) {
      // popula a variável através do evento recebido
      this.brandImage = e.target.files;
    },
    // método responsável salvar no BD
    save() {
      
      // definindo a url da api
      let url = "http://0.0.0.0:8080/api/v1/brand";

      // definindo as configurações da requisição
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          "Accept": "application/json",
        },
      };

      // definindo os dados a serem inseridos no BD
      let formData = new FormData();
      formData.append("name", this.brandName);
      formData.append("image", this.brandImage[0]);

      console.log(url, config, formData)

      // executando a requisição post
      axios
        .post(url, formData, config)
        // se houve sucesso na requisição
        .then((response) => {

          console.log('sucesso');
          // atribui status de sucesso para exibir o alert
          this.request_status = "success";

          console.log(response);
        })
        // em caso de erros, imprime
        .catch((errors) => {

          console.log('erro');
          // atribui status de error para exibir o alert
          this.request_status = "error";

          // atribui as mensagens de erro para serem utilizadas no alert
          this.request_errors_messages = errors.response;

          console.log(errors.response);
        });
    },
  },
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>
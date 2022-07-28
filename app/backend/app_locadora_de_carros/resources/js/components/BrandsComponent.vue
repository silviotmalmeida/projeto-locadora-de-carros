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
            <table-component
              :data="cleanData"
              :attributes="brandsAttributes"
            ></table-component>
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

        {{ cleanData }}
      </div>
    </div>

    <!-- modal de cadastro -->
    <modal-component id="brandModal" title="Adicionar Nova Marca">
      <!-- inserindo o alert -->
      <template v-slot:alert>
        <!-- alert que será exibido no sucesso da requisição -->
        <alert-component
          type="success"
          text="Cadastro realizado com sucesso!"
          :details="request_messages"
          v-if="request_status == 'success'"
        ></alert-component>

        <!-- alert que será exibido no erro da requisição -->
        <alert-component
          type="danger"
          text="Erro durante cadastro:"
          :details="request_messages"
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
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: [],

  // função que retorna o estado inicial das variáveis do componente
  data: function () {
    return {
      // inicializando as variáveis
      baseUrl: "http://0.0.0.0:8080/api/v1/brand",
      brandName: "",
      brandImage: [],
      request_status: "",
      request_messages: [],
      brandsData: [],
      brandsAttributes: {
        id: { title: "ID", type: "text" },
        name: { title: "Nome", type: "text" },
        image: { title: "Imagem", type: "image" },
      },
    };
  },

  // propriedades computadas do componente
  computed: {
    // token de autorização
    token() {
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

      return token;
    },

    // url corrigida para filtrar na requisição os atributos definidos em brandsAttributes
    listTableUrl() {
      // obtendo a lista de atributos separada por vírgulas
      let atr_brand = Object.keys(this.brandsAttributes).reduce(
        (p, n) => p + "," + n
      );

      // incrementando a url base com os parâmetros de filtro
      let listTableUrl = this.baseUrl + "?atr_brand=" + atr_brand;

      return listTableUrl;
    },

    // dados limpos para envio ao componente table
    cleanData() {
      // inicializando o array de saída
      let cleanData = [];
      // obtendo os atributos desejados a partir da brandsAttributes
      let attributesKeys = Object.keys(this.brandsAttributes);
      // iterando sobre o array original da resposta da requisição
      this.brandsData.map((item, index) => {
        // inicializando o registro vazio
        let cleanItem = {};
        // iterando sobre o array de atributos desejados
        attributesKeys.forEach((att) => {
          // populando o registro
          cleanItem[att] = item[att];
        });
        // populando o array de saída
        cleanData.push(cleanItem);
      });

      return cleanData;
    },
  },

  // comportamentos do componente
  methods: {
    // método que obtém a lista de marcas cadastradas
    getBrands() {
      // definindo as configurações da requisição
      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      // executando a requisição get
      axios
        .get(this.listTableUrl, config)
        // se houve sucesso na requisição
        .then((response) => {
          // popula o array de marcas
          this.brandsData = response.data;

          console.log(this.brandsData);
        })
        // em caso de erros, imprime
        .catch((errors) => {
          console.log(errors.response);
        });
    },

    // método responsável por popular a variável brandImage
    getImage(e) {
      // popula a variável através do evento recebido
      this.brandImage = e.target.files;
    },
    // método responsável salvar no BD
    save() {
      // definindo as configurações da requisição
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      // definindo os dados a serem inseridos no BD
      let formData = new FormData();
      // adiciona o nome na requisição
      formData.append("name", this.brandName);
      // se alguma imagem for selecionada
      if (this.brandImage[0]) {
        // adiciona a imagem na requisição
        formData.append("image", this.brandImage[0]);
      }

      console.log(this.baseUrl, config, formData);

      // executando a requisição post
      axios
        .post(this.baseUrl, formData, config)
        // se houve sucesso na requisição
        .then((response) => {
          // atribui status de sucesso para exibir o alert
          this.request_status = "success";

          // atribui as mensagens de sucesso para serem utilizadas no alert
          this.request_messages.push(
            "ID do novo registro: " + response.data.id
          );
          console.log(response);
        })
        // em caso de erros, imprime
        .catch((errors) => {
          // atribui status de error para exibir o alert
          this.request_status = "error";

          // atribui as mensagens de erro para serem utilizadas no alert
          // adicionando a mensagem geral
          this.request_messages.push(errors.response.data.message);

          // adicionando as demais mensagens
          // converte o objeto em array
          Object.entries(errors.response.data.errors)
            // executa um foreach pelo array
            .forEach((entry) => {
              // popula o array de mensagens que será passado para o alert
              const [key, value] = entry;
              this.request_messages.push(value[0]);
            });
          console.log(errors.response);
        });
    },
  },
  // comportamentos automáticos após a montagem do componente
  mounted() {
    // carrega a lista de marcas cadastradas
    this.getBrands();
  },
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

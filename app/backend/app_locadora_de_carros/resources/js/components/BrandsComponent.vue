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
                  <!-- o valor do input está ligado à variável searchId -->
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    placeholder="ID"
                    aria-describedby="helpID"
                    v-model="searchId"
                  />
                </input-container-component>

                <!-- se existir filtro aplicado, imprime o valor -->
                <span v-if="searchData.id" class="form-text"
                  >Aplicado: {{ searchData.id }}</span
                >
              </div>

              <div class="col mb-3">
                <!-- incluindo o componente de input para o Nome-->
                <input-container-component
                  label="Nome"
                  inputId="inputNAME"
                  helpID="helpNAME"
                  helpText="Opcional. Informe o nome da marca."
                >
                  <!-- o valor do input está ligado à variável searchName -->
                  <input
                    type="text"
                    class="form-control"
                    id="inputNAME"
                    placeholder="Nome"
                    aria-describedby="helpNAME"
                    v-model="searchName"
                  />
                </input-container-component>

                <!-- se existir filtro aplicado, imprime o valor -->
                <span v-if="searchData.name" class="form-text"
                  >Aplicado: {{ searchData.name }}</span
                >
              </div>
            </div>
          </template>

          <!-- inserindo o footer -->
          <template v-slot:footer>
            <!-- inserindo o botão de pesquisar -->
            <button
              type="submit"
              class="btn btn-primary float-end"
              @click="search()"
            >
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
              :btn_read="{
                visible: true,
                dataToogle: 'modal',
                dataTarget: '#brandModalRead',
              }"
              :btn_update="{
                visible: false,
                dataToogle: 'modal',
                dataTarget: '#brandModalUpdate',
              }"
              :btn_delete="{
                visible: true,
                dataToogle: 'modal',
                dataTarget: '#brandModalDelete',
              }"
            ></table-component>
          </template>

          <!-- inserindo o footer -->
          <template v-slot:footer>
            <!-- inserindo a paginação -->
            <pagination-component>
              <!-- iterando sobre os links retornados na requisição com paginação -->
              <template v-for="(value, key) in brandsData.links">
                <!-- se o link for diferente null, não renderiza o item -->
                <!-- se o active for true, adiciona a classe active -->
                <li
                  v-if="value.url != null"
                  :key="key"
                  :class="value.active ? 'page-item active' : 'page-item'"
                  @click="paginate(value)"
                >
                  <!-- inserindo o link -->
                  <!-- o texto dos botões será traduzido pela função translatePagination() -->
                  <a
                    class="page-link"
                    v-html="translatePagination(value.label)"
                  ></a>
                </li>
              </template>
            </pagination-component>

            <!-- inserindo o botão de adicionar -->
            <button
              type="submit"
              class="btn btn-primary float-end"
              data-bs-toggle="modal"
              data-bs-target="#brandModalCreate"
              @click="cleanCreateForm()"
            >
              Adicionar Nova Marca
            </button>
          </template>
        </card-component>
      </div>
    </div>

    <!-- modal de cadastro -->
    <modal-component id="brandModalCreate" title="Adicionar Nova Marca">
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
            <!-- como não é possivel utilizar o v-model em inputs de tipo file,
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
        <button type="button" class="btn btn-primary" @click="create()">
          Salvar
        </button>
      </template>
    </modal-component>

    <!-- modal de visualização -->
    <modal-component id="brandModalRead" title="Detalhes marca">
      <!-- inserindo o content -->
      <template v-slot:content>
        <template v-if="brandData.id">
          <input-container-component label="ID" inputId="inputReadID">
            <input
              type="text"
              class="form-control"
              id="inputReadID"
              :value="brandData.id"
              disabled
            />
          </input-container-component>

          <input-container-component label="Nome" inputId="inputReadNAME">
            <input
              type="text"
              class="form-control"
              id="inputReadNAME"
              :value="brandData.name"
              disabled
            />
          </input-container-component>

          <input-container-component label="Imagem">
            <img :src="'/storage/' + brandData.image" width="30" height="30" />
          </input-container-component>

          <input-container-component
            label="Data de criação"
            inputId="inputReadCREATEDAT"
          >
            <!-- exibindo a data formatada -->
            <input
              type="text"
              class="form-control"
              id="inputReadCREATEDAT"
              :value="
                new Intl.DateTimeFormat('pt-BR', {
                  day: 'numeric',
                  month: 'long',
                  year: 'numeric',
                }).format(new Date(brandData.created_at))
              "
              disabled
            />
          </input-container-component>

          <input-container-component
            label="Data de atualização"
            inputId="inputReadUPDATEDAT"
          >
            <!-- exibindo a data formatada -->
            <input
              type="text"
              class="form-control"
              id="inputReadUPDATEDAT"
              :value="
                new Intl.DateTimeFormat('pt-BR', {
                  day: 'numeric',
                  month: 'long',
                  year: 'numeric',
                }).format(new Date(brandData.updated_at))
              "
              disabled
            />
          </input-container-component>

          <template v-if="brandData.types.length">
            <input-container-component label="Modelos associados">
              <ul v-for="(value, key) in brandData.types" :key="key">
                <!-- se o link for diferente null, não renderiza o item -->
                <!-- se o active for true, adiciona a classe active -->
                <li>{{ value.name }}</li>
              </ul>
            </input-container-component>
          </template>
        </template>
      </template>

      <!-- inserindo o footer -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <!-- modal de remoção -->
    <modal-component id="brandModalDelete" title="Remover marca">
      <!-- inserindo o content -->
      <template v-slot:content>
        <template v-if="brandData.id">
          <input-container-component label="ID" inputId="inputReadID">
            <input
              type="text"
              class="form-control"
              id="inputReadID"
              :value="brandData.id"
              disabled
            />
          </input-container-component>

          <input-container-component label="Nome" inputId="inputReadNAME">
            <input
              type="text"
              class="form-control"
              id="inputReadNAME"
              :value="brandData.name"
              disabled
            />
          </input-container-component>
        </template>
      </template>

      <!-- inserindo o footer -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>

        <!-- inserindo o botão de remover -->
        <button
          v-if="brandData.id"
          type="button"
          class="btn btn-danger"
          @click="del()"
        >
          Remover
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
      paginationUrl: "",
      brandName: "",
      brandImage: [],
      request_status: "",
      request_messages: [],
      brandsData: [],
      cleanData: [],
      brandsAttributes: {
        id: { title: "ID", type: "text" },
        name: { title: "Nome", type: "text" },
        image: { title: "Imagem", type: "image" },
      },
      brandsPerPage: 5,
      searchId: "",
      searchName: "",
      searchData: { id: "", name: "" },
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

    // informações filtradas da marca referente ao id armazenado na store
    // sempre que a store é atualizada, esta variável irá acompanhar
    brandData() {
      // obtendo os dados da marca referente ao id atual da store
      let brandData = this.getBrand(this.$store.state.selectedBrand);

      return brandData;
    },
  },

  // comportamentos do componente
  methods: {
    // método que gera a url corrigida para filtrar na requisição os atributos definidos em brandsAttributes
    // bem como na definição da paginação
    customizeListUrl() {
      // obtendo a lista de atributos separada por vírgulas
      let atr_brand = Object.keys(this.brandsAttributes).reduce(
        (p, n) => p + "," + n
      );

      // inicializando a url customizada
      // se a url de paginação estiver definida, utiliza a mesma
      // senão utiliza a url base
      let customListUrl = this.paginationUrl
        ? this.paginationUrl
        : this.baseUrl;

      // se na url já existir a ?, refere-se a url com o atributo page incluído
      // logo os próximos atributos deverão ser separados por &
      // senão inclui a ?
      customListUrl.includes("?")
        ? (customListUrl += "&")
        : (customListUrl += "?");

      // incrementando a url base com os parâmetros de filtro e quantidades de item por página
      customListUrl +=
        // "atr_brand=" + atr_brand + "&paginate=" + this.brandsPerPage;
        "&paginate=" + this.brandsPerPage;

      // inicializando a variável com os filtros vazia
      let filter = "";

      // iterando sobre o array de filtros
      for (let key in this.searchData) {
        // se o filtro foi preenchido
        if (this.searchData[key]) {
          // se não for o primeiro filtro, insere o separador ;
          if (filter) filter += ";";

          // considera o filtro conforme formatação key:like:%value%
          filter += key + ":like:%" + this.searchData[key] + "%";
        }
      }

      // se existirem filtros, incrementa a url
      if (filter) customListUrl += "&filter=" + filter;

      return customListUrl;
    },

    // limpa os dados para envio ao componente table
    clearData() {
      // inicializando o array de saída
      let cleanData = [];
      // obtendo os atributos desejados a partir da brandsAttributes
      let attributesKeys = Object.keys(this.brandsAttributes);
      // iterando sobre o array original da resposta da requisição
      this.brandsData.data.map((item, index) => {
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

    // método que obtém a lista de marcas cadastradas
    getBrands() {
      // definindo as configurações da requisição
      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      // obtendo a url customizada para a listagem
      let customListUrl = this.customizeListUrl();

      console.log(customListUrl);

      // executando a requisição get
      axios
        .get(customListUrl, config)
        // se houve sucesso na requisição
        .then((response) => {
          // popula o array de marcas completo
          this.brandsData = response.data;

          // popula o array de marcas limpo para envio ao componente table
          this.cleanData = this.clearData();

          console.log(this.brandsData);
          console.log(this.cleanData);
        })
        // em caso de erros, imprime
        .catch((errors) => {
          console.log(errors.response);
        });
    },

    // método responsável por filtrar o array brandsData pelo id da brand
    getBrand(id) {
      // iniciando o objeto de saída
      let brandData = {};

      // se um id for fornecido
      if (id >= 0) {
        // convertendo o objeto em array
        Object.entries(this.brandsData.data)
          // executa um foreach(some) pelo array
          .some((entry) => {
            // obtendo o índice e o objetao atual da iteração
            const [index, brand] = entry;

            // se o id do objeto corresponder ao recebido pro argumento
            if (brand.id == id) {
              // atribui ao objeto de saída
              brandData = brand;

              // saindo do laço (break)
              return true;
            }
          });
      }

      return brandData;
    },

    // método que realiza a paginação
    paginate(v) {
      // atualiza a pagination url com a página clicada
      this.paginationUrl = v.url;

      // realiza a busca
      this.getBrands();
    },

    // método que realiza a busca
    search() {
      // atualizando os filtros
      this.searchData.id = this.searchId;
      this.searchData.name = this.searchName;

      // removendo o atributo de pagina
      this.paginationUrl = "";

      // realiza a busca
      this.getBrands();
    },

    // método que traduz os botões de paginação
    translatePagination(text) {
      // se o texto for Previous
      if (text.includes("Previous"))
        // traduz o texto
        return text.replace("Previous", "Anterior");

      // se o texto for Next
      if (text.includes("Next"))
        // traduz o texto
        return text.replace("Next", "Próxima");

      // para os demais casos não traduz
      return text;
    },

    // método que limpa o formulário de create
    cleanCreateForm() {
      // limpando o formulário
      this.brandName = "";
      this.brandImage = [];
      this.cleanMessages();
    },

    // método que limpa o status e array de mensagens
    cleanMessages() {
      this.request_status = "";
      this.request_messages = [];
    },

    // método responsável por popular a variável brandImage
    getImage(e) {
      // popula a variável através do evento recebido
      this.brandImage = e.target.files;
    },
    // método responsável salvar no BD
    create() {
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

    // método responsável por remover do BD
    del() {
      // exibindo o diálogo de confirmação
      let confirmation = confirm("Tem certeza que deseja remover o registro?");

      // se não houver confirmação, cancela
      if (!confirmation) return false;

      // definindo as configurações da requisição
      let config = {
        headers: {
          Accept: "application/json",
          Authorization: this.token,
        },
      };

      // definindo os dados a serem enviados pela requisição
      let formData = new FormData();
      // alterando o método para que o Laravel entenda que trata-se de delete
      formData.append("_method", "delete");

      // obtendo a url customizada para a remoção
      let url = this.baseUrl + "/" + this.$store.state.selectedBrand;

      // executando a requisição post
      axios
        .post(url, formData, config)
        // se houve sucesso na requisição
        .then((response) => {
          // atribui status de sucesso para exibir o alert
          this.request_status = "success";

          // atribui as mensagens de sucesso para serem utilizadas no alert
          this.request_messages.push("Registro removido com sucesso!");

          this.getBrands();

          console.log(response);
        })
        // em caso de erros, imprime
        .catch((errors) => {
          // atribui status de error para exibir o alert
          this.request_status = "error";

          /// atribui as mensagens de erro para serem utilizadas no alert
          this.request_messages.push("O registro não pôde ser removido!");

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

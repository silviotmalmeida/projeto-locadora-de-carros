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
                visible: true,
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
    <modal-component id="brandModalCreate" title="Criar Nova Marca">
      <!-- inserindo o alert -->
      <template v-slot:alert>
        <!-- alert que será exibido no sucesso da requisição -->
        <alert-component
          type="success"
          text="Cadastro realizado com sucesso!"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'success'"
        ></alert-component>

        <!-- alert que será exibido no erro da requisição -->
        <alert-component
          type="danger"
          text="Erro durante cadastro:"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'error'"
        ></alert-component>
      </template>

      <!-- inserindo o content -->
      <!-- em caso de sucesso na requisição, não será renderizado -->
      <template v-slot:content v-if="$store.state.request_status != 'success'">
        <!-- incluindo o componente de input para o Nome-->
        <div class="form-group">
          <input-container-component
            label="Nome"
            inputId="inputCreateNAME"
            helpID="helpCreateNAME"
            helpText="Obrigatório. Informe o nome da marca."
          >
            <!-- foi utilizado o v-model (two-way-databinding) para a variável brandCreateName -->
            <input
              type="text"
              class="form-control"
              id="inputCreateNAME"
              placeholder="Nome"
              aria-describedby="helpCreateNAME"
              v-model="brandCreateName"
            />
          </input-container-component>
        </div>

        <!-- espaçamento entre os inputs -->
        <div class="mb-3"></div>

        <!-- incluindo o componente de input para a Imagem-->
        <div class="form-group">
          <input-container-component
            label="Imagem"
            inputId="inputCreateIMAGE"
            helpID="helpCreateIMAGE"
            helpText="Opcional. Adicione a imagem da marca (formato .png)."
          >
            <!-- como não é possivel utilizar o v-model em inputs de tipo file,
            utilizamos o @change para capturarmos o evento onChange do input
            e dispararmos o método getImage() -->
            <input
              type="file"
              class="form-control"
              id="inputCreateIMAGE"
              placeholder="Imagem"
              aria-describedby="helpCreateIMAGE"
              @change="getImage($event)"
            />
          </input-container-component>
        </div>
      </template>

      <!-- inserindo o footer -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>

        <!-- inserindo o botão de salvar -->
        <!-- em caso de sucesso na requisição, não será renderizado -->
        <button
          v-if="$store.state.request_status != 'success'"
          type="button"
          class="btn btn-primary"
          @click="create()"
        >
          Criar
        </button>
      </template>
    </modal-component>

    <!-- modal de visualização -->
    <modal-component id="brandModalRead" title="Detalhes marca">
      <!-- inserindo o content -->
      <!-- a ser renderizado somente se algum id estiver setado na store -->
      <template v-slot:content v-if="brandData.id">
        <!-- exibindo o id -->
        <input-container-component label="ID" inputId="inputReadID">
          <input
            type="text"
            class="form-control"
            id="inputReadID"
            :value="brandData.id"
            disabled
          />
        </input-container-component>

        <!-- exibindo o nome -->
        <input-container-component label="Nome" inputId="inputReadNAME">
          <input
            type="text"
            class="form-control"
            id="inputReadNAME"
            :value="brandData.name"
            disabled
          />
        </input-container-component>

        <!-- exibindo a imagem -->
        <input-container-component label="Imagem">
          <img :src="'/storage/' + brandData.image" width="30" height="30" />
        </input-container-component>

        <!-- exibindo a data de criação -->
        <input-container-component
          label="Data de criação"
          inputId="inputReadCREATEDAT"
        >
          <!-- exibindo a data formatada com filtro -->
          <input
            type="text"
            class="form-control"
            id="inputReadCREATEDAT"
            :value="brandData.created_at | formatDateTime"
            disabled
          />
        </input-container-component>

        <!-- exibindo a data de atualização -->
        <input-container-component
          label="Data de atualização"
          inputId="inputReadUPDATEDAT"
        >
          <!-- exibindo a data formatada com filtro -->
          <input
            type="text"
            class="form-control"
            id="inputReadUPDATEDAT"
            :value="brandData.updated_at | formatDateTime"
            disabled
          />
        </input-container-component>

        <!-- se existirem modelos associados, lista-os -->
        <template v-if="brandData.types.length">
          <input-container-component label="Modelos associados">
            <!-- iterando sobre o array de modelos -->
            <ul v-for="(value, key) in brandData.types" :key="key">
              <!-- exibindo o nome do modelo -->
              <li>{{ value.name }}</li>
            </ul>
          </input-container-component>
        </template>
      </template>

      <!-- inserindo o footer -->
      <!-- a ser renderizado somente se algum id estiver setado na store -->
      <template v-slot:footer v-if="brandData.id">
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>
      </template>
    </modal-component>

    <!-- modal de remoção -->
    <modal-component id="brandModalDelete" title="Remover marca">
      <!-- inserindo o alert -->
      <template v-slot:alert>
        <!-- alert que será exibido no sucesso da requisição -->
        <alert-component
          type="success"
          text="Registro removido com sucesso!"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'success'"
        ></alert-component>

        <!-- alert que será exibido no erro da requisição -->
        <alert-component
          type="danger"
          text="Erro durante remoção:"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'error'"
        ></alert-component>
      </template>
      <!-- inserindo o content -->
      <!-- a ser renderizado somente se algum id estiver setado na store -->
      <!-- em caso de sucesso na requisição, não será renderizado -->
      <template
        v-slot:content
        v-if="brandData.id && $store.state.request_status != 'success'"
      >
        <!-- exibindo o id -->
        <input-container-component label="ID" inputId="inputDeleteID">
          <input
            type="text"
            class="form-control"
            id="inputDeleteID"
            :value="brandData.id"
            disabled
          />
        </input-container-component>

        <!-- exibindo o nome -->
        <input-container-component label="Nome" inputId="inputDeleteNAME">
          <input
            type="text"
            class="form-control"
            id="inputDeleteNAME"
            :value="brandData.name"
            disabled
          />
        </input-container-component>
      </template>

      <!-- inserindo o footer -->
      <!-- a ser renderizado somente se algum id estiver setado na store -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>

        <!-- inserindo o botão de remover -->
        <!-- a ser renderizado somente se algum id estiver setado na store -->
        <!-- em caso de sucesso na requisição, não será renderizado -->
        <button
          v-if="brandData.id && $store.state.request_status != 'success'"
          type="button"
          class="btn btn-danger"
          @click="del()"
        >
          Remover
        </button>
      </template>
    </modal-component>

    <!-- modal de atualização -->
    <modal-component id="brandModalUpdate" title="Atualizar Marca">
      <!-- inserindo o alert -->
      <template v-slot:alert>
        <!-- alert que será exibido no sucesso da requisição -->
        <alert-component
          type="success"
          text="Atualização realizada com sucesso!"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'success'"
        ></alert-component>

        <!-- alert que será exibido no erro da requisição -->
        <alert-component
          type="danger"
          text="Erro durante atualização:"
          :details="$store.state.request_messages"
          v-if="$store.state.request_status == 'error'"
        ></alert-component>
      </template>

      <!-- inserindo o content -->
      <!-- a ser renderizado somente se algum id estiver setado na store -->
      <!-- em caso de sucesso na requisição, não será renderizado -->
      <template
        v-slot:content
        v-if="brandData.id && $store.state.request_status != 'success'"
      >
        <!-- incluindo o componente de input para o Nome-->
        <div class="form-group">
          <input-container-component
            label="Nome"
            inputId="inputUpdateNAME"
            helpID="helpUpdateNAME"
            helpText="Obrigatório. Informe o nome da marca."
          >
            <!-- foi utilizado o v-model (two-way-databinding) para a variável brandData.name -->
            <input
              type="text"
              class="form-control"
              id="inputUpdateNAME"
              placeholder="Nome"
              aria-describedby="helpUpdateNAME"
              v-model="brandData.name"
            />
          </input-container-component>
        </div>

        <!-- espaçamento entre os inputs -->
        <div class="mb-3"></div>

        <!-- incluindo o componente de input para a Imagem-->
        <div class="form-group">
          <input-container-component
            label="Imagem"
            inputId="inputUpdateIMAGE"
            helpID="helpUpdateIMAGE"
            helpText="Opcional. Adicione a imagem da marca (formato .png)."
          >
            <!-- exibindo a imagem -->
            <img :src="'/storage/' + brandData.image" width="30" height="30" />

            <!-- como não é possivel utilizar o v-model em inputs de tipo file,
            utilizamos o @change para capturarmos o evento onChange do input
            e dispararmos o método getImage() -->
            <input
              type="file"
              class="form-control"
              id="inputUpdateIMAGE"
              placeholder="Imagem"
              aria-describedby="helpUpdateIMAGE"
              @change="updateImage($event)"
            />
          </input-container-component>
        </div>
      </template>

      <!-- inserindo o footer -->
      <template v-slot:footer>
        <!-- inserindo o botão de fechar -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Fechar
        </button>

        <!-- inserindo o botão de salvar -->
        <!-- a ser renderizado somente se algum id estiver setado na store -->
        <!-- em caso de sucesso na requisição, não será renderizado -->
        <button
          v-if="brandData.id && $store.state.request_status != 'success'"
          type="button"
          class="btn btn-success"
          @click="update()"
        >
          Atualizar
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
      // endpoint principal
      baseUrl: "http://0.0.0.0:8080/api/v1/brand",
      // enpoint com paginação, inicia vazio e é atribuído ao clicar nos botões de paginação
      paginationUrl: "",
      // atributo nome no formulário de criação
      brandCreateName: "",
      // atributo imagem no formulário de criação
      brandCreateImage: [],
      // atributo imagem no formulário de atualização
      brandUpdateImage: [],
      // dados completos recebidos da api
      brandsData: [],
      // dados filtrados para exibição na listagem
      cleanData: [],
      // atributos a serem exibidos na tabela de listagem
      brandsAttributes: {
        id: { title: "ID", type: "text" },
        name: { title: "Nome", type: "text" },
        image: { title: "Imagem", type: "image" },
        updated_at: { title: "Última atualização", type: "datetime" },
      },
      // quantidade de registros por página na listagem
      brandsPerPage: 5,
      // atributo id no formulário de busca
      searchId: "",
      // atributo nome no formulário de busca
      searchName: "",
      // atributos a serem considerados na busca
      searchData: { id: "", name: "" },
    };
  },

  // propriedades computadas do componente
  computed: {
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

      // incrementando a url base com a quantidade de itens por página
      customListUrl += "&paginate=" + this.brandsPerPage;

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
      // obtendo a url customizada para a listagem
      let customListUrl = this.customizeListUrl();

      // executando a requisição get
      axios
        .get(customListUrl)
        // se houve sucesso na requisição
        .then((response) => {
          // popula o array de marcas completo
          this.brandsData = response.data;

          // popula o array de marcas limpo para envio ao componente table
          this.cleanData = this.clearData();
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
      this.brandCreateName = "";
      this.brandCreateImage = [];

      // limpando o status e array de mensagens da store
      this.cleanMessages();
    },

    // método que limpa o status e array de mensagens da store
    cleanMessages() {
      this.$store.state.request_status = "";
      this.$store.state.request_messages = [];
    },

    // método responsável por popular a variável brandCreateImage
    getImage(e) {
      // popula a variável através do evento recebido
      this.brandCreateImage = e.target.files;
    },

    // método responsável por atualizar a variável brandUpdateImage
    updateImage(e) {
      // popula a variável através do evento recebido
      this.brandUpdateImage = e.target.files;
    },

    // método responsável salvar no BD
    create() {
      // definindo as configurações da requisição
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      // definindo os dados a serem inseridos no BD
      let formData = new FormData();
      // adiciona o nome na requisição
      formData.append("name", this.brandCreateName);
      // se alguma imagem for selecionada
      if (this.brandCreateImage[0]) {
        // adiciona a imagem na requisição
        formData.append("image", this.brandCreateImage[0]);
      }

      // executando a requisição post
      axios
        .post(this.baseUrl, formData, config)
        // se houve sucesso na requisição
        .then((response) => {
          // atribui status de sucesso para exibir o alert
          this.$store.state.request_status = "success";

          // limpando o array de erros
          this.$store.state.request_messages = [];

          // atribui as mensagens de sucesso para serem utilizadas no alert
          this.$store.state.request_messages.push(
            "ID do novo registro: " + response.data.id
          );

          // removendo o atributo de pagina
          this.paginationUrl = "";

          // atualiza a lista de marcas
          this.getBrands();
        })
        // em caso de erros, imprime
        .catch((errors) => {
          // atribui status de error para exibir o alert
          this.$store.state.request_status = "error";

          // limpando o array de erros
          this.$store.state.request_messages = [];

          // atribui as mensagens de erro para serem utilizadas no alert
          // adicionando a mensagem geral
          this.$store.state.request_messages.push(errors.response.data.message);

          // adicionando as demais mensagens
          // converte o objeto em array
          Object.entries(errors.response.data.errors)
            // executa um foreach pelo array
            .forEach((entry) => {
              // popula o array de mensagens que será passado para o alert
              const [key, value] = entry;
              this.$store.state.request_messages.push(value[0]);
            });
        });
    },

    // método responsável por remover do BD
    del() {
      // exibindo o diálogo de confirmação
      let confirmation = confirm("Tem certeza que deseja remover o registro?");

      // se não houver confirmação, cancela
      if (!confirmation) return false;

      // definindo os dados a serem enviados pela requisição
      let formData = new FormData();
      // alterando o método para que o Laravel entenda que trata-se de delete
      formData.append("_method", "delete");

      let brandId = this.$store.state.selectedBrand;

      // obtendo a url customizada para a atualização
      let url = this.baseUrl + "/" + brandId;

      // executando a requisição post
      axios
        .post(url, formData)
        // se houve sucesso na requisição
        .then((response) => {
          // atribui status de sucesso para exibir o alert
          this.$store.state.request_status = "success";

          // atribui as mensagens de sucesso para serem utilizadas no alert
          this.$store.state.request_messages.push(
            "Registro " + brandId + " removido com sucesso!"
          );

          // removendo o atributo de pagina
          this.paginationUrl = "";

          // atualiza a lista de marcas
          this.getBrands();
        })
        // em caso de erros, imprime
        .catch((errors) => {
          // atribui status de error para exibir o alert
          this.$store.state.request_status = "error";

          // limpando o array de erros
          this.$store.state.request_messages = [];

          // atribui as mensagens de erro para serem utilizadas no alert
          // adicionando a mensagem geral
          this.$store.state.request_messages.push(errors.response.data.msg);
        });
    },

    // método responsável atualizar no BD
    update() {
      // definindo as configurações da requisição
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      // definindo os dados a serem inseridos no BD
      let formData = new FormData();
      // adiciona o nome na requisição
      formData.append("name", this.brandData.name);
      // se alguma imagem for selecionada
      if (this.brandUpdateImage[0]) {
        // adiciona a imagem na requisição
        formData.append("image", this.brandUpdateImage[0]);
      }
      // alterando o método para que o Laravel entenda que trata-se de patch
      formData.append("_method", "patch");

      let brandId = this.$store.state.selectedBrand;

      // obtendo a url customizada para a atualização
      let url = this.baseUrl + "/" + brandId;

      // executando a requisição post
      axios
        .post(url, formData, config)
        // se houve sucesso na requisição
        .then((response) => {
          // atribui status de sucesso para exibir o alert
          this.$store.state.request_status = "success";

          // limpando o array de erros
          this.$store.state.request_messages = [];

          // atribui as mensagens de sucesso para serem utilizadas no alert
          this.$store.state.request_messages.push(
            "Registro: " + response.data.id + " atualizado com sucesso!"
          );

          // removendo o atributo de pagina
          this.paginationUrl = "";

          // limpando a variável de imagem
          this.brandUpdateImage = [];

          // atualiza a lista de marcas
          this.getBrands();
        })
        // em caso de erros, imprime
        .catch((errors) => {
          // atribui status de error para exibir o alert
          this.$store.state.request_status = "error";

          // limpando o array de erros
          this.$store.state.request_messages = [];

          // atribui as mensagens de erro para serem utilizadas no alert
          // adicionando a mensagem geral
          this.$store.state.request_messages.push(errors.response.data.message);

          // adicionando as demais mensagens
          // converte o objeto em array
          Object.entries(errors.response.data.errors)
            // executa um foreach pelo array
            .forEach((entry) => {
              // popula o array de mensagens que será passado para o alert
              const [key, value] = entry;
              this.$store.state.request_messages.push(value[0]);
            });
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

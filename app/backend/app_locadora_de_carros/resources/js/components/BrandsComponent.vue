<template>
  <!-- trecho de código que representa o html do componente -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- card de busca -->
        <div class="card mb-3">
          <div class="card-header">Busca de Marcas</div>
          <!-- inserindo o formulário de busca -->
          <div class="card-body">
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
                  inputType="text"
                  inputPlaceholder="Nome"
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
          </div>

          <!-- inserindo o botão de pesquisar -->
          <div class="card-footer">
            <button type="submit" class="btn btn-primary float-end">
              Pesquisar
            </button>
          </div>
        </div>

        <!-- card de listagem -->
        <div class="card">
          <div class="card-header">Listagem de Marcas</div>
          <!-- inserindo os resultados da busca -->
          <div class="card-body">

            <table-component></table-component>

          </div>

          <!-- inserindo o botão de adicionar -->
          <div class="card-footer">
            <button type="submit" class="btn btn-primary float-end">
              Adicionar Novo
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import InputContainerComponent from "./InputContainerComponent.vue";
export default {
  components: { InputContainerComponent },
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: [""],

  // função que retorna o estado inicial das variáveis do componente
  data: function () {
    return {
      // inicializando as variáveis
    };
  },

  // comportamentos do componente
  methods: {},
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

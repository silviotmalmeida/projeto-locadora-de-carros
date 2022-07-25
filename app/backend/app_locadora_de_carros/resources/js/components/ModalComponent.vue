<template>
  <!-- trecho de código que representa o html do componente -->

  <!-- inserindo o modal -->
  <div
    class="modal fade"
    :id="id"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- cabeçalho do modal -->
        <div class="modal-header">
          <!-- inserindo o título do modal -->
          <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
          <!-- inserindo o X de fechar -->
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>

        <!-- inserindo o corpo do modal -->
        <div class="modal-body">
          <!-- conteúdo a ser inserido no corpo da tag, através de um template v-slot -->
          <slot name="alert"></slot>

          <!-- conteúdo a ser inserido no corpo da tag, através de um template v-slot -->
          <slot name="content"></slot>
        </div>

        <!-- inserindo o rodapé do modal -->
        <div class="modal-footer">
          <!-- conteúdo a ser inserido no corpo da tag, através de um template v-slot -->
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["id", "title"],

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

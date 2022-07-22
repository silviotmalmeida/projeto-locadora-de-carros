<template>
  <!-- trecho de código que representa o html do componente -->

  <div class="card mb-3">
    <!-- cabeçalho do card -->
    <div class="card-header">{{ title }}</div>
    <!-- corpo do card -->
    <div class="card-body">
      <!-- conteúdo a ser inserido no corpo da tag, através de um template v-slot -->
      <slot name="content"></slot>
    </div>
    <!-- rodapé do card -->
    <div class="card-footer">
      <!-- conteúdo a ser inserido no corpo da tag, através de um template v-slot -->
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["title"],

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

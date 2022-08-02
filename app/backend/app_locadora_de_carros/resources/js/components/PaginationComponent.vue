<template>
  <!-- trecho de código que representa o html do componente -->

  <nav aria-label="Page navigation example">
    <ul class="pagination" style="cursor: pointer">
      <slot></slot>
    </ul>
  </nav>
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
    };
  },

  // propriedades computadas do componente
  computed: {},

  // comportamentos do componente
  methods: {},
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

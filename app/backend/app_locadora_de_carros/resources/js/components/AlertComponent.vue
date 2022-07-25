<template>
  <!-- trecho de código que representa o html do componente -->

  <div :class="style" role="alert">
    {{ text }}
    <hr />
    <!-- {{ details.data.message }} -->
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["type", "text", "details"],

  // propriedades computadas do componente
  computed: {
    style() {
      return "alert alert-" + this.type;
    },
  },

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

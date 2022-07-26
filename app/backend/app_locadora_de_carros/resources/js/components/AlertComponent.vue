<template>
  <!-- trecho de código que representa o html do componente -->

  <!-- desenhando o alert do tipo correspondente -->
  <div :class="style" role="alert">
    <!-- imprimindo a mensagem -->
    {{ text }}

    <!-- se foram enviados detalhes de erro -->
    <span v-if="details">
      <!-- desenha linha horizontal -->
      <hr />

      <!-- imprimie lista de erros -->
      <ul>
        <li v-for="(value, key) in details" :key="key">{{ value }}</li>
      </ul>
    </span>
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

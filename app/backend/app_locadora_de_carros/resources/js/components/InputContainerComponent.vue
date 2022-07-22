<template>
  <!-- trecho de código que representa o html do componente -->

  <div class="form-group">
    <!-- renderizando a label -->
    <label :for="inputId" class="form-label">{{ label }}</label>

    <!-- conteúdo a ser inserido no corpo da tag, no caso o input -->
    <slot></slot>

    <!-- renderizando o texto de ajuda -->
    <div :id="helpID" class="form-text">{{ helpText }}</div>
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["inputId", "label", "helpID", "helpText"],

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

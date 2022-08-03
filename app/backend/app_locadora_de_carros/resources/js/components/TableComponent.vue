<template>
  <!-- trecho de código que representa o html do componente -->

  <div>
    <!-- desenhando a tabela -->
    <table class="table table-hover">
      <!-- desenhando o cabeçalho -->
      <thead>
        <tr>
          <!-- iterando o array de atributos a serem considerados -->
          <th v-for="(item, index) in attributes" :key="index" scope="col">
            {{ item.title }}
          </th>
          <!-- a coluna de botões só será desenhada caso algum botão exista -->
          <th v-if="btn_read || btn_update || btn_delete"></th>
        </tr>
      </thead>
      <tbody>
        <!-- iterando o array de dados para imprimir os registros -->
        <tr v-for="(item, index) in data" :key="index">
          <!-- iterando sobre os registros -->
          <td v-for="(value, key) in item" :key="key + randomKey()">
            <!-- se o tipo do atributo for image, renderiza uma tag img com a imagem -->
            <template v-if="attributes[key].type === 'image'"
              ><img :src="'/storage/' + value" width="30" height="30"
            /></template>
            <!-- se o tipo do atributo for text, imprime o texto -->
            <template v-else-if="attributes[key].type === 'text'">{{
              value
            }}</template>
          </td>
          <!-- a coluna de botões só será desenhada caso algum botão exista -->
          <td v-if="btn_read || btn_update || btn_delete">
            <!-- botão de read -->
            <button v-if="btn_read" class="btn btn-outline-primary btn-sm">
              Visualizar
            </button>
            <!-- botão de update -->
            <button v-if="btn_update" class="btn btn-outline-secondary btn-sm">
              Editar
            </button>
            <!-- botão de delete -->
            <button v-if="btn_delete" class="btn btn-outline-danger btn-sm">
              Remover
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  // propriedades a serem recebidas para criação do componente
  // as propriedades são definidas como atributos na tag do componente
  props: ["data", "attributes", "btn_read", "btn_update", "btn_delete"],

  // função que retorna o estado inicial das variáveis do componente
  data: function () {
    return {
      // inicializando as variáveis
    };
  },

  // propriedades computadas do componente
  computed: {},

  // comportamentos do componente
  methods: {
    randomKey() {
      return (
        new Date().getTime() + Math.floor(Math.random() * 10000).toString()
      );
    },
  },
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

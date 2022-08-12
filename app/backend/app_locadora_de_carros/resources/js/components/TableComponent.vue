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
          <th
            v-if="btn_read.visible || btn_update.visible || btn_delete.visible"
          ></th>
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
            <!-- se o tipo do atributo for datetime, imprime a data formatada com filtro -->
            <template v-else-if="attributes[key].type === 'datetime'">{{
              value | formatDateTime
            }}</template>
          </td>
          <!-- a coluna de botões só será desenhada caso algum botão exista -->
          <td
            v-if="btn_read.visible || btn_update.visible || btn_delete.visible"
          >
            <!-- botão de read -->
            <button
              v-if="btn_read.visible"
              class="btn btn-outline-primary btn-sm"
              :data-bs-toggle="btn_read.dataToogle"
              :data-bs-target="btn_read.dataTarget"
              @click="setStore(item)"
            >
              Visualizar
            </button>
            <!-- botão de update -->
            <button
              v-if="btn_update.visible"
              class="btn btn-outline-success btn-sm"
              :data-bs-toggle="btn_update.dataToogle"
              :data-bs-target="btn_update.dataTarget"
              @click="setStore(item)"
            >
              Atualizar
            </button>
            <!-- botão de delete -->
            <button
              v-if="btn_delete.visible"
              class="btn btn-outline-danger btn-sm"
              :data-bs-toggle="btn_delete.dataToogle"
              :data-bs-target="btn_delete.dataTarget"
              @click="setStore(item)"
            >
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
    // método que gera uma chave aleatória
    randomKey() {
      return (
        new Date().getTime() + Math.floor(Math.random() * 10000).toString()
      );
    },

    // método que atualiza o id da marca selecionada na store
    setStore(obj) {
      // atualizando a store
      this.$store.state.selectedBrand = obj.id;

      // limpando o status e array de mensagens da store
      this.cleanMessages();
    },

    // método que limpa o status e array de mensagens da store
    cleanMessages() {
      this.$store.state.request_status = "";
      this.$store.state.request_messages = [];
    },
  },
};
</script>

<style>
/* trecho de código que representa o css do componente */
</style>

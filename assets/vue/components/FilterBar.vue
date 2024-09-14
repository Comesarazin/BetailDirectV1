<template>
    <form @submit.prevent="filter" class="mb-8 mx-4 flex items-center justify-center space-x-2">
      <select v-model="type" class="p-2 border border-gray-300 rounded-lg">
        <option value="">Tous les types</option>
        <option value="chien">Chien</option>
        <option value="cheval">Cheval</option>
        <option value="brebis">Brebis</option>
        <option value="cochon">Cochon</option>
      </select>
      <select v-model="race" class="p-2 border border-gray-300 rounded-lg">
        <option value="">Toutes les races</option>
        <option value="labrador">Labrador</option>
        <option value="frison">Frison</option>
        <option value="pottok">Pottok</option>
        <option value="irish cob">Irish cob</option>
        <option value="mérinos">Mérinos</option>
        <option value="solognotes">Solognotes</option>
      </select>
      <button type="submit" class="p-2 bg-blue-500 text-white rounded-lg">Filtrer</button>
    </form>
  </template>
  
  <script>
  export default {
    data() {
      return {
        type: '',
        race: ''
      };
    },
    methods: {
      filter() {
        this.$emit('filter', { type: this.type, race: this.race });
      }
    }
  };
  </script>
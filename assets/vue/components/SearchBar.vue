<template>
    <form @submit.prevent="search" class="mb-8 mx-4 flex items-center space-x-2 max-w-lg w-full ml-auto">
        <input v-model="query" type="text" placeholder="Rechercher un animal..." class="flex-grow p-2 border border-gray-300 rounded-lg">
        <button type="submit" class="p-2 bg-blue-500 text-white rounded-lg">Rechercher</button>
    </form>
</template>

<script>
export default {
  data() {
    return {
      query: ''
    };
  },
  methods: {
    search() {
      this.$emit('search', this.query);
    }
  }
};
</script>
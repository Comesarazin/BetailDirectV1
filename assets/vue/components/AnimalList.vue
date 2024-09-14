<template>
  <div class="container mx-auto px-4 max-w-3xl">
    <div class="grid grid-cols-1 gap-4">
      <div v-for="animal in animals" :key="animal.id" class="block mb-4">
        <a :href="`animal/${animal.id}`" class="block">
          <div class="animal bg-white rounded-lg shadow-md p-4 flex items-center">
            <div class="animal-photo flex-shrink-0">
              <img v-if="animal.photos.length" :src="animal.photos[0]" :alt="animal.name" class="w-40 h-40 object-cover" />
              <img v-else src="/images/Pas-dimage-disponible.jpg" alt="Pas d'image" class="w-40 h-40 object-cover" />
            </div>
            <div class="animal-info ml-4">
              <h3 class="text-lg font-bold mb-2">{{ animal.name }}</h3>
              <p class="text-gray-600">{{ animal.age }} ans</p>
              <p class="text-gray-600">{{ animal.type }}</p>
              <p class="text-gray-600">{{ animal.race }}</p>
            </div>
            <div class="animal-description ml-auto">
              <p class="text-gray-600">{{ animal.description }}</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    animals: {
      type: Array,
      required: true
    }
  }
};
</script>
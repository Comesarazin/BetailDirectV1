import { createApp } from 'vue';
import SearchBar from './vue/components/SearchBar.vue';
import FilterBar from './vue/components/FilterBar.vue';
import AnimalList from './vue/components/AnimalList.vue';

const app = createApp({
    data() {
        return {
            animals: [],
            query: '',
            filters: {
                type: '',
                race: ''
            }
        };
    },
    methods: {
        fetchAnimals() {
            const url = `/api/animals?q=${this.query}&type=${this.filters.type}&race=${this.filters.race}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    this.animals = data;
                });
        },
        handleSearch(query) {
            this.query = query;
            this.fetchAnimals();
        },
        handleFilter(filters) {
            this.filters = filters;
            this.fetchAnimals();
        }
    },
    mounted() {
        this.fetchAnimals();
    }
});

// Enregistrer les composants Vue
app.component('SearchBar', SearchBar);
app.component('FilterBar', FilterBar);
app.component('AnimalList', AnimalList);

// Monter l'application Vue sur un élément DOM spécifique
app.mount('#app');

import './bootstrap.js';
import './styles/app.css';
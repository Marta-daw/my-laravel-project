<template>
    <div>
        <h4>Test connexió - Categorías</h4>

        <div v-if="carregant">Carregant...</div>

        <div v-else-if="error" style="color: red;">
            Error: {{ error }}
        </div>

        <div v-else>
            <q-table
                :rows="rows"
                :columns="columns"
                row-key="col1"
                flat
                bordered
                dense
                :pagination="{ rowsPerPage: 0 }"
                hide-pagination
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from '@/boot/axios.js'

const categorias = ref([])
const error = ref(null)
const carregant = ref(true)

onMounted(async () => {
  try {
    const response = await api.get('/api/categorias')
    categorias.value = response.data
  } catch (err) {
    error.value = err.message
    console.error('Error carregant categorias:', err)
  } finally {
    carregant.value = false
  }
})

const columns = [
  { name: 'col1', label: 'Columna 1', field: 'col1', align: 'left' },
  { name: 'col2', label: 'Columna 2', field: 'col2', align: 'left' }
]

const rows = computed(() => {
    const noms = categorias.value.map(categoria => categoria.nombre)
    const files = []
    for (let i = 0; i < noms.length; i += 2){
        files.push({
            col1: noms[i] ?? '',
            col2: noms[i + 1] ?? '',
        })
    }
    return files
})

</script>

<style scoped></style>
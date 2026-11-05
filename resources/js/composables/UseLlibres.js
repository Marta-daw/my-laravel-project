import { ref } from 'vue'
import { getLlibres } from '@/services/api'

export function useLlibres() {
    const llibres = ref([])
    const carregant = ref(false)
    const error = ref(null)

    async function carregarPagina(page = 1) {
        carregant.value = true
        error.value = null
        try {
            const { data } = await getLlibres({ page, per_page: 10 })
            llibres.value = data.data
        } catch (err) {
            error.value = err.response?.data?.message ?? err.message
        } finally {
            carregant.value = false
        }
    }

    return { llibres, carregant, error, carregarPagina }
}
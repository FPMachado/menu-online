import { reactive, computed } from 'vue'

const state = reactive({
    items: [],
    aberto: false
})

function adicionar(produto) {
    const existe = state.items.find(i => i.id === produto.id)

    if (existe) {
        existe.qtd++
    } else {
        state.items.push({
            ...produto,
            qtd: 1
        })
    }

}

function remover(id) {
    state.items = state.items.filter(item => item.id !== id)
}

function aumentar(id) {
    const item = state.items.find(i => i.id === id)
    if (item) item.qtd++
}

function diminuir(id) {
    const item = state.items.find(i => i.id === id)

    if (!item) return

    if (item.qtd > 1) {
        item.qtd--
    } else {
        remover(id)
    }
}

const totalItens = computed(() =>
    state.items.reduce((t, i) => t + i.qtd, 0)
)

const subtotal = computed(() =>
    state.items.reduce((t, i) => t + (parseFloat(i.preco.replace(',', '.')) * i.qtd), 0)
)

export default {
    state,
    adicionar,
    remover,
    aumentar,
    diminuir,
    totalItens,
    subtotal
}

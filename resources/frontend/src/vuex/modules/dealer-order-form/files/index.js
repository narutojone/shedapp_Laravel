import types from './types'

const state = {
    categories: null
}

const mutations = {
    [types.RECEIVE_FILE_CATEGORIES] (state, categories) {
        state.categories = categories
    }
}

import actions from './_actions'
import getters from './_getters'

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}

import types from './types'

const state = {
  all: []
}

const mutations = {
  [types.RECEIVE_DEALERS] (state, dealers) {
    state.all = dealers
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

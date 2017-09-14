import types from './types'
import updateStoreState from 'src/helpers/update-store-state.js'

const state = {
  loadForm: {
    show: false,
    state: null
  },
  saveForm: {
    show: false,
    state: null
  },
  inventoryForm: {
    show: false,
    state: null
  },
  requestCancellation: {
    show: false,
    state: null
  }
}

const mutations = {
  [types.HIDE_LOAD_FORM_TOOL] (state) {
    state.loadForm.show = false
  },
  [types.SHOW_LOAD_FORM_TOOL] (state) {
    state.saveForm.show = false
    state.loadForm.show = true
    state.loadForm.state = 'new'
  },
  [types.SET_STATE_LOAD_FORM_TOOL] (state, dataState) {
    state.loadForm.state = dataState
  },

  [types.HIDE_SAVE_FORM_TOOL] (state) {
    state.saveForm.show = false
    state.saveForm.onContinue = null
  },
  [types.SHOW_SAVE_FORM_TOOL] (state) {
    state.loadForm.show = false
    state.saveForm.show = true
    state.saveForm.state = 'new'
  },
  [types.SET_STATE_SAVE_FORM_TOOL] (state, params) {
    state.saveForm = updateStoreState(state.saveForm, params)
  },

  [types.SET_STATE_INVENTORY_FORM_TOOL] (state, data, object) {
    state.inventoryForm = updateStoreState(state.inventoryForm, data, object)
  },

  [types.SET_STATE_REQUEST_CANCELLATION_TOOL] (state, data, object) {
    state.requestCancellation = updateStoreState(state.requestCancellation, data, object)
    console.log(state.requestCancellation)
  },

  [types.HIDE_ALL_MODAL_FORM_TOOL] (state) {
    state.loadForm.show = false
    state.saveForm.show = false
    state.inventoryForm.show = false
    state.requestCancellation.show = false
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


import {
    SET_STATE,
    SYNC_START,
    SYNC_SUCCESS,
    SYNC_FAILURE,
    SYNC_UPDATE,

    UPDATE_DEALER,
    UPDATE_CUSTOMER,

    UPDATE_BUILDING,
    ADD_BUILDING_CUSTOM_OPTION,
    REMOVE_BUILDING_CUSTOM_OPTION,
    UPDATE_BUILDING_CUSTOM_OPTION,
    INCREASE_BUILDING_CUSTOM_OPTION,
    DECREASE_BUILDING_CUSTOM_OPTION,

    UPDATE_RENTER,
    UPDATE_SUMMARY,
    UPDATE_VALIDATION
} from '../types'
import orders from 'src/api/orders'

import addAttachment from './add-attachment'
import removeAttachment from './remove-attachment'
import computeCed from './customer-expects-date'
import submitOrder from './submit-order'
import updateOrderOrder from './order'
import searchBySerial from './search-by-serial'
import getOrderFormFields from './get-order-form-fields'
import computeOrdertHash from './compute-order-hash'
import computeOrderSummary from './compute-order-summary'
import computeBuildingSummary from './compute-building-summary'

const setOrderState = function ({commit}, data, object) {
    commit(SET_STATE, data, object)
}

const updateOrderValidation = function ({commit}, data) {
    commit(UPDATE_VALIDATION, data)
}
const updateOrderDealer = function ({commit}, data, object) {
    commit(UPDATE_DEALER, data, object)
}
const updateOrderCustomer = function ({commit}, data, object) {
    commit(UPDATE_CUSTOMER, data, object)
}
const updateOrderBuilding = function ({commit}, data, object) {
    commit(UPDATE_BUILDING, data, object)
}
const addOrderBuildingCustomOption = function ({commit}, option) {
    commit(ADD_BUILDING_CUSTOM_OPTION, option)
}
const removeOrderBuildingCustomOption = function ({commit}, option) {
    commit(REMOVE_BUILDING_CUSTOM_OPTION, option)
}
const updateOrderBuildingCustomOption = function ({commit}, optionIndex, props) {
    commit(UPDATE_BUILDING_CUSTOM_OPTION, optionIndex, props)
}
const increaseOrderBuildingCustomOption = function ({commit}, optionIndex, extraProps) {
    commit(INCREASE_BUILDING_CUSTOM_OPTION, optionIndex, extraProps)
}
const decreaseOrderBuildingCustomOption = function ({commit}, optionIndex, extraProps) {
    commit(DECREASE_BUILDING_CUSTOM_OPTION, optionIndex, extraProps)
}

const updateOrderRenter = function ({commit}, data, object) {
    commit(UPDATE_RENTER, data, object)
}
const updateOrderSummary = function ({commit}, data) {
    commit(UPDATE_SUMMARY, data)
}

// save changed in background if from data is changed
const saveOrderChanges = function({dispatch, commit, state}, params) {
    return new Promise((resolve, reject) => {
        computeOrdertHash({commit, state}, 'next')
        let lastHash = state.state.sync.hash.last
        let newHash = state.state.sync.hash.next

        if (lastHash !== newHash) {
            /*
            // save order through 'save' modal
            dispatch('uiTools/uiToolsSetStateSaveForm', {
                show: true,
                state: 'new'
            })
             // reject()
            */

            // save order in background
            saveDealerOrder({dispatch, commit, state}, {
                successCb() {
                    resolve()
                },
                errorCb(payload) {
                    reject(payload)
                }
            })
        } else {
            resolve()
        }
    })
}

const saveDealerOrder = function ({commit, state}, {payload, beforeCb, successCb, errorCb}) {
    console.log(payload)
    payload = getOrderFormFields({state}, payload)
    console.log(payload)
    return orders.saveDealerOrder(
        {payload},
        () => {
            if (beforeCb) beforeCb()
            commit(SYNC_START, {
                syncStatus: 'saving',
                payload
            })
        },
        (response) => {
            if (successCb) successCb(response)
            commit(SYNC_SUCCESS, {
                syncStatus: 'saved',
                response,
                payload
            })
            computeOrdertHash({commit, state}, 'last')
        },
        (response) => {
            if (errorCb) errorCb(response)
            commit(SYNC_FAILURE, {
                syncStatus: 'error',
                response,
                payload
            })
        }
    )
}

const updateReasonNote = function ({commit, state}, {payload, beforeCb, successCb, errorCb}) {
    console.log(payload)
    payload = getOrderFormFields({state}, payload)
    console.log('Send request to update')
    console.log(payload)
    return orders.updateReasonNote(
        {payload},
        () => {
            if (beforeCb) beforeCb()
            commit(SYNC_START, {
                syncStatus: 'saving',
                payload
            })
        },
        (response) => {
            if (successCb) successCb(response)
            commit(SYNC_SUCCESS, {
                syncStatus: 'saved',
                response,
                payload
            })
            computeOrdertHash({commit, state}, 'last')
        },
        (response) => {
            if (errorCb) errorCb(response)
            commit(SYNC_FAILURE, {
                syncStatus: 'error',
                response,
                payload
            })
        }
    )
}

const loadDealerOrder = function ({commit, state}, {payload, beforeCb, successCb, errorCb}) {
    return orders.getDealerOrder(
        {payload},
        () => {
            if (beforeCb) beforeCb()
            commit(SYNC_START, 'loading')
        },
        (response) => {
            commit(SYNC_SUCCESS, {
                syncStatus: 'loaded',
                response,
                payload
            })
            computeCed({commit, state})
            computeOrdertHash({commit, state}, 'last')
            if (successCb) successCb(response)
        },
        (response) => {
            commit(SYNC_FAILURE, {
                syncStatus: 'error',
                response,
                payload
            })
            if (errorCb) errorCb(response)
        }
    )
}
const searchOrders = function ({commit, state}, {payload, beforeCb, successCb, errorCb}) {
    orders.searchOrders(
        {payload},
        () => {
            if (beforeCb) beforeCb()
            commit(SYNC_START, {
                syncStatus: 'searching',
                payload
            })
        },
        (response) => {
            commit(SYNC_SUCCESS, {
                syncStatus: 'searchEnd',
                response,
                payload
            })
            if (successCb) successCb(response)
        },
        (response) => {
            commit(SYNC_FAILURE, {
                syncStatus: 'error',
                response,
                payload
            })
            if (errorCb) errorCb(response)
        }
    )
}
const getFormPdf = function ({commit, state}, {payload}) {
    if (typeof payload === 'undefined') {
        payload = getOrderFormFields({state})
    }

    return orders.getFormPdf({payload})
}
const updateOrderSync = function ({commit, state}, data) {
    commit(SYNC_UPDATE, data)
}

export default {
    addAttachment,
    removeAttachment,
    computeCed,
    submitOrder,
    updateOrderOrder,
    searchBySerial,

    getOrderFormFields,

    computeOrderSummary,
    computeBuildingSummary,

    computeOrdertHash,
    setOrderState,
    updateOrderSync,
    updateOrderValidation,
    updateOrderDealer,
    updateOrderCustomer,
    updateOrderBuilding,
    addOrderBuildingCustomOption,
    removeOrderBuildingCustomOption,
    updateOrderBuildingCustomOption,
    increaseOrderBuildingCustomOption,
    decreaseOrderBuildingCustomOption,
    updateOrderRenter,
    updateOrderSummary,
    saveOrderChanges,
    saveDealerOrder,
    updateReasonNote,
    loadDealerOrder,
    searchOrders,
    getFormPdf
}
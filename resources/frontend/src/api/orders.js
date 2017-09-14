/**
 * Communicates with API server about orders
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const actions = {
    // required for calculate URL for export data to xls,csv
    get: {
        method: 'GET',
        url: '/api/orders{/id}'
    },
    getPdf: {
        method: 'POST',
        url: '/api/orders/{form}-pdf'
    },
    customerOrder: {
        method: 'POST',
        url: '/api/orders/customer-order'
    },
    generateDocument: {
        method: 'GET',
        url: '/api/orders/{id}/generate-document/{categoryId}'
    },
    search: {
        method: 'POST',
        url: '/api/orders/search'
    },
    saveDealerOrder: {
        method: 'POST',
        url: '/api/orders/save-dealer-order'
    },
    updateReasonNote: {
        method: 'POST',
        url: '/api/orders/update-reason-note'
    },
    getDealerOrder: {
        method: 'GET',
        url: '/api/orders/get-dealer-order/{id}'
    },
    statuses: {
        method: 'GET',
        url: '/api/orders/statuses'
    },
    paymentTypes: {
        method: 'GET',
        url: '/api/orders/payment-types'
    },
    orderTypes: {
        method: 'GET',
        url: '/api/orders/order-types'
    }
}
const options = {
    headers: {
        'X-CSRF-TOKEN': csrfToken()
    }
}

const orderResource = Vue.resource('/api/orders{/id}', {}, actions, options)

export default {
    actions,
    get ({id, query}) {
        return orderResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return orderResource.save({}, data)
        } else {
            data.append('_method', 'put')
            return orderResource.save({
                id: item.id
            }, data)
        }
    },
    paymentTypes () {
        return orderResource.paymentTypes()
    },
    orderTypes () {
        return orderResource.orderTypes()
    },
    generateCustomerOrder ({payload}) {
        return orderResource.customerOrder({}, payload)
            .then(response => response)
            .catch(response => {
                if (response.status === 422) {
                    return Promise.reject({
                        response, message: response.data.message
                    })
                }

                return Promise.reject({
                    response, message: response.statusText
                })
            })
    },
    generateDocument({item, data}) {
        return orderResource.generateDocument({
            id: item.id,
            categoryId: item.categoryId
        }, data)
    },
    getFormPdf ({payload}) {
        return orderResource.getPdf({
            form: payload.form
        }, payload)
            .then(response => response)
            .catch(response => {
                if (response.status === 422) {
                    return Promise.reject({
                        response, message: response.data.message
                    })
                }

                return Promise.reject({
                    response, message: response.statusText
                })
            })
    },
    saveDealerOrder ({payload}, beforeCb, successCb, errorCb) {
        beforeCb()
        return orderResource.saveDealerOrder(payload).then(
            (response) => {
                successCb(response.data)
            },
            (response) => {
                if (response.status === 422) {
                    errorCb(response.data)
                } else {
                    errorCb(response)
                }
            })
    },

    updateReasonNote ({payload}, beforeCb, successCb, errorCb) {
        beforeCb()
        return orderResource.updateReasonNote(payload).then(
            (response) => {
                successCb(response.data)
            },
            (response) => {
                if (response.status === 422) {
                    errorCb(response.data)
                } else {
                    errorCb(response)
                }
            })
    },
    getDealerOrder ({payload}, beforeCb, successCb, errorCb) {
        beforeCb()
        return orderResource.getDealerOrder(payload).then(
            (response) => {
                successCb(response.data)
            },
            (response) => {
                if (response.status === 422) {
                    errorCb(response.data)
                } else {
                    errorCb(response)
                }
            })
    },
    searchOrders ({payload}, beforeCb, successCb, errorCb) {
        beforeCb()
        return orderResource.search(payload).then(
            (response) => {
                successCb(response.data)
            },
            (response) => {
                if (response.status === 422) {
                    errorCb(response.data)
                } else {
                    errorCb(response)
                }
            })
    },
    submitOrder ({item}) {
        return orderResource.saveDealerOrder(item)
    },
    statuses () {
        return orderResource.statuses()
            .then(response => response)
            .catch((response) => {
                if (response.status === 422) return Promise.reject(response.data)
                return Promise.reject(response.statusText)
            })
    },
    listOrders ({query}) {
        return orderResource.get(query)
            .then(response => response)
            .catch((response) => {
                if (response.status === 422) return Promise.reject(response.data)
                return Promise.reject(response.statusText)
            })
    },
    saveOrder ({item}) {
        if (typeof item.id === 'undefined') {
            return orderResource.save({}, item)
        } else {
            return orderResource.update({
                id: item.id
            }, item)
        }
    }
}

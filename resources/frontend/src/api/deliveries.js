/**
 * Communicates with API server about deliveries
 */

import Vue from 'vue'
import {csrfToken} from './_config'

const deliveryResource = Vue.resource('/api/deliveries{/id}',
    {},
    {
        statuses: {
            method: 'GET',
            url: '/api/deliveries/statuses'
        }
    },
    {
        headers: {
            'X-CSRF-TOKEN': csrfToken()
        }
    }
)

export default {
    get ({id, query}) {
        return deliveryResource.get({id, ...query})
    },
    save ({item, data}) {
        if (typeof item.id === 'undefined') {
            return deliveryResource.save({}, data)
        } else {
            // data.append('_method', 'put')
            return deliveryResource.save({
                id: item.id
            }, data)
        }
    },
    delete({item}) {
        return deliveryResource.delete({id: item.id})
    },
    statuses ({ params }) {
        return deliveryResource.statuses(params)
    }
}

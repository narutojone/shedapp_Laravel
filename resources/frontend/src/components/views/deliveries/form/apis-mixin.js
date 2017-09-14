import apiDeliveries from 'src/api/deliveries'
import apiBuildings from 'src/api/buildings'
import apiLocations from 'src/api/locations'

const receiveLocations = function() {
    return apiLocations.get({
        query: {
            fields: ['id', 'name'],
            per_page: 999999,
            order_by: ['name asc']
        }
    })
}

const receiveStatuses = function() {
    return apiDeliveries.statuses({})
}

const receiveBuildings = function() {
    return apiBuildings.get({
        query: {
            fields: ['id', 'serial_number', 'last_location_id'],
            per_page: 999999,
            order_by: ['id asc'],
            include: [
                'last_location',
                'sales.order.order_reference'
            ]
        }
    })
}

export default {
    methods: {
        receiveLocations,
        receiveStatuses,
        receiveBuildings
    }
}
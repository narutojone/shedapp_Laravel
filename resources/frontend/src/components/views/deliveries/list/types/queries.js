export default {
    searches: {
        status_id(search) {
            let query = {}
            let statuses = search.value.split(',')
            _.set(query, ['where', 'status_id', 'inq'], statuses)
            return query
        },
        location_start_id(search) {
            let query = {}
            let locations = search.value.split(',')
            _.set(query, ['where', 'location_start_id', 'inq'], locations)
            return query
        },
        location_end_id(search) {
            let query = {}
            let locations = search.value.split(',')
            _.set(query, ['where', 'location_end_id', 'inq'], locations)
            return query
        }
    }
}

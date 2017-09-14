import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Delivery ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id'],
            group_by: ['id']
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        checked: true,
        order_by: 'status_id',
        query: {
            fields: ['status_id'],
            group_by: ['status_id']
        }
    },
    {
        name: 'Building ID',
        id: 'building_id',
        checked: true,
        order_by: 'building.serial_number',
        query: {
            fields: ['building_id'],
            include: ['building'],
            leftJoinRelation: ['building'],
            group_by: ['building.serial_number']
        }
    },
    {
        name: 'Ready Date',
        id: 'ready_date',
        checked: true,
        order_by: 'ready_date',
        query: {
            fields: ['ready_date'],
            group_by: ['ready_date']
        }
    },
    {
        name: 'Start Location',
        id: 'location_start_id',
        checked: true,
        order_by: 'start_location.name',
        query: {
            fields: ['location_start_id'],
            include: ['start_location'],
            leftJoinRelation: ['start_location'],
            group_by: ['location_start_id']
        }
    },
    {
        name: 'End Location',
        id: 'location_end_id',
        checked: true,
        order_by: 'end_location.name',
        query: {
            fields: ['location_end_id'],
            include: ['end_location'],
            leftJoinRelation: ['end_location'],
            group_by: ['location_end_id']
        }
    },
    {
        name: 'Start Date',
        id: 'date_start',
        checked: true,
        order_by: 'date_start',
        query: {
            fields: ['date_start'],
            group_by: ['date_start']
        }
    },
    {
        name: 'End Date',
        id: 'date_end',
        checked: true,
        order_by: 'date_end',
        query: {
            fields: ['date_end'],
            group_by: ['date_end']
        }
    },
    {
        name: 'Scheduled Date',
        id: 'scheduled_date',
        checked: true,
        order_by: 'scheduled_date',
        query: {
            fields: ['scheduled_date'],
            group_by: ['scheduled_date']
        }
    },
    {
        name: 'Confirmed Date',
        id: 'confirmed_date',
        checked: true,
        order_by: 'confirmed_date',
        query: {
            fields: ['confirmed_date'],
            group_by: ['confirmed_date']
        }
    },
    {
        name: 'Cost',
        id: 'cost',
        checked: true,
        order_by: 'cost',
        query: {
            fields: ['cost'],
            group_by: ['cost']
        }
    },
    {
        name: 'Price',
        id: 'price',
        checked: true,
        order_by: 'price',
        query: {
            fields: ['price'],
            group_by: ['price']
        }
    },
    {
        name: 'Invoice #',
        id: 'invoice',
        checked: true,
        order_by: 'invoice',
        query: {
            fields: ['invoice'],
            group_by: ['invoice']
        }
    }
]

let searches = [
    {
        name: 'Status',
        id: 'status_id',
        value: null,
        checked: false
        // query: {
        //     where: 'status_id'
        // }
    },
    {
        name: 'Building',
        id: 'building_id',
        value: null,
        checked: false,
        query: {
            where: 'building_id'
        }
    },
    {
        name: 'Start Location',
        id: 'location_start_id',
        value: null,
        checked: false
        // query: {
        //     where: 'location_start_id'
        // }
    },
    {
        name: 'End Location',
        id: 'location_end_id',
        value: null,
        checked: false
        // query: {
        //     where: 'location_end_id'
        // }
    },
    {
        name: 'Ready Date',
        id: 'ready_date',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'ready_date'
        }
    },
    {
        name: 'Scheduled Date',
        id: 'scheduled_date',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'scheduled_date'
        }
    },
    {
        name: 'Confirmed Date',
        id: 'confirmed_date',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'confirmed_date'
        }
    },
    {
        name: 'Date Start',
        id: 'date_start',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'date_start'
        }
    },
    {
        name: 'Date End',
        id: 'date_end',
        parse(value) {
            let between = []
            _.merge(between, parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD'))
            return {between}
        },
        checked: false,
        query: {
            where: 'date_end'
        }
    }
    /*
    {
        name: 'Length',
        id: 'length',
        value: null,
        checked: false,
        query: {
            where: 'length'
        }
    }*/
]

let totals = [
    {
        name: 'Rows',
        id: 'totalRows',
        type: 'unit',
        checked: null,
        selectable: false,
        value: null
    }
]

export default {
    dimensions,
    searches,
    totals
}

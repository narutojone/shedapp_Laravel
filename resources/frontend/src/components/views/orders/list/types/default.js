import parseDates from 'src/components/views/_base/ListItems/_helpers/parse-route-search-dates'

let dimensions = [
    {
        name: 'Order ID',
        id: 'id',
        checked: true,
        order_by: 'id',
        query: {
            fields: ['id', 'uuid'],
            group_by: ['id']
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        checked: true,
        order_by: 'created_at',
        query: {
            fields: ['created_at'],
            group_by: ['created_at']
        }
    },
    {
        name: 'Date Updated',
        id: 'date_updated',
        checked: true,
        order_by: 'updated_at',
        query: {
            fields: ['updated_at'],
            group_by: ['updated_at']
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
        name: 'Model',
        id: 'building_model',
        checked: true,
        order_by: 'building.building_model_id.name',
        query: {
            fields: ['building_id'],
            include: ['building.building_model'],
            leftJoinRelation: ['building.building_model'],
            group_by: ['building.building_model_id']
        }
    },
    {
        name: 'Retail',
        id: 'retail',
        checked: true,
        order_by: 'retail',
        query: {
            fields: ['total_sales_price'],
            fn: {
                'sum.retail': 'total_sales_price'
            }
        }
    },
    {
        name: 'Order Type',
        id: 'order_type',
        checked: true,
        order_by: 'sale_type',
        query: {
            fields: ['sale_type'],
            group_by: ['sale_type']
        }
    },
    {
        name: 'Dealer',
        id: 'dealer',
        checked: true,
        order_by: 'dealer_id',
        query: {
            fields: ['dealer_id'],
            include: ['dealer'],
            leftJoinRelation: ['dealer'],
            group_by: ['dealer_id']
        }
    },
    {
        name: 'Sales Person',
        id: 'sales_person',
        checked: true,
        order_by: 'sales_person',
        query: {
            fields: ['sales_person'],
            group_by: ['sales_person']
        }
    },
    {
        name: 'Customer',
        id: 'customer',
        order_by: 'order_reference.first_name',
        query: {
            fields: ['reference_id'],
            include: ['order_reference'],
            leftJoinRelation: ['order_reference'],
            group_by: ['order_reference.first_name']
        },
        checked: true
    },
    {
        name: 'Payment Type',
        id: 'payment_type',
        checked: true,
        order_by: 'payment_type',
        query: {
            fields: ['payment_type'],
            group_by: ['payment_type']
        }
    },
    {
        name: 'Attachments',
        id: 'attachments',
        checked: true,
        query: {
            include: ['files']
        }
    }
]

let searches = [
    {
        name: 'Order ID',
        id: 'order_id',
        value: null,
        checked: false,
        query: {
            where: 'id'
        }
    },
    {
        name: 'Date Created',
        id: 'date_created',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
            return {between}
        },
        checked: false,
        query: {
            where: 'created_at'
        }
    },
    {
        name: 'Date Updated',
        id: 'date_updated',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD HH:mm:ss')
            return {between}
        },
        checked: false,
        query: {
            where: 'updated_at'
        }
    },
    {
        name: 'Status',
        id: 'status_id',
        value: 'submitted',
        checked: true
    },
    {
        name: 'Model',
        id: 'model_id',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Retail',
        id: 'retail',
        value: null,
        checked: false,
        query: {
            where: 'total_sales_price'
        }
    },
    {
        name: 'Order Type',
        id: 'order_type',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Dealer',
        id: 'dealer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Sales Person',
        id: 'sales_person',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Customer',
        id: 'customer',
        value: null,
        checked: false
        // query by helper
    },
    {
        name: 'Order Date',
        id: 'order_date',
        parse(value) {
            let between = parseDates.ranges(_.get(value, 'between'), 'YYYY-MM-DD')
            return {between}
        },
        checked: false,
        query: {
            where: 'order_date'
        }
    },
    {
        name: 'Serial Numbers',
        id: 'serial_numbers_values',
        value: null,
        checked: true
        // query by helper
    }
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

<template>

    <tr>
        <td class="text-center" v-for="(dimension, d_id) in dimensions" :key="d_id">
            <template v-if="dimension.id === 'id'">{{ item.id }}</template>

            <template v-if="dimension.id === 'status_id'">
                <delivery-status-label :status="item.status"></delivery-status-label>
            </template>

            <template v-if="dimension.id === 'building_id'">
                <span v-if="item.building">
                    <a :href="'/buildings/#/' + item.building.id" target="_blank">
                        {{ item.building.serialNumber }}
                    </a>
                </span>
            </template>

            <template v-if="dimension.id === 'ready_date'">
                <span class="label label-default" v-if="item.readyDate">
                    {{ filters.moment(item.readyDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'location_start_id'">
                <span v-if="item.startLocation">
                    {{ item.startLocation.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'location_end_id'">
                <span v-if="item.endLocation">
                    {{ item.endLocation.name }}
                </span>
            </template>

            <template v-if="dimension.id === 'date_start'">
                <span class="label label-default" v-if="item.dateStart">
                    {{ filters.moment(item.dateStart, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'date_end'">
                <span class="label label-default" v-if="item.dateEnd">
                    {{ filters.moment(item.dateEnd, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'scheduled_date'">
                <span class="label label-default" v-if="item.scheduledDate">
                    {{ filters.moment(item.scheduledDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'confirmed_date'">
                <span class="label label-default" v-if="item.confirmedDate">
                    {{ filters.moment(item.confirmedDate, 'YYYY-MM-DD', 'MM/DD/YYYY') }}
                </span>
            </template>

            <template v-if="dimension.id === 'cost'">
                <span class="money-cell">
                    {{ item.cost !== undefined ? filters.money(item.cost) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'price'">
                <span class="money-cell">
                    {{ item.price !== undefined ? filters.money(item.price) : '' }}
                </span>
            </template>

            <template v-if="dimension.id === 'invoice'">
                {{ item.invoice }}
            </template>
        </td>

        <td class="text-center nowrap" v-if="dimensions.id">
            <a class="pointer btn btn-primary btn-xs" v-on:click="openUpdateModal"><i class="fa fa-pencil"></i></a>
            <a class="pointer btn btn-danger btn-xs" v-on:click="openDeleteModal"><i class="fa fa-trash-o"></i></a>
        </td>
    </tr>

</template>

<script type="text/babel">
    import baseListItemCrud from 'src/components/views/_base/ListItems/list/ListItemCrud.vue'
    import DeliveryStatusLabel from 'src/components/views/partials/DeliveryStatusLabel.vue'

    export default {
        extends: baseListItemCrud,
        data() {
            return {}
        },
        components: {
            DeliveryStatusLabel
        },
        created() {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>
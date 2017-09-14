<template>

    <div class="search-form form-horizontal">
        <ul class="list-group list-group-horizontal list-tools-form">
            <template v-for="search in currentSearches">
                <form-search-date v-if="search.id === 'date_created'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD HH:mm:ss'">
                </form-search-date>
                <form-search-select v-if="search.id === 'status_id'"
                                      :label="'name'"
                                      v-bind:title="'name'"
                                      v-bind:datas="deliveryStatuses"
                                      v-bind:item="search">
                </form-search-select>
                <form-search-buildings v-if="search.id === 'building_id'"
                                       v-bind:datas="buildings"
                                       v-bind:item="search">
                </form-search-buildings>
                <form-search-select v-if="search.id === 'location_start_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="locations"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-select v-if="search.id === 'location_end_id'"
                                    :label="'name'"
                                    v-bind:title="'name'"
                                    v-bind:datas="locations"
                                    v-bind:item="search">
                </form-search-select>
                <form-search-date v-if="search.id === 'ready_date'"
                                  v-bind:item="search">
                </form-search-date>
                <form-search-date v-if="search.id === 'scheduled_date'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-date v-if="search.id === 'confirmed_date'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_start'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
                <form-search-date v-if="search.id === 'date_end'"
                                  v-bind:item="search"
                                  v-bind:format="'YYYY-MM-DD'">
                </form-search-date>
            </template>
        </ul>
    </div>

</template>

<script type="text/babel">
    import baseSearchForm from 'src/components/views/_base/ListItems/filters/SearchForm.vue'

    import FormSearchDate from 'src/components/views/_base/ListItems/search-forms/Date.vue'
    import FormSearchSelect from 'src/components/views/_base/ListItems/search-forms/Select.vue'
    import FormSearchBuildings from './search-forms/Buildings.vue'

    import apiDeliveries from 'src/api/deliveries'
    import apiLocations from 'src/api/locations'
    import apiBuildings from 'src/api/buildings'

    export default {
        extends: baseSearchForm,
        data() {
            return {
                statuses: {},
                locations: [],
                buildings: []
            }
        },
        components: {
            FormSearchDate,
            FormSearchSelect,
            FormSearchBuildings
        },
        methods: {
            syncSearches() {},
            fetchData() {
                const datas = [
                    apiDeliveries.statuses({}),
                    apiBuildings.get({
                        query: {
                            fields: ['id', 'serial_number'],
                            per_page: 999999,
                            order_by: ['id asc']
                        }
                    }),
                    apiLocations.get({
                        fields: ['id', 'name'],
                        query: {
                            per_page: 999999
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.deliveryStatuses = response[0].data
                        this.buildings = response[1].data.data
                        this.locations = response[2].data.data
                        this.$emit('data-ready')
                        return response
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            }
        }
    }
</script>
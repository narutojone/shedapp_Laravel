<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.id">
                    <div class="col-xs-12 col-md-3">
                        <label for="status_id" class="control-label">Status</label>
                        <select id="status_id"
                                name="status_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.statusId">
                            <option v-for="(status, status_id) in statuses" 
                                    v-bind:value="status.id"
                                    v-bind:selected="curItem.statusId == status.id">
                                {{ status.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.statusId === 'completed'">
                        <label class="control-label">&nbsp;</label>
                        <div class="checkbox">
                            <input id="assoc_end_location"
                                   name="assoc_end_location"
                                   type="checkbox"
                                   value="1"
                                   v-model="curItem.assocEndLocation"
                                   v-bind:true-value="1"
                                   v-bind:false-value="0"
                                   v-bind:checked=" curItem.statusId !== 'completed' ">
                            <label for="assoc_end_location">Add & Associate End Location</label>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-6 col-md-6">
                        <label for="building_id" class="control-label">Building</label>
                        <select id="building_id"
                                name="building_id"
                                class="form-control"
                                initial="off"
                                v-on:change="applyBuildingParams($event.target.value)"
                                v-model="curItem.buildingId">
                            <option :value="null" disabled>Select...</option>
                            <option v-for="(building, building_id) in buildings"
                                    v-bind:value="building.id">#{{ building.id }} - {{ building.serialNumber }}</option>
                        </select>
                    </div>
                    <div class="col-xs-6 col-md-6" v-if="sales">
                        <label for="sale_id" class="control-label">Sales</label>
                        <select id="sale_id"
                                name="sale_id"
                                class="form-control"
                                initial="off"
                                v-on:change="applySaleParams(sale)"
                                v-model="curItem.saleId">
                            <option :value="null" selected>Select...({{ sales.length }})</option>
                            <option v-for="(sale, sale_id) in sales"
                                    v-bind:value="sale.id">#{{ sale.id }} - {{ sale.order.orderReference.customerName }}</option>
                        </select>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-sm-6" v-if="buildingCurrentLocation">
                        <label for="building_id" class="control-label">Current Location</label>
                        <div><strong>{{ buildingCurrentLocation.name }}</strong></div>
                    </div>
                    <div class="col-xs-12 col-sm-6" v-if="buildingSaleLocation">
                        <label for="building_id" class="control-label">Sale Location</label>
                        <div><strong>{{ buildingSaleLocation.name }}</strong></div>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="location_start_id" class="control-label">
                            Start Location
                            <span v-if="buildingCurrentLocation && curItem.locationStartId && curItem.locationStartId === buildingCurrentLocation.id" style="color: red">
                                <strong>(current)</strong>
                            </span>
                            <span v-if="buildingSaleLocation && curItem.locationStartId && curItem.locationStartId === buildingSaleLocation.id" style="color: #0039b7">
                                <strong>(sale)</strong>
                            </span>
                        </label>
                        <select id="location_start_id"
                                name="location_start_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.locationStartId">
                            <option :value="null" disabled>Select...</option>
                            <option v-for="location in locations"
                                    v-bind:value="location.id">
                                {{ location.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-5 form-inline">
                        <div>
                            <label for="location_end_id" class="control-label">End Location</label>
                            <span v-if="buildingCurrentLocation && curItem.locationEndId && curItem.locationEndId === buildingCurrentLocation.id" style="color: red">
                                <strong>(current)</strong>
                            </span>
                            <span v-if="buildingSaleLocation && curItem.locationEndId && curItem.locationEndId === buildingSaleLocation.id" style="color: #0039b7">
                                <strong>(sale)</strong>
                            </span>
                        </div>
                        <select id="location_end_id"
                                name="location_end_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.locationEndId">
                            <option :value="null" disabled>Select...</option>
                            <option v-for="location in locations"
                                    v-bind:value="location.id">{{ location.name }}</option>
                        </select>
                        <button type="button"
                                class="btn btn-primary btn"
                                title="Add option"
                                v-on:click.prevent="openCreateLocationModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="ready_date" class="control-label">Ready Date</label>
                        <datepicker :width="'100%'" id="ready_date" :value="curItem.readyDate" ref="readyDate"></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="scheduled_date" class="control-label">Scheduled Date</label>
                        <datepicker :width="'100%'" id="scheduled_date" :value="curItem.scheduledDate" ref="scheduledDate"></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="confirmed_date" class="control-label">Confirmed Date</label>
                        <datepicker :width="'100%'" id="confirmed_date" :value="curItem.confirmedDate" ref=confirmedDate></datepicker>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="date_start" class="control-label">Date Start</label>
                        <datepicker :width="'100%'" id="date_start" :value="curItem.dateStart" ref="dateStart"></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="date_end" class="control-label">Date End</label>
                        <datepicker :width="'100%'" id="date_end" :value="curItem.dateEnd" ref="dateEnd"></datepicker>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="length" class="control-label">Length</label>
                        <input class="form-control" placeholder="Length" name="length" type="number" id="length" v-model="curItem.length">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="price" class="control-label">Price</label>
                        <input class="form-control" placeholder="Price $" name="price" type="number" id="price" v-model="curItem.price">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="cost" class="control-label">Cost</label>
                        <input class="form-control" placeholder="Cost $" name="Cost" type="number" id="cost" v-model="curItem.cost">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="invoice" class="control-label">Invoice</label>
                        <input class="form-control" placeholder="" name="invoice" type="text" id="invoice" v-model="curItem.invoice">
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes" class="control-label">Notes</label>
                        <textarea class="form-control" placeholder="" name="notes" id="notes" v-model="curItem.notes"></textarea>
                    </div>
                </div>
            </div>
        </form>

        <component v-if="createLocationModal" :show="createLocationModal"
                   is="LocationModalCreate"
                   v-bind:item="{}"
                   v-on:close="closeCreateLocationModal"
                   v-on:saved="pickNewLocation">
        </component>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from 'src/components/ui/Datepicker.vue'
    // import LocationModalCreate from 'src/components/views/locations/modals/LocationModalCreate.vue'

    import apiDeliveries from 'src/api/deliveries'
    import apisMixin from './apis-mixin'

    export default {
        name: 'delivery-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                createLocationModal: false,
                curItem: {
                    statusId: null,
                    assocEndLocation: null,
                    buildingId: null,
                    saleId: null,
                    locationStartId: null,
                    locationEndId: null,
                    readyDate: null,
                    scheduledDate: null,
                    confirmedDate: null,
                    dateStart: null,
                    dateEnd: null,
                    length: null,
                    price: null,
                    cost: null,
                    invoice: null,
                    notes: null
                },
                // data dependency
                statuses: {},
                buildings: {},
                locations: {}
            }
        },
        components: {
            Datepicker,
            LocationModalCreate: function(resolve) {
                require(['src/components/views/locations/modals/ModalCreate.vue'], resolve)
            }
        },
        methods: {
            save({ item, data }) {
                return apiDeliveries.save({ item, data }).then(response => response.data)
            },
            initData() {
                if (this.item.id) {
                    apiDeliveries.get({
                        id: this.item.id,
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                            this.curItem.readyDate = this.filters.moment(this.curItem.readyDate, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.scheduledDate = this.filters.moment(this.curItem.scheduledDate, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.confirmedDate = this.filters.moment(this.curItem.confirmedDate, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.dateStart = this.filters.moment(this.curItem.dateStart, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.dateEnd = this.filters.moment(this.curItem.dateEnd, 'YYYY-MM-DD', 'MM/DD/YYYY')
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    this.initDependencies()
                        .then(response => { this.$emit('data-ready') })
                        .catch(response => { this.$emit('data-failed', response) })
                }
            },
            initDependencies(dep = null) {
                const datas = []

                if (dep === null) {
                    datas.push(this.receiveStatuses())
                    datas.push(this.receiveBuildings())
                    datas.push(this.receiveLocations())
                }

                if (dep === 'locations') {
                    datas.push(this.receiveLocations())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.statuses = response[0].data
                            this.buildings = response[1].data.data
                            this.locations = response[2].data.data
                        }

                        if (dep === 'locations') {
                            this.locations = response[0].data.data
                        }
                        return response
                    })
            },
            submit() {
                let form = this.curItem
                if (this.curItem.id) form._method = 'put'

                if (this.$refs.readyDate.val) form.ready_date = this.filters.moment(this.$refs.readyDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.scheduledDate.val) form.scheduled_date = this.filters.moment(this.$refs.scheduledDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.confirmedDate.val) form.confirmed_date = this.filters.moment(this.$refs.confirmedDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.dateStart.val) form.date_start = this.filters.moment(this.$refs.dateStart.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.dateEnd.val) form.date_end = this.filters.moment(this.$refs.dateEnd.val, 'MM/DD/YYYY', 'YYYY-MM-DD')

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form, data: form })
                    .then(msg => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: msg
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            openCreateLocationModal() {
                this.createLocationModal = true
            },
            closeCreateLocationModal() {
                this.createLocationModal = false
            },
            pickNewLocation(newLocation) {
                return new Promise(this.receiveLocations).then(resolve => {
                    this.$set(this.curItem, 'locationEndId', newLocation.id)
                    resolve()
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            applyBuildingParams(buildingID) {
                let found = _.find(this.buildings, {id: parseInt(buildingID)})
                if (found) {
                    // let building = _.cloneDeep(found)
                    if (found.lastLocation) {
                        this.curItem.locationStartId = found.lastLocation.locationId
                        this.curItem.saleId = null
                    }
                }
            },
            applySaleParams(sale) {
                if (!sale) return
                this.$set(this.curItem, 'saleId', sale.id)
                this.$set(this.curItem, 'locationEndId', sale.locationId)
            }
        },
        computed: {
            building() {
                let building = null
                if (this.curItem.buildingId) {
                    let buildingID = this.curItem.buildingId
                    let found = _.find(this.buildings, {id: buildingID})
                    if (found) {
                        building = _.cloneDeep(found)
                    }
                }
                return building
            },
            buildingCurrentLocation() {
                if (!this.building || !this.building.lastLocation) return null
                let found = _.find(this.locations, {id: this.building.lastLocation.locationId})
                return found
            },
            buildingSaleLocation() {
                if (!this.sale) return null
                let found = _.find(this.locations, {id: this.sale.locationId})
                return found
            },
            sales() {
                if (_.isEmpty(this.building)) return []
                return this.building.sales || []
            },
            sale() {
                if (!this.curItem.saleId) return null
                let sale = _.find(this.sales, {id: this.curItem.saleId})
                return sale
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .datepicker{
        position: relative;
        display: block !important;
        padding: 0;
    }
</style>
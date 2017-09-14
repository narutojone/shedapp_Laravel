<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="is_active_flags" class="control-label">Is Active</label>
                        <select id="is_active_flags"
                                name="is_active_flags"
                                class="form-control"
                                v-model="curItem.isActive"
                                initial="off">
                            <option v-for="activeFlag in activeFlags"
                                    v-bind:value="activeFlag.id"
                                    v-bind:selected="curItem.isActive && curItem.isActive == activeFlag.id">
                                {{ activeFlag.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="date_updated" class="control-label">Date Updated</label>
                        <div id="date_updated">
                            {{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                </div>

                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="name" class="control-label">Business Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.businessName">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label for="style_id" class="control-label">Location</label>
                        <div class="form-inline">
                            <select id="style_id"
                                    name="style_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.locationId">
                                <option :value="null"></option>
                                <option v-for="location in locations"
                                        v-bind:value="location.id">{{ location.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-6 col-sm-4">
                        <label for="phone" class="control-label">Phone</label>
                        <input id="phone" type="text" class="form-control" v-model="curItem.phone">
                    </div>
                    <div class="col-xs-6 col-sm-4">
                        <label for="email" class="control-label">Email</label>
                        <input id="email" type="text" class="form-control" v-model="curItem.email">
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Tax Rate</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.taxRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Commission Rate</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.commissionRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Deposit % for Cash Sales</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.cashSaleDepositRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiDealers from 'src/api/dealers'
    import apisMixin from './apis-mixin'

    export default {
        name: 'dealer-model-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                curItem: {},
                locations: [],
                activeFlags: {}
            }
        },
        components: {},
        methods: {
            save({ item, data }) {
                return apiDealers.save({ item, data }).then(response => response.data)
            },
            submit() {
                let item = _.merge({}, {
                    businessName: this.curItem.businessName || null,
                    phone: this.curItem.phone || null,
                    email: this.curItem.email || null,
                    locationId: this.curItem.locationId || null,
                    taxRate: this.curItem.taxRate || null,
                    commissionRate: this.curItem.commissionRate || null,
                    cashSaleDepositRate: this.curItem.cashSaleDepositRate || null,
                    isActive: this.curItem.isActive || null
                })

                if (this.curItem.id) {
                    item.id = this.curItem.id
                    item._method = 'put'
                }

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: item, data: item })
                    .then(data => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: data
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.item.id) {
                    apiDealers.get({
                        id: this.item.id,
                        query: {
                            include: ['location']
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
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
                    datas.push(this.receiveActiveFlags())
                    datas.push(this.receiveLocations())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.activeFlags = response[0].data
                            this.locations = response[1].data.data
                        }

                        return response
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .row {
        margin-bottom: 0.5em;
    }
</style>
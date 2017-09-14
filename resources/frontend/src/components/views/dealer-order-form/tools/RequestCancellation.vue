<template>
    <div>
        <div class="panel-heading">
            <h2 class="panel-title">Request Cancellation</h2>
        </div>

        <div class="panel-body overlayable">
            <div class="form-container">
                <data-process :process="dataProcess" :with_loader="true"></data-process>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label for="request_cancellation_reason">Please indicate the reason that the customer would like to cancel the order. Cancellation fees may apply.</label>
                                <textarea type="text"
                                       class="form-control"
                                       id="request_cancellation_reason"
                                       placeholder="Please indicate the reason that the customer would like to cancel the order."
                                       v-model="dealerNotes"> 
                                </textarea>
                                <div v-if="$v.dealerNotes.$dirty && $v.dealerNotes.required === false" class="alert alert-danger" role="alert">This field is required.</div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <div class="panel-footer" style="text-align: center">
            <button type="button" class="btn btn-default" @click="close">Close</button>
            <button type="button" class="btn btn-primary" @click="save" :disabled="dataProcess.running">Save</button>
        </div>
    </div>

</template>

<script type="text/babel">
    import baseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import {mapActions, mapGetters} from 'vuex'
    import {required} from 'vuelidate/lib/validators'
    export default {
        extends: baseDataItem,
        data() {
            return {
                dealerNotes: null,
                customer: {
                    firstName: null,
                    lastName: null,
                    email: null
                },
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        mounted() {
            this.dealerNotes = this.dealerNotes
            this.customer = {
                email: '1@gmail.com',
                firstName: 'P',
                lastName: 'G'
            }
        },
        computed: {
            ...mapGetters({
                getUiToolsStateRequestCancellation: 'dealerOrderForm/uiTools/getUiToolsStateRequestCancellation',
                orderDealerID: 'dealerOrderForm/orderDealerID'
            })
        },
        methods: {
            ...mapActions({
                updateReasonNote: 'dealerOrderForm/updateReasonNote',
                uiToolsSetStateRequestCancellation: 'dealerOrderForm/uiTools/uiToolsSetStateRequestCancellation'
            }),
            close() {
                this.item = null
                this.uiToolsSetStateRequestCancellation({'show': false})
            },
            save() {
                this.$v.$touch()
                if (this.$v.$error) return
                let self = this
                self.updateReasonNote({
                    payload: {
                        customer: self.customer,
                        status: 'request_cancellation',
                        dealerNotes: this.dealerNotes
                    },
                    beforeCb() {
                        self.run({text: 'Saving..', type: 'form'})
                    },
                    successCb() {
                        self.$emit('data-process-update', {
                            running: false,
                            success: 'Dealer Note successfully saved.'
                        })
                    },
                    errorCb(response) {
                        self.$emit('data-failed', response)
                    }
                })
            }
        },
        validations: {
            dealerNotes: {required}
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>
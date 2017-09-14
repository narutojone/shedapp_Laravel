<template>

    <modal :show="currentModalTool !== false"
           :modal-class="modalClass"
           :modal-style="modalStyle"
           :close-modal-method="closeModalMethod">
        <component :is="currentModalTool">

        </component>
    </modal>

</template>

<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'

    import Modal from 'src/components/ui/Modal.vue'
    import LoadForm from './LoadForm.vue'
    import SaveForm from './SaveForm.vue'
    import InventoryForm from './InventoryForm.vue'
    import RequestCancellation from './RequestCancellation.vue'

    export default {
        data() {
          return {
              modalClass: 'col-md-4 col-sm-6 col-xs-11',
              modalStyle: {
                  float: 'none',
                  padding: '0'
              }
          }
        },
        components: {
            Modal,
            LoadForm,
            SaveForm,
            InventoryForm,
            RequestCancellation
        },
        computed: {
            ...mapGetters({
                uiToolsShowLoadForm: 'dealerOrderForm/uiTools/getUiToolsShowLoadForm',
                uiToolsShowSaveForm: 'dealerOrderForm/uiTools/getUiToolsShowSaveForm',
                uiToolsShowInventoryForm: 'dealerOrderForm/uiTools/getUiToolsShowInventoryForm',
                uiToolsShowRequestCancellation: 'dealerOrderForm/uiTools/getUiToolsShowRequestCancellation'
            }),
            currentModalTool() {
                if (this.uiToolsShowLoadForm) return 'LoadForm'
                if (this.uiToolsShowSaveForm) return 'SaveForm'
                if (this.uiToolsShowInventoryForm) return 'InventoryForm'
                if (this.uiToolsShowRequestCancellation) return 'RequestCancellation'

                return false
            }
        },
        methods: {
            ...mapActions({
                uiToolsHideLoadForm: 'dealerOrderForm/uiTools/uiToolsHideLoadForm',
                uiToolsHideSaveForm: 'dealerOrderForm/uiTools/uiToolsHideSaveForm',
                uiToolsSetStateInventoryForm: 'dealerOrderForm/uiTools/uiToolsSetStateInventoryForm',
                uiToolsSetStateRequestCancellation: 'dealerOrderForm/uiTools/uiToolsSetStateRequestCancellation'
            }),
            closeModalMethod() {
                if (this.currentModalTool === 'LoadForm') this.uiToolsHideLoadForm()
                if (this.currentModalTool === 'SaveForm') this.uiToolsHideSaveForm()
                if (this.currentModalTool === 'InventoryForm') {
                    this.uiToolsSetStateInventoryForm({'show': false})
                }
                if (this.currentModalTool === 'RequestCancellation') {
                    this.uiToolsSetStateRequestCancellation({'show': false})
                }
            }
        }
    }
</script>

<style type="text/css">

</style>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .customer-panel {
        .panel-heading {
            padding: 0.7em;
        }
    }
</style>
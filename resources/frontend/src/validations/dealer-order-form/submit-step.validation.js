import {required, between} from 'vuelidate/lib/validators'
import accepted from 'src/validators/accepted'

export default function () {
    let attachedCategories = {
        signedDepositReceipt: {}
    }
    let allRules = {
        // inital step
        depositReceived: {
            between: between(this.depositAmount, 9999999)
        },
        paymentMethod: {},
        transactionId: {},
        attachedCategories
    }

    if (this.orderStateMode === 'submit') {
        allRules.depositReceived.required = required
        allRules.paymentMethod.required = required

        if (this.paymentMethod === 'credit_card' || this.paymentMethod === 'check') {
            allRules.transactionId.required = required
        }

        if (this.paymentMethod !== 'credit_card') {
            allRules.attachedCategories.signedDepositReceipt.accepted = accepted
        }
    }

    return allRules
}

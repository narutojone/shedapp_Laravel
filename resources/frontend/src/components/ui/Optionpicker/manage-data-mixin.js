/*global swal*/

const setReactive = (vm, alias, value) => {
    let arr = _.split(alias, '.')
    if (arr.length > 1) {
        let key = _.takeRight(arr, arr.length - 1)
        let obj = _.take(arr, arr.length - 1)
        key = key.join('.')
        obj = obj.join('.')
        vm.$set(vm[obj], key, value)
        return vm[obj][key]
    }

    vm[alias] = value
    return vm[alias]
}

export default {
    /*
     * Required data: this.data.alias
     * Required props: 'this.data.alias'
     */
    methods: {
        addOption(option, extraProps) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            var selectedBuildingOption = _.find(buildingOptions, function (el) {
                return el.optionId === option.id
            }, this)

            if (typeof selectedBuildingOption === 'undefined') {
                if (buildingOptions.length >= 24) {
                    swal('Error', 'There is a limit of 24 custom options per building.', 'error')
                    return
                }

                option = _.cloneDeep(option)

                let buildingOption = _.assign({
                    'optionId': option.id
                }, {
                    'option': option,
                    'color': option.color || null,
                    'quantity': 1,
                    'minQuantity': option.minQuantity || null,
                    'parentOptions': option.parentOptions || [],
                    'unitPrice': option.unitPrice,
                    'totalPrice': option.unitPrice * 1
                }, extraProps)

                buildingOptions.push(buildingOption)
            } else {
                selectedBuildingOption.quantity = selectedBuildingOption.quantity + 1
                selectedBuildingOption.totalPrice = selectedBuildingOption.unitPrice * selectedBuildingOption.quantity
            }
        },
        removeOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            if (buildingOptions.length > 0) {
                var options = _.filter(buildingOptions, function (item) {
                    return item.optionId !== buildingOption.optionId
                }, this)

                setReactive(this, this.alias.buildingOptions, options)
            }
        },
        increaseOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            var selectedOption = _.find(buildingOptions, function (el) {
                return el.optionId === buildingOption.optionId
            }, this)

            if (typeof selectedOption !== 'undefined') {
                selectedOption.quantity = selectedOption.quantity + 1
                selectedOption.total = selectedOption.unitPrice * selectedOption.quantity
            }
        },
        decreaseOption(buildingOption) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)

            var selectedOption = _.find(buildingOptions, function (el) {
                return el.optionId === buildingOption.optionId
            }, this)

            if (typeof selectedOption !== 'undefined') {
                if (selectedOption.minQuantity >= selectedOption.quantity) return

                selectedOption.quantity = selectedOption.quantity - 1
                selectedOption.total = selectedOption.unitPrice * selectedOption.quantity
            }
        },
        updateOption(buildingOption, params) {
            let buildingOptions = _.get(this, this.alias.buildingOptions)
            let buildingOptionIndex = buildingOptions.findIndex(item => item.optionId === buildingOption.optionId)
            if (buildingOptionIndex === -1) return

            let selectedOption = buildingOptions[buildingOptionIndex]
            let newObject = _.extend({}, selectedOption)

            // color
            if (params.color) {
                newObject.color = _.assign({}, selectedOption.color, params.color)
                delete params['color']
            }

            // parent options
            if (params.parentOptions) {
                newObject.parentOptions = params.parentOptions
                delete params['parentOptions']
            }

            newObject = _.merge(newObject, params)
            // force min quantity
            if (newObject.minQuantity > newObject.quantity) {
                newObject.quantity = newObject.minQuantity
            }

            newObject.total = newObject.unitPrice * newObject.quantity
            this.$set(_.get(this, this.alias.buildingOptions), buildingOptionIndex, newObject)
        }
    }
}

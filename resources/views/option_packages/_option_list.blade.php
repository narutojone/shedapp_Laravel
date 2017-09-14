@section('option-package-option-list')

    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 mt-md mr-md row">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">Options</h4>
            </header>

            <div class="panel-body">
                <button type="button"
                        class="btn btn-primary btn-sm options-add_option_button mb-sm ml-xs"
                        title="Add option">
                    + Add Option
                </button>

                <div id="options-select_container" class="form-inline col-xs-12 row" style="overflow-y: auto; height: 200px">
                    <!-- list of options here -->
                </div>
            </div>

            <div class="panel-footer"></div>
        </section>
    </div>

@endsection

@section('footer-scripts')
    @parent

    <script type="text/template" id="tpl-options-select-wrapper">
        <div class="form-group form-group-sm mb-xs ml-xs ws-nowrap">
            <div class="col-xs-8 pl-none pr-xs">
                <select name="options[]" id="options" class="form-control w-100">
                    <% _.forEach(options, function(option) { %>
                    <option value="<%= option['id'] %>"
                            <% if (option['disabled'] == true) { %> disabled="disabled" <% } %>
                            <% if (option['id'] === selected_option_id) { %> selected="selected" <% } %>
                            data-unit_price="<%= option['unit_price'] %>"><%= option['name'] %></option>
                    <% }); %>
                </select>
            </div>

            <div class="col-xs-3 pl-none pr-xs">
                <input name="options_price[]" type="number" class="form-control w-100
                <% if (selected_price !== false) { %> active_option <% } %>" value="<%= selected_price %>" <% if (selected_price === false) { %> disabled <% } %>>
            </div>

            <div class="col-xs-1 pl-none pr-xs">
                <button type="button" class="btn btn-default btn-xs options-remove_option_button">
                    <i class="fa fa-remove fa-fw"></i>
                </button>
            </div>
        </div>
    </script>

    <?php
    $selected_options = $selected_option_prices = [];
    if( Input::old('options') && is_array(Input::old('options'))) {
        $selected_options = Input::old('options');
        $selected_option_prices = Input::old('options_price');
    } elseif( isset($params['data']['item']) )
    {
        $selected_options = $params['data']['item']->options->pluck('id')->toArray();
        $selected_option_prices = $params['data']['item']->options->map(function($option) {
            return $option->pivot->unit_price;
        })->toArray();
    }
    ?>

    <script type="text/javascript" charset="utf-8">
        (function( $, _ ) {
            'use strict';

            var OPTIONS_DATA = <?= json_encode($params['data']['options']) ?>;
            var OPTIONS_SELECTED_IDS = <?= json_encode($selected_options, JSON_NUMERIC_CHECK); ?>;
            var OPTIONS_SELECTED_PRICES = <?= json_encode($selected_option_prices, JSON_NUMERIC_CHECK); ?>;

            var _template_options_select_wrapper = _.template($('#tpl-options-select-wrapper').html());

            var $options_select_container = $('#options-select_container');
            var $options_add_button = $('.options-add_option_button');
            var $option_package_price = $('.option-package_price');

            var options_remove_button_class = '.options-remove_option_button';

            var attachAddOptionEvent = function(element) {
                element.on('click', function() {
                    buildOptionsList(OPTIONS_DATA, 0, false);
                });
            };

            var attachRemoveOptionEvent = function(element) {
                element.on('click', function() {
                    var $this = $(this);
                    var $options_select_container = $this.parent().parent();
                    var $option_selected = $this.parent().parent().find('select option:selected');
                    $options_select_container.remove();
                    recalculatePrice();
                });
            };

            var attachChangeOptionEvent = function($options_select_wrapper) {
                var $options_select = $options_select_wrapper.find('select');
                var $option_price = $options_select_wrapper.find('input[name="options_price[]"]');
                attachChangePriceEvent($option_price);

                $options_select.on('change', function() {
                    var $this = $(this);
                    var $option_selected = $options_select.find("option:selected");

                    $option_price.addClass('active_option');
                    $option_price.removeAttr('disabled');
                    $option_price.val($option_selected.data('unit_price'));
                    recalculatePrice();
                });
            };

            var attachChangePriceEvent = function(element) {
                element.on('change keyup', function() {
                    recalculatePrice();
                });
            };

            var recalculatePrice = function() {
                var new_price = 0;
                var $selected_prices = $options_select_container.find('input[name="options_price[]"].active_option');
                $.each($selected_prices, function(index, item) {
                    new_price += Number($(item).val());
                });

                $option_package_price.val(new_price);
            };

            var buildOptionsList = function(options_data, selected_option_id, selected_price) {
                var options_select_wrapper = _template_options_select_wrapper({
                    options: options_data,
                    selected_option_id: ( typeof selected_option_id !== 'undefined' ) ? selected_option_id : 0,
                    selected_price: ( typeof selected_price !== 'undefined' ) ? selected_price : false
                });

                var $options_select_wrapper = $(options_select_wrapper);
                var $option_remove_button = $options_select_wrapper.find(options_remove_button_class);

                attachRemoveOptionEvent($option_remove_button);
                attachChangeOptionEvent($options_select_wrapper);

                $options_select_container.append($options_select_wrapper);
            };

            _.each(OPTIONS_SELECTED_IDS, function(option_id, index) {
                var option_price = ( !$.isEmptyObject(OPTIONS_SELECTED_PRICES)
                && OPTIONS_SELECTED_PRICES[index] !== 'undefined' ) ? OPTIONS_SELECTED_PRICES[index] : 0;

                buildOptionsList(OPTIONS_DATA, option_id, option_price);
            });
            attachAddOptionEvent($options_add_button);
            recalculatePrice();

        }).apply(this, [ jQuery, _ ]);
    </script>
@endsection

@yield('option-package-option-list')
@section('status-priorities-table')

    <div class="bootstrap-table">

        <table id="table-status-priorities"
               class="table table-no-bordered"
               data-height="350"
               data-mobile-responsive="true">
            <thead>
                <tr>
                    <th data-field="id" data-sortable="true">Id</th>
                    <th data-field="type">Type</th>
                    <th data-field="name">Name</th>
                    <th data-field="priority" data-sortable="true">Priority</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
@endsection

@section('modal-status-priorities')

    <div id="modal-status-priorities" class="modal-block modal-block-primary mfp-hide {!! $modalSize !!}">
        <section class="panel">

            <header class="panel-heading">
                <h2 class="panel-title">{!! $modalTitle !!}</h2>
            </header>
            <div class="panel-body">
                <!-- START table priorities -->
                @yield('status-priorities-table')
                <!-- END table priorities -->
            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        {!! Form::button('Cancel', ['type' => 'button', 'class' => 'btn btn-default modal-close']) !!}
                    </div>
                </div>
            </footer>

        </section>
    </div>

@endsection

@section('specifics-scripts')
    @parent

    <link href="{{ asset('vendor/bootstrap-table/bootstrap-table.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-editable/css/bootstrap-editable.css') }}" rel="stylesheet">
    
    <script src="{{ asset('vendor/bootstrap-table/bootstrap-table.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-table/editable/bootstrap-table-editable.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-table/mobile/bootstrap-table-mobile.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-editable/js/bootstrap-editable.min.js') }}"></script>

    <script type="text/javascript" charset="utf-8">
        (function( $ ) {
            'use strict';

            var URL = '{{ url('building_statuses') }}';

            var $modal_open = $('.modal-open-status-priorities');
            var $modal_close = $('#modal-status-priorities .modal-close');
            var $modal = $('#modal-status-priorities');
            var $table = $('#table-status-priorities');

            // modal close
            $modal_close.on('click', function (e) {
                e.preventDefault();
                $.magnificPopup.close();
            });

            // modal open
            $modal_open.magnificPopup({
                type: 'inline',
                preloader: false,
                modal: true,
                callbacks: {
                    open: function() {
                        var url = '/api/building-statuses/type/' + $('select[name="type"] option:selected').val();
                        $table.bootstrapTable('refresh', {url: url});
                        $table.bootstrapTable('resetView');
                    }
                }
            });

            $(document).ready(function() {
                $table.bootstrapTable({
                    idField: 'id', // primary key (used for REST request)
                    //url: '/api/building-statuses/type/' + $('select[name="type"] option:selected').val(),
                    columns: [
                        {
                            field: 'id',
                            title: 'Id'
                        },
                        {
                            field: 'type',
                            title: 'Type'
                        },
                        {
                            field: 'name',
                            title: 'Name'
                        },
                        {
                            field: 'priority',
                            title: 'Priority',
                            editable: {
                                type: 'text',
                                inputclass: 'input-sm',
                                url: function(item) {
                                    var deffered = new $.Deferred();

                                    var url = URL + '/' + item.pk,
                                            data = {};
                                    data[item.name] = item.value;

                                    $.ajax({
                                        type: 'PUT',
                                        url: url,
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                                        data: data,
                                        success: function(data, textStatus, jqXHR) {
                                            deffered.resolve();
                                        },

                                        error: function(data, textStatus, jqXHR) {
                                            deffered.reject(data.statusText);
                                        }
                                    });

                                    return deffered.promise();
                                }
                            }
                        }
                    ]
                });
            });

        }).apply(this, [ jQuery ]);
    </script>

@endsection

@yield('modal-status-priorities')
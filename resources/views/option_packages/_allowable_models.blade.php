@section('option-package-allowable-models')

    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 mt-md mr-md row">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">Allowable Models</h4>
            </header>

            <div class="panel-body">

                <div style="overflow-y: auto; height: 200px">

                    <!-- START list of allowable models -->
                    @foreach( $params['data']['allowable_models'] as $allowable_model )
                        <div class="form-group form-group-sm mb-none">

                            <label class="col-sm-12 control-label">
                                {!! Form::checkbox('allowable_models[]', $allowable_model->id) !!} {{ $allowable_model->name }}
                            </label>

                        </div>
                        @endforeach
                    <!-- END list of allowable models -->

                </div>

            </div>

            <div class="panel-footer">
            </div>

        </section>
    </div>

@endsection

@yield('option-package-allowable-models')
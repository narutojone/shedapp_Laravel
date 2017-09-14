@extends('partials._base')

@section('panel-body')
    @include('partials._forms')
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-xs-12 col-md-12">
                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'autofocus' => 'true']) !!}
            </div>

            <div class="col-xs-12 col-md-4">

                {!! Form::label('priority', 'Priority', ['class' => 'control-label']) !!}

                <div class="form-horizontal">
                    <div class="form-group col-xs-5 col-sm-5 col-md-7">
                        {!! Form::number('priority', null, ['class' => 'form-control', 'placeholder' => 'Priority']) !!}
                    </div>

                    <div class="form-group col-xs-7 col-sm-5 col-md-5 ml-xs">
                        <button type="button"
                                class="btn btn-primary btn-sm modal-open-status-priorities"
                                href="#modal-status-priorities">
                            Show Status Priorities
                        </button>
                    </div>

                </div>

            </div>

            <div class="col-xs-12 col-md-3">
                {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
                {!! Form::select('type', $params['data']['status_types'], null, ['class' => 'form-control'] ) !!}
            </div>

            <div class="col-xs-12 col-md-2">
                {!! Form::label('is_active', 'Is Active', ['class' => 'control-label']) !!}
                {!! Form::text('is_active', null, ['class' => 'form-control', 'placeholder' => 'Is Active']) !!}
            </div>
        </div>
    </div>

    <!-- Modal Status priorities -->
    @include('building_statuses._status_priorities', [
            'modalTitle' => 'Status priorities',
            'modalSize' => 'modal-block-sm'
        ])

@endsection


@section('panel-footer')
    <div class="panel-footer clearfix">
        <div class="pull-right">
            <a href="{{ url($params['route']) }}" class="btn btn-default">Cancel</a>
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@endsection

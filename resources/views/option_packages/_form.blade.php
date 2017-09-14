@extends('partials._base')

@section('panel-body')
    @include('partials._forms')
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-xs-12 col-md-12">
                {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Name', 'autofocus' => 'true']) !!}
            </div>
            <div class="col-xs-12 col-md-12">
                {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
                {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
            </div>

            <div class="col-xs-12 col-md-2">
                {!! Form::label('total_price', 'Total Price', ['class' => 'control-label']) !!}
                {!! Form::text('total_price', null, ['class' => 'form-control option-package_price', 'placeholder' => 'Price', 'disabled' => 'disabled']) !!}
            </div>
        </div>
    </div>

    @include('option_packages._option_list')
    @include('option_packages._allowable_models')

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

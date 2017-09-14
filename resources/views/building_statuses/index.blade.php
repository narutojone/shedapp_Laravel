@extends('partials._lists')

@section('panel-tools')

        <form action="" id="searchForm" method="GET" accept-charset="UTP-8" class="form-inline pull-right">

            <div class="form-group">
                {!! Form::select('status_type', [null => 'All'] + $params['data']['status_types'], $params['status_type'], ['class' => 'form-control', 'onchange' => 'this.form.submit()'] ) !!}
            </div>

            @if (isset($params['search']) AND strtolower($params['search']) == 'yes')
                <div class="form-group">
                    <div class="input-group input-search" style="width:200px;">
                        <input type="search" name="filter" class="form-control input-sm" style="width:200px;" value="{{ $params['filter'] }}" placeholder="Search..." autofocus="true">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
            @endif
        </form>
@endsection


@section('table-items')
    <thead>
        <tr>
            <th class="col-md-1 text-center">ID</th>
            <th class="col-md-1 text-center">Type</th>
            <th class="col-md-1 text-center">Priority</th>
            <th>Name</th>
            <th>Is Active</th>
            <th class="col-lg-1 col-md-1 col-sm-2 col-xs-1 text-center"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($params['data']['items'] as $item)
        <tr>
            <td class="text-center">{{ $item->id }}</td>
            <td class="text-center">{{ ucfirst($item->type) }}</td>
            <td class="text-center">{{ $item->priority }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->is_active }}</td>
            <td class="text-center actions">
                <!-- Edit -->
                {{-- @if(Auth::user()->ability('sysadmin', $params['route_name'].'-edit')) --}}
                    <a href="{{ url($params['route'], [$item->id, 'edit']) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                {{-- @endif --}}

                <!-- Delete -->
                {{-- @if(Auth::user()->ability('sysadmin', $params['route_name'].'-delete')) --}}
                    <a class="confirmation delete-row" href="#modalWindow" title="Delete" data-id="{{ $item->id }}"><i class="fa fa-trash-o"></i></a>
                {{-- @endif --}}
            </td>
        </tr>
        @endforeach

        @if (count($params['data']['items']) == 0)
        <tr>
            <td colspan="5" valign="top" class="dataTables_empty h5 text-primary">No records found</td>
        </tr>
        @endif
    </tbody>
@endsection

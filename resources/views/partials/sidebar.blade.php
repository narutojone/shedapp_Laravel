<aside id="sidebar-left" class="sidebar-left">

	<div class="sidebar-header">
		<div class="sidebar-title">
			Menú
		</div>
		<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>

	<div class="nano">
		<div class="nano-content">
			<nav id="menu" class="nav-main" role="navigation">
				<?php $page = isset($params['breadcrumbs'][1])?$params['breadcrumbs'][1]['page']:"none"; ?>
				<ul class="nav nav-main">
					<li {!! (empty($params['breadcrumbs']))?'class="nav-active"':'' !!} >
						<a href="{{ url('/') }}">
							<i class="fa fa-home" aria-hidden="true"></i>
							<span>Dashboard</span>
						</a>
					</li>
					@if(Auth::user()->hasRole('administrator'))
                    {{-- @if(Auth::user()->ability('sysadmin', 'ctas_ctes-list')) --}}
                    <li {!! ($params['breadcrumbs'][0]['page'] == 'Buildings') ? 'class="nav-active"' : '' !!} >
						<a href="{{ url('buildings/#/') }}">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                            <span>Buildings</span>
                        </a>
                    </li>
                    {{-- @if(Auth::user()->ability('sysadmin', 'ctas_ctes-list')) --}}
                    <li {!! ($params['breadcrumbs'][0]['page'] == 'Orders') ? 'class="nav-active"' : '' !!} >
                        <a href="{{ url('orders/#/') }}">
							<i class="fa fa-file-text-o" aria-hidden="true"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    {{-- @if(Auth::user()->ability('sysadmin', 'ctas_ctes-list')) --}}
                    <li {!! ($params['breadcrumbs'][0]['page'] == 'Sales') ? 'class="nav-active"' : '' !!} >
                        <a href="{{ url('sales/#/') }}">
							<i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Sales</span>
                        </a>
                    </li>
                    {{-- @endif --}}
                    {{-- @if(Auth::user()->ability('sysadmin', 'ctas_ctes-list')) --}}
                    <li {!! ($params['breadcrumbs'][0]['page'] == 'Deliveries') ? 'class="nav-active"' : '' !!} >
                        <a href="{{ url('deliveries/#/') }}">
                            <i class="fa fa-truck" aria-hidden="true"></i>
                            <span>Deliveries</span>
                        </a>
                    </li>
                    {{-- @endif --}}
					<li class="nav-parent {!! ($params['breadcrumbs'][0]['page'] == 'Bills')?'nav-active nav-expanded':'' !!}">
						<a>
							<i class="fa fa-bar-chart" aria-hidden="true"></i>
							<span>Reports</span>
						</a>
						<ul class="nav nav-children">
							<li><a href="{{ url('reports/#/dealer-inventory/') }}">Dealer Inventory</a></li>
							<li><a href="{{ url('reports/#/orders/') }}">Orders</a></li>
							<li><a href="{{ url('reports/#/sales/') }}">Sales</a></li>
						</ul>
					</li>

					{{-- @if(Auth::user()->ability('sysadmin', 'ctas_ctes-list')) --}}
					<li {!! ($params['breadcrumbs'][0]['page'] == 'Expenses') ? 'class="nav-active"' : '' !!} >
						<a href="{{ url('expenses/report') }}">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>
							<span>Expenses</span>
						</a>
					</li>
					{{-- @endif --}}
					<li {!! ($params['breadcrumbs'][0]['page'] == 'Employees') ? 'class="nav-active"' : '' !!} >
						<a href="{{ url('employees') }}">
							<i class="fa fa-users" aria-hidden="true"></i>
							<span>Employees</span>
						</a>
					</li>

					<li class="nav-parent {!! ($params['breadcrumbs'][0]['page'] == 'Bills')?'nav-active nav-expanded':'' !!}">
						<a>
							<i class="fa fa-usd" aria-hidden="true"></i>
							<span>Bills</span>
						</a>
						<ul class="nav nav-children">
							{{-- @if(Auth::user()->ability('sysadmin', 'personas-list')) --}}
							<li {!! ($page == 'Create') ? 'class="nav-active"' : '' !!}><a href="{{ url('bills/create') }}">Create</a></li>
							{{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
							<li {!! ($page == 'Bill Report') ? 'class="nav-active"' : '' !!}><a href="{{ url('bills/report') }}">Report</a></li>
							{{-- @endif --}}
						</ul>
					</li>

					{{-- @if(Auth::user()->ability('sysadmin', 'productos-list, rubros-list, personas-list, roles-list, unidades_de_negocio-list')) --}}
					<li class="nav-parent {!! ($params['breadcrumbs'][0]['page'] == 'Mantenimiento')?'nav-active nav-expanded':'' !!}">
						<a>
							<i class="fa fa-cogs" aria-hidden="true"></i>
							<span>System</span>
						</a>
						<ul class="nav nav-children">
							{{-- @if(Auth::user()->ability('sysadmin', 'personas-list')) --}}
							<li {!! ($page == 'Settings') ? 'class="nav-active"' : '' !!}><a href="{{ url('settings') }}">Settings</a></li>
							{{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'personas-list')) --}}
							<li {!! ($page == 'Building Models') ? 'class="nav-active"' : '' !!}><a href="{{ url('building-models/#/') }}">Building Models</a></li>
							{{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
							<li {!! ($page == 'Building Statuses') ? 'class="nav-active"' : '' !!}><a href="{{ url('building_statuses') }}">Building Statuses</a></li>
							{{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
							<li {!! ($page == 'Building Packages') ? 'class="nav-active"' : '' !!}><a href="{{ url('building-packages/#/') }}">Building Packages</a></li>
							{{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
							<li {!! ($page == 'Building Package Categories') ? 'class="nav-active"' : '' !!}><a href="{{ url('building-package-categories/#/') }}">Building Package Categories</a></li>
							{{-- @endif --}}
                            {{-- @if(Auth::user()->ability('sysadmin', 'productos-list')) --}}
                            <li {!! ($page == 'Plants') ? 'class="nav-active"' : '' !!}><a href="{{ url('plants') }}">Plants</a></li>
                            {{-- @endif --}}
                            {{-- @if(Auth::user()->ability('sysadmin', 'productos-list')) --}}
                            <li {!! ($page == 'Styles') ? 'class="nav-active"' : '' !!}><a href="{{ url('styles') }}">Styles</a></li>
                            {{-- @endif --}}
                            <li {!! ($page == 'Materials') ? 'class="nav-active"' : '' !!}><a href="{{ url('materials') }}">Materials</a></li>
                            <li {!! ($page == 'Material Categories') ? 'class="nav-active"' : '' !!}><a href="{{ url('material_categories') }}">Material Categories</a></li>
                            {{-- @if(Auth::user()->ability('sysadmin', 'productos-list')) --}}
                            <li {!! ($page == 'Colors') ? 'class="nav-active"' : '' !!}><a href="{{ url('colors') }}">Colors</a></li>
                            {{-- @endif --}}
                            {{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
                            <li {!! ($page == 'Options') ? 'class="nav-active"' : '' !!}><a href="{{ url('options/#/') }}">Options</a></li>
							<li {!! ($page == 'Option Categories') ? 'class="nav-active"' : '' !!}><a href="{{ url('option-categories/#/') }}">Option Categories</a></li>
                            {{-- @endif --}}
							{{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
							<li {!! ($page == 'Option Packages') ? 'class="nav-active"' : '' !!}><a href="{{ url('option_packages') }}">Option Packages</a></li>
							{{-- @endif --}}
                            {{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
                            <li {!! ($page == 'Locations') ? 'class="nav-active"' : '' !!}><a href="{{ url('locations') }}">Locations</a></li>
                            {{-- @endif --}}
                            {{-- @if(Auth::user()->ability('sysadmin', 'rubros-list')) --}}
                            <li {!! ($page == 'Dealers') ? 'class="nav-active"' : '' !!}><a href="{{ url('dealers/#/') }}">Dealers</a></li>
                            {{-- @endif --}}
						</ul>
					</li>
					{{-- @endif --}}
					@endif
				</ul>
			</nav>

			<hr class="separator" />
		</div>
	</div>
</aside>

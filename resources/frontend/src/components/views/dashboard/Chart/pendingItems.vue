<template>
<div class="row push-col-md-2">
	<div class="col-md-6">
		<div class="col-xs-12 col-md-12 col-lg-12 stats">
			<h3 class="text-center">Items Pending</h3>
			<table class="rwd-table">
				<tr>
					<th style="colspan:2">&nbsp;</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Total Retail</th>
				</tr>
				<tr>
					<td class="text-right">Submitted Order:</td>
					<td class="text-center">44</td>
					<td class="text-center">$207,900.00</td>
				</tr>
				<tr>
					<td class="text-right">Orders Needing Review:</td>
					<td class="text-center">31</td>
					<td class="text-center">$158,875.00</td>
				</tr>
				<tr>
					<td class="text-right">Open Sales:</td>
					<td class="text-center">12</td>
					<td class="text-center">$ 61,500.00</td>
				</tr>
				<tr>
					<td class="text-right">Pending Buildings:</td>
					<td class="text-center">13</td>
					<td class="text-center">$ 63,050.00</td>
				</tr>
				<tr>
					<td class="text-right">Buildings in production:</td>
					<td class="text-center">13</td>
					<td class="text-center">$ 63,050.00</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-md-6">
		<div class="col-xs-12 col-md-12 col-lg-12 stats">
			<h3 class="text-center">April 2017 (To Date)</h3>
			<table class="rwd-table">
				<tr>
					<th style="colspan:2">&nbsp;</th>
					<th class="text-center">Quantity</th>
					<th class="text-center">Total Retail</th>
				</tr>
				<tr>
					<td class="text-right">Quotes generated:</td>
					<td class="text-center">44</td>
					<td class="text-center">$207,900.00</td>
				</tr>
				<tr>
					<td class="text-right">Invoiced Sales:</td>
					<td class="text-center">31</td>
					<td class="text-center">$158,875.00</td>
				</tr>
				<tr>
					<td class="text-right">Buildings Ready to Deliver:</td>
					<td class="text-center">12</td>
					<td class="text-center">$ 61,500.00</td>
				</tr>
				<tr>
					<td class="text-right">Building Delivered:</td>
					<td class="text-center">13</td>
					<td class="text-center">$ 63,050.00</td>
				</tr>
			</table>
		</div>
	</div>
</div>
</template>

<script type="text/babel">
    // import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    // import apiDashboard from 'src/api/dashboard'
    
    export default {
        // extends: BaseDataItem,
        name: 'counts-list-page',
        components: {},
        computed: {},
        methods: {
            /*
            initData() {
                this.run({text: 'Loading..'})
                apiDashboard.getcounts().then(response => {
                    this.$emit('data-ready')
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            }
            */
        }
    }
</script>
<style type="text/sass" lang="scss" rel="stylesheet/scss" scoped>
	.stats {
		// border: 1px solid #ddd;
		background: #f7f7f9;
	}

	.rwd-table {
		width: 100%;
		margin: 1em 0;
		background: #34495E;
		color: #fff;
		border-radius: .4em;
		overflow: hidden;

		tr {
			border-top: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
			border-color: lighten(#34495E, 10%);
		}

		th, td {
			display: table-cell;
			padding: .25em .5em;
			margin: .5em 1em;

			&:first-child {
				padding-left: 0;
			}

			&:last-child {
				padding-right: 0;
			}
		}

		th, td:before {
			color: #db7730;
		}
	}

	h1 {
		font-weight: normal;
		letter-spacing: -1px;
		color: #34495E;
	}
</style>
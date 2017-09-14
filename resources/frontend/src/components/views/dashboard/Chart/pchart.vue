<template>
<div class="row">
  <div class="col-xs-12 col-sm-12">
    <vue-chart type="bar" :data="chartData" :options="options" :height="200"></vue-chart>
  </div>
</div>

</template>
 
<script type="text/babel">
import VueChart from 'vue-chart-js'
export default {
  name: 'production-chart',
  components: {
    VueChart
  },
  data: () => ({
    chartData: {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
        datasets: [
        {
        label: 'Daily Throughput (Mar 2017)',
        type: 'bar',
        yAxesID: 'A',
        data: [9597.30, 3845.00, 7480.00, 2920.00, 5175.50, 3807.50, 2252.50, 2052.50, 2415.00, 4003.50, 6917.50, 3340.00, 7261.50, 5922.50, 3202.50, 1509.50, 3795.00, 6330.50],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#A2C3F0',
        backgroundColor: '#A2C3F0',
        borderWidth: 2
      },
      {
        label: 'Daily Throughput (Apr 2017)',
        type: 'bar',
        yAxesID: 'C',
        data: [5897.50, 3866.50, 7695.00, 0.00, 867.50, 4005.00, 5057.50, 2132.50, 7370.50, 0.00, 0.00, 6886.00, 4430.00, 2932.50, 0.00, 3862.00, 1650.00, 9742.00, 0.00, 2487.50],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#4688E1',
        backgroundColor: '#4688E1',
        borderWidth: 2
      }, {
        label: 'Cumulative Throughput (Mar 2017)',
        type: 'line',
        yAxesID: 'B',
        data: [9597.30, 13442.30, 20922.30, 23842.30, 29017.80, 32825.30, 35077.80, 37130.30, 39545.30, 43548.80, 50466.30, 53806.30, 61067.80, 66990.30, 70192.80, 71702.30, 75497.30, 81827.80],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#A2C3F0',
        backgroundColor: 'transparent',
        borderWidth: 2
      }, {
        label: 'Cumulative Throughput (Apr 2017)',
        type: 'line',
        yAxesID: 'D',
        data: [5897.50, 9764.00, 17459.00, 17459.00, 18326.50, 22331.50, 27389.00, 29521.50, 36892.00, 36892.00, 36892.00, 43778.00, 48208.00, 51140.50, 51140.50, 55002.50, 56652.50, 66394.50, 66394.50, 68882.00],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#4688E1',
        backgroundColor: 'transparent',
        borderWidth: 2
      }]
    },
    options: {
            responsive: true,
            legend: {
                position: 'top'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Days'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '$'
                    }
                }]
            },
            title: {
                display: true,
                text: 'Throughput'
            }
        }
  })
  }
</script>
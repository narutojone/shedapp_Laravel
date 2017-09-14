<template>

<div class="row">
  <div class="col-xs-12 col-sm-12">
    <vue-chart type="bar" :data="chartData" :options="options" :height="200"></vue-chart>
  </div>
</div>

</template>
 
<script>
import VueChart from 'vue-chart-js'
export default {
  name: 'sale-chart',
  components: {
    VueChart
  },
  height: '300',
  data: () => ({
    chartData: {
        labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
        datasets: [
        {
        label: 'Daily Sales (Mar 2017)',
        type: 'bar',
        yAxesID: 'A',
        data: [0.00, 7250.00, 5490.00, 12232.00, 7145.00, 41858.00, 4200.00, 2160.00, 7645.00, 24959.00, 0.00, 3945.00, 7600.00, 70541.00, 4130.00, 10700.00, 12639.00, 10675.00],
        fillColor: 'rgba(220,220,220,0.2)',
        strokeColor: 'rgba(220,220,220,1)',
        pointColor: 'rgba(220,220,220,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        borderColor: '#B4D6AC',
        backgroundColor: '#B4D6AC',
        borderWidth: 2
      }, {
        label: 'Daily Sales (Apr 2017)',
        type: 'bar',
        yAxesID: 'C',
        data: [8615.00, 18462.00, 5100.00, 9100.00, 1735.00, 0.00, 2160.00, 0.00, 12455.00, 11255.00, 41361.00, 0.00, 11945.00, 6860.00, 1370.00, 21015.00],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#33752E',
        backgroundColor: '#33752E',
        borderWidth: 2
      }, {
        label: 'Cumulative Sales (Mar 2017)',
        type: 'line',
        yAxesID: 'B',
        data: [0.00, 7250.00, 12740.00, 24972.00, 32117.00, 73975.00, 78175.00, 80335.00, 87980.00, 112939.00, 112939.00, 116884.00, 124484.00, 195025.00, 199155.00, 209855.00, 222494.00, 233169.00],
        fillColor: 'rgba(151,187,205,0.2)',
        strokeColor: 'rgba(151,187,205,1)',
        pointColor: 'rgba(151,187,205,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(151,187,205,1)',
        borderColor: '#B4D6AC',
        backgroundColor: 'transparent',
        borderWidth: 2
      }, {
        label: 'Cumulative Sales (Apr 2017)',
        type: 'line',
        yAxesID: 'D',
        data: [ 8615.00, 27077.00, 32177.00, 41277.00, 43012.00, 43012.00, 45172.00, 45172.00, 57627.00, 68882.00, 110243.00, 110243.00, 122188.00, 129048.00, 130418.00, 151433.00 ],
        fillColor: '#33752E',
        strokeColor: '#33752E',
        pointColor: '#33752E',
        pointStrokeColor: '#33752E',
        pointHighlightFill: '#33752E',
        pointHighlightStroke: '#33752E',
        borderColor: '#33752E',
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
                text: 'Sales'
            }
        }
  })
}
</script>
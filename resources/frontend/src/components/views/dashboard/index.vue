<template>
    <div class="row">
    <!-- <div class="col-xs-12 col-sm-12">
        <count-stats></count-stats>
    </div>
    <div class="col-xs-12 col-sm-12">
        <label>Items Pending <i class="fa fa-hand-o-down" aria-hidden="true"></i></label>
        <pending-counts></pending-counts>
    </div> -->
    <div class="col-xs-12">
        <pending-items></pending-items>
    </div>
    <div class="col-xs-12 col-sm-6">
        <chart></chart>
    </div> 

    <div class="col-xs-12 col-sm-6">
        <pchart></pchart>
    </div>
    </div>
</template>

<script type="text/babel">
    import chart from './Chart/chart.vue'
    import pchart from './Chart/pchart.vue'
    import countStats from './Chart/counts.vue'
    import pendingCounts from './Chart/pcounts.vue'
    import pendingItems from './Chart/pendingItems'
    export default {
        data() {
            return {
            }
        },
        components: {
            chart,
            pchart,
            countStats,
            pendingCounts,
            pendingItems
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .iframe {
    }
</style>
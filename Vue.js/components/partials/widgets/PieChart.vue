<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">{{ cardTitle }} {{translate("status", "Status")}}</h4>
      <!-- Pie Chart -->
      <apexchart
        class="apex-charts"
        height="320"
        type="pie"
        dir="ltr"
        :series="chart.series"
        :options="chart.chartOptions"
      ></apexchart>
    </div>
  </div>
</template>

<script>
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator';

    export default {
        data() {
            return {
                chart : {
                    series: [100, 0],
                    chartOptions: {
                        labels: ['Active', 'InActive'],
                        colors: ['#34c38f', '#556ee6'],
                        legend: {
                            show: true,
                            position: 'bottom',
                            horizontalAlign: 'center',
                            verticalAlign: 'middle',
                            floating: false,
                            fontSize: '14px',
                            offsetX: 0,
                            offsetY: -10,
                        },
                        responsive: [{
                            breakpoint: 600,
                            options: {
                                chart: {
                                    height: 240,
                                },
                                legend: {
                                    show: false,
                                },
                            },
                        }],
                    },
                },
                cardTitle : '',
            };
        },
        props: ['data', 'title', 'colors'],
        watch: {
            // @ts-ignore
            data() {
              this.$bus.$on('pieload', () => {
                this.loadData();
              });
            },
        },
        methods: {
            loadData() {
                this.chart = {
                    series: this.data,
                    chartOptions: {
                        labels: [
                            'Active ' + this.title,
                            'InActive ' + this.title,
                        ],
                        colors: this.colors,
                        legend: {
                            show: true,
                            position: 'bottom',
                            horizontalAlign: 'center',
                            verticalAlign: 'middle',
                            floating: false,
                            fontSize: '14px',
                            offsetX: 0,
                            offsetY: -10,
                        },
                        responsive: [{
                            breakpoint: 600,
                            options: {
                                chart: {
                                    height: 240,
                                },
                                legend: {
                                    show: false,
                                },
                            },
                        }],
                    },
                };
            },
        },
    };
</script>

<style scoped>

</style>

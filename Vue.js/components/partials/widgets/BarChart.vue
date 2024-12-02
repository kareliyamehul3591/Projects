<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">{{translate("most-watched", "Most Watched")}} {{title}}</h4>
      <!-- Column with Data Labels -->
      <apexchart
        class="apex-charts"
        height="350"
        type="bar"
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
                chart: {
                    series: [{
                        name: 'Time',
                        data: [],
                    }],
                    chartOptions: {
                        chart: {
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top', // top, center, bottom
                                },
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (val) => {
                                return val + ' seconds';
                            },
                            offsetY: -20,
                            style: {
                                fontSize: '12px',
                                colors: ['#304758'],
                            },
                        },
                        colors: ['#556ee6'],
                        grid: {
                            borderColor: '#f1f1f1',
                        },
                        xaxis: {
                            categories: [],
                            position: 'top',
                            labels: {
                                offsetY: -18,
                            },
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            crosshairs: {
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        colorFrom: '#D8E3F0',
                                        colorTo: '#BED1E6',
                                        stops: [0, 100],
                                        opacityFrom: 0.4,
                                        opacityTo: 0.5,
                                    },
                                },
                            },
                            tooltip: {
                                enabled: true,
                                offsetY: -35,
                            },
                        },
                        fill: {
                            gradient: {
                                shade: 'light',
                                type: 'horizontal',
                                shadeIntensity: 0.25,
                                gradientToColors: undefined,
                                inverseColors: true,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [50, 0, 100, 100],
                            },
                        },
                        yaxis: {
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            labels: {
                                show: false,
                                formatter: (val) => {
                                    let minutes = Math.floor(val / 60);
                                    let secondsleft = val % 60;
                                    if (minutes < 10) {
                                        minutes = '0'.minutes;
                                    }
                                    if (secondsleft < 10) {
                                        secondsleft = '0'.secondsleft;
                                    }
                                    let output = '';
                                    if (minutes) {
                                        output = minutes + ' minutes ';
                                    }
                                    if (secondsleft) {
                                        output = output + secondsleft + ' seconds';
                                    }
                                    return output;
                                },
                            },

                        },
                        title: {
                            text: 'Most Watched Movies',
                            floating: true,
                            offsetY: 320,
                            align: 'center',
                            style: {
                                color: '#444',
                            },
                        },
                    },
                },
            };
        },
        props: ['title', 'subtitle', 'colors', 'list'],
        watch: {
            // @ts-ignore
            list() { // watch it
                this.loadData();
            },
        },
        methods: {
            loadData() {
                this.chart = {
                    series: [{
                        name: 'Time',
                        data: [],
                    }],
                    chartOptions: {
                        chart: {
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    position: 'top', // top, center, bottom
                                },
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (val) => {
                                return val + ' seconds';
                            },
                            offsetY: -20,
                            style: {
                                fontSize: '12px',
                                colors: ['#304758'],
                            },
                        },
                        colors: this.colors,
                        grid: {
                            borderColor: '#f1f1f1',
                        },
                        xaxis: {
                            categories: [],
                            position: 'top',
                            labels: {
                                offsetY: -18,
                            },
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            crosshairs: {
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        colorFrom: '#D8E3F0',
                                        colorTo: '#BED1E6',
                                        stops: [0, 100],
                                        opacityFrom: 0.4,
                                        opacityTo: 0.5,
                                    },
                                },
                            },
                            tooltip: {
                                enabled: true,
                                offsetY: -35,
                            },
                        },
                        fill: {
                            gradient: {
                                shade: 'light',
                                type: 'horizontal',
                                shadeIntensity: 0.25,
                                gradientToColors: undefined,
                                inverseColors: true,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [50, 0, 100, 100],
                            },
                        },
                        yaxis: {
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            labels: {
                                show: false,
                                formatter: (val) => {
                                    let minutes = Math.floor(val / 60);
                                    let secondsleft = val % 60;
                                    if (minutes < 10) {
                                        minutes = '0'.minutes;
                                    }
                                    if (secondsleft < 10) {
                                        secondsleft = '0'.secondsleft;
                                    }
                                    let output = '';
                                    if (minutes) {
                                        output = minutes + ' minutes ';
                                    }
                                    if (secondsleft) {
                                        output = output + secondsleft + ' seconds';
                                    }
                                    return output;
                                },
                            },

                        },
                        title: {
                            text: 'Most Watched ' + this.subtitle,
                            floating: true,
                            offsetY: 320,
                            align: 'center',
                            style: {
                                color: '#444',
                            },
                        },
                    },
                };
                this.chart.series[0].data = [];
                this.chart.chartOptions.xaxis.categories = [];
                for (const index in this.list) {
                    if (index && this.list[index]) {
                        this.chart.series[0].data.push(this.list[index].totalTime);
                        this.chart.chartOptions.xaxis.categories.push(this.translate(this.list[index].name, this.list[index].name));
                    }
                }
            },
        },
    };
</script>


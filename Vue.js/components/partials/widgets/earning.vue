<script>
/**
 * Monthly-Earning component
 */
export default {
  data() {
    return {
      chartOptions: {
        chart: {
          offsetY: -10,
        },
        plotOptions: {
          radialBar: {
            startAngle: -135,
            endAngle: 135,
            dataLabels: {
              name: {
                fontSize: '13px',
                color: undefined,
                offsetY: 60,
              },
              value: {
                offsetY: 22,
                fontSize: '16px',
                color: undefined,
                formatter(val) {
                  return val + '%';
                },
              },
            },
          },
        },
        colors: ['#556ee6'],
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'dark',
            shadeIntensity: 0.15,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91],
          },
        },
        stroke: {
          dashArray: 4,
        },
        labels: ['Series A'],
      },
      series: [67],
    };
  },
};
</script>

<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-4">{{translate("monthly-earning","Monthly Earning")}}</h4>
      <div class="row">
        <div class="col-sm-6">
          <p class="text-muted">{{translate("this-month","This month")}}</p>
          <h3>$34,252</h3>
          <p class="text-muted">
            <span class="text-success mr-2">
              12%
              <i class="mdi mdi-arrow-up"></i>
            </span> {{translate("from-previous-period","From previous period")}}
          </p>

          <div class="mt-4">
            <a href="javascript: void(0);" class="btn btn-primary btn-sm">
              {{translate("view-more","View More")}}
              <i class="mdi mdi-arrow-right ml-1"></i>
            </a>
          </div>
        </div>
        <div class="col-sm-6">
          <!-- Chart -->
          <apexchart
            class="apex-charts"
            type="radialBar"
            height="250"
            dir="ltr"
            :series="series"
            :options="chartOptions"
          ></apexchart>
        </div>
      </div>
      <p class="text-muted mb-0">{{translate("we-craft-digital,-graphic-and-dimensional-thinking.","We craft digital, graphic and dimensional thinking.")}}</p>
    </div>
  </div>
</template>

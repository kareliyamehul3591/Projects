<script>
/**
 * Email-sent component
 */
export default {
  data() {
    return {
      series: [
        {
          name: 'Series A',
          data: [44, 55, 41, 67, 22, 43, 36, 52, 24, 18, 36, 48],
        },
        {
          name: 'Series B',
          data: [13, 23, 20, 8, 13, 27, 18, 22, 10, 16, 24, 22],
        },
        {
          name: 'Series C',
          data: [11, 17, 15, 15, 21, 14, 11, 18, 17, 12, 20, 18],
        },
      ],
      chartOptions: {
        chart: {
          stacked: true,
          toolbar: {
            show: false,
          },
          zoom: {
            enabled: true,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '15%',
            endingShape: 'rounded',
          },
        },
        dataLabels: {
          enabled: false,
        },
        xaxis: {
          categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
          ],
        },
        colors: ['#556ee6', '#f1b44c', '#34c38f'],
        legend: {
          position: 'bottom',
        },
        fill: {
          opacity: 1,
        },
      },
    };
  },
};
</script>
<template>
  <div class="card">
    <div class="card-body">
      <div class="float-right">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link" href="javascript: void(0);">{{translate("week","Week")}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript: void(0);">{{translate("month","Month")}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="javascript: void(0);">{{translate("year","Year")}}</a>
          </li>
        </ul>
      </div>
      <h4 class="card-title mb-4">{{translate("email-sent","Email Sent")}}</h4>

      <apexchart
        class="apex-charts"
        type="bar"
        dir="ltr"
        height="362"
        :series="series"
        :options="chartOptions"
      ></apexchart>
    </div>
  </div>
</template>

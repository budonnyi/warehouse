/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

/* global moment:false, Chart:false, Sparkline:false */

$(function () {
  'use strict'

  // Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  // var salesGraphChartData = {
  //   labels: ['2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023', ' Q1', ' Q2'],
  //   datasets: [
  //     {
  //       label: 'Digital Goods',
  //       fill: false,
  //       borderWidth: 2,
  //       lineTension: 0,
  //       spanGaps: true,
  //       borderColor: '#efefef',
  //       pointRadius: 3,
  //       pointHoverRadius: 7,
  //       pointColor: '#efefef',
  //       pointBackgroundColor: '#efefef',
  //       data: [2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
  //     }
  //   ]
  // }

  var salesGraphChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData,
    options: salesGraphChartOptions
  })
})

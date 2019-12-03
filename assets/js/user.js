//css

//js
import $ from 'jquery';
import 'bootstrap';
import moment from 'moment';
import Chart from 'chart.js';
import 'chartjs-plugin-annotation';

$('.team-pane').on('shown.bs.tab', function (e) {
    setCharts($(e.target))
});

function setCharts(element) {
    element = $("#" + element.attr('aria-controls'));
    $(element).find(".tag-card").each(function () {

        var unity = $(this).data('unity');
        var tag = $(this).data('tag');
        var ctx = $(this).find(".graph-canvas");

        var yValues = []; //set the labels with moment 
        $(this).find(".y-value").each(function () {
            yValues.push(parseInt($(this).text(),10));
        });

        var xValues = []; //set the data value 
        $(this).find(".x-value").each(function () {
            xValues.push(new moment($.trim($(this).text())));
        });

        var data = []
        var i;
        for (i=0; i < xValues.length; i++){
            data.push({
                x: xValues[i],
                y: yValues[i]
            });
        }

        //look for the maximum


        data.sort(keysrt('x'));

        //create the chart
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    data: data,
                    borderColor: "#3e95cd",
                    fill: false
                }]
            },
            options: {
                devicePixelRatio: 2,
                legend: {
                    display: false
                },
                responsive: true,
                title: {
                    display: true,
                    text: tag
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Dates'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: unity
                        }
                    }]
                }
            }
        });
    });
}

function keysrt(key) {
    return function(a,b){
     return a[key].unix() - b[key].unix();
    }
  }




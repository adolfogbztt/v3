'use strict';
/** 
  * controllers used for the dashboard
*/
app.controller('SparklineCtrl', ["$scope", function ($scope) {
    $scope.sales = [600, 923, 482, 1211, 490, 1125, 1487];
    $scope.earnings = [400, 650, 886, 443, 502, 412, 353];
    $scope.referrals = [4879, 6567, 5022, 5890, 9234, 7128, 4811];
}]);
app.controller('VisitsCtrl', ["$scope", function ($scope) {

    $scope.data = {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        datasets: [
            {
                label: 'Ventas Mensuales',
                backgroundColor: 'rgba(11, 171, 15, 0.2)',
                borderColor: 'rgba(11, 171, 15, 1)',
                pointColor: 'rgba(11, 171, 15, 1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(11, 171, 15, 1)',
                lineTension: 0,
                data: [1000, 2000, 1500, 5000, 2500, 3500, 900, 1250, 4000, 1750, 2000, 3000]
            },
            {
                label: 'Compras Mensuales',
                backgroundColor: 'rgba(213, 0, 0, 0.2)',
                borderColor: 'rgba(213, 0, 0, 1)',
                pointColor: 'rgba(213, 0, 0, 1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(213, 0, 0, 1)',
                lineTension: 0,
                data: [500, 1500, 1000, 3500, 2000, 3000, 500, 900, 2500, 1300, 1500, 2500]
            },
            {
                label: 'Margen de Utilidad',
                backgroundColor: 'rgba(0, 59, 223, 0.2)',
                borderColor: 'rgba(0, 59, 223, 1)',
                pointColor: 'rgba(0, 59, 223, 1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(0, 59, 223, 1)',
                lineTension: 0,
                data: [1250, 1750, 1250, 4500, 2250, 3250, 700, 1150, 1450, 130, 1250, 2725]
            }
        ]
    };

    $scope.options = {
        maintainAspectRatio: false,
        // Sets the chart to be responsive
        responsive: true,
        ///Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - Whether the line is curved between points
        bezierCurve: false,
        //Number - Tension of the bezier curve between points
        bezierCurveTension: 0.4,
        //Boolean - Whether to show a dot for each point
        pointDot: true,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 4,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a colour
        datasetFill: true,
        // Function - on animation progress
        onAnimationProgress: function () { },
        // Function - on animation complete
        onAnimationComplete: function () { },
        //String - A legend template
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            // Return the HTML string here.
            var legendTemplate = '<ul class="tc-chart-js-legend">';
            for (var i = 0; i < chart.legend.legendItems.length; i++) {
                var legendItem = chart.legend.legendItems[i];
                legendTemplate = legendTemplate + '<li><span style="background-color:' + legendItem.strokeStyle + '"></span>' + legendItem.text + '</li>';
            }
            legendTemplate = legendTemplate + '</ul>';
            return legendTemplate;
        }
    };

}]);
app.controller('SalesCtrl', ["$scope", function ($scope) {

    // Chart.js Data
    $scope.data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
            {
                label: 'My First dataset',
                backgroundColor: 'rgba(220,220,220,0.5)',
                borderColor: 'rgba(220,220,220,0.8)',
                highlightFill: 'rgba(220,220,220,0.75)',
                highlightStroke: 'rgba(220,220,220,1)',
                data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: 'My Second dataset',
                backgroundColor: 'rgba(151,187,205,0.5)',
                borderColor: 'rgba(151,187,205,0.8)',
                highlightFill: 'rgba(151,187,205,0.75)',
                highlightStroke: 'rgba(151,187,205,1)',
                data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };

    // Chart.js Options
    $scope.options = {
        maintainAspectRatio: false,
        // Sets the chart to be responsive
        responsive: true,
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero: true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines: true,
        //String - Colour of the grid lines
        scaleGridLineColor: "rgba(0,0,0,.05)",
        //Number - Width of the grid lines
        scaleGridLineWidth: 1,
        //Boolean - If there is a stroke on each bar
        barShowStroke: true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth: 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing: 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing: 1,
        //String - A legend template
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            // Return the HTML string here.
            var legendTemplate = '<ul class="tc-chart-js-legend">';
            for (var i = 0; i < chart.legend.legendItems.length; i++) {
                var legendItem = chart.legend.legendItems[i];
                legendTemplate = legendTemplate + '<li><span style="background-color:' + legendItem.strokeStyle + '"></span>' + legendItem.text + '</li>';
            }
            legendTemplate = legendTemplate + '</ul>';
            return legendTemplate;
        }
    };

}]);
app.controller('OnotherCtrl', ["$scope", function ($scope) {

    // Chart.js Data
    $scope.data = {
        labels: ["Red", "Green", "Yellow"],
        datasets: [{
            label: "My First Dataset",
            data: [300, 50, 100],
            backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"]
        }]
    }

    $scope.total = 450;
    // Chart.js Options
    $scope.options = {
        // Sets the chart to be responsive
        responsive: false,
        //Boolean - Whether we should show a stroke on each segment
        segmentShowStroke: true,
        //String - The colour of each segment stroke
        segmentStrokeColor: '#fff',
        //Number - The width of each segment stroke
        segmentStrokeWidth: 2,
        //Number - The percentage of the chart that we cut out of the middle
        percentageInnerCutout: 50, // This is 0 for Pie charts
        //Number - Amount of animation steps
        animationSteps: 100,
        //String - Animation easing effect
        animationEasing: 'easeOutBounce',
        //Boolean - Whether we animate the rotation of the Doughnut
        animateRotate: true,
        //Boolean - Whether we animate scaling the Doughnut from the centre
        animateScale: false,
        //String - A legend template
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            // Return the HTML string here.
            var legendTemplate = '<ul class="tc-chart-js-legend">';
            for (var i = 0; i < chart.legend.legendItems.length; i++) {
                var legendItem = chart.legend.legendItems[i];
                legendTemplate = legendTemplate + '<li><span style="background-color:' + legendItem.fillStyle + '"></span>' + legendItem.text + '</li>';
            }
            legendTemplate = legendTemplate + '</ul>';
            return legendTemplate;
        }
    };

}]);
app.controller('LastCtrl', ["$scope", function ($scope) {

    // Chart.js Data
    $scope.data = {
        labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
        datasets: [
            {
                label: 'My First dataset',
                backgroundColor: 'rgba(220,220,220,0.2)',
                borderColor: 'rgba(220,220,220,1)',
                pointColor: 'rgba(220,220,220,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: [65, 59, 90, 81, 56, 55, 40]
            },
            {
                label: 'My Second dataset',
                backgroundColor: 'rgba(151,187,205,0.2)',
                borderColor: 'rgba(151,187,205,1)',
                pointColor: 'rgba(151,187,205,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(151,187,205,1)',
                data: [28, 48, 40, 19, 96, 27, 100]
            }
        ]
    };

    // Chart.js Options
    $scope.options = {
        // Sets the chart to be responsive
        responsive: true,
        //Boolean - Whether to show lines for each scale point
        scaleShowLine: true,
        //Boolean - Whether we show the angle lines out of the radar
        angleShowLineOut: true,
        //Boolean - Whether to show labels on the scale
        scaleShowLabels: false,
        // Boolean - Whether the scale should begin at zero
        scaleBeginAtZero: true,
        //String - Colour of the angle line
        angleLineColor: 'rgba(0,0,0,.1)',
        //Number - Pixel width of the angle line
        angleLineWidth: 1,
        //String - Point label font declaration
        pointLabelFontFamily: '"Arial"',
        //String - Point label font weight
        pointLabelFontStyle: 'normal',
        //Number - Point label font size in pixels
        pointLabelFontSize: 10,
        //String - Point label font colour
        pointLabelFontColor: '#666',
        //Boolean - Whether to show a dot for each point
        pointDot: true,
        //Number - Radius of each point dot in pixels
        pointDotRadius: 3,
        //Number - Pixel width of point dot stroke
        pointDotStrokeWidth: 1,
        //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
        pointHitDetectionRadius: 20,
        //Boolean - Whether to show a stroke for datasets
        datasetStroke: true,
        //Number - Pixel width of dataset stroke
        datasetStrokeWidth: 2,
        //Boolean - Whether to fill the dataset with a colour
        datasetFill: true,
        //String - A legend template
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            // Return the HTML string here.
            var legendTemplate = '<ul class="tc-chart-js-legend">';
            for (var i = 0; i < chart.legend.legendItems.length; i++) {
                var legendItem = chart.legend.legendItems[i];
                legendTemplate = legendTemplate + '<li><span style="background-color:' + legendItem.strokeStyle + '"></span>' + legendItem.text + '</li>';
            }
            legendTemplate = legendTemplate + '</ul>';
            return legendTemplate;
        },
        scale: {

            ticks: {
                beginAtZero: true,
                userCallback: function (value, index, values) {
                    return '';
                }
            }
        }
    };

}]);

/*function initChart() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {		
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานภาษี - หน่วยเป็นบาท",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(255,127,0,0.2)",
                    borderColor: "rgba(255,127,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(255,127,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(255,127,0,0.2)",
                    pointHoverBorderColor: "rgba(255,127,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [155822.55, 194815.01, 219438.62, 216655.84, 488730.42, 245438.62, 175192.41, 181227.39, 210624.99, 41618.84],
					showLine: true,
					spanGaps: false
				},{
					
                    label: "รายงานคดี-หน่วยเป็น คดี",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(238,0,0,0.4)",
                    borderColor: "rgba(238,0,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(238,0,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(238,0,0,0.4)",
                    pointHoverBorderColor: "rgba(238,0,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [111, 94, 209, 182, 124, 121, 85, 83, 84, 65],
					showLine: true,
					spanGaps: true
					
					},{
					
                    label: "รายงานจำนวนโรงงาน - หน่วยเป็น แห่ง",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(125,38,205,0.4)",
                    borderColor: "rgba(125,38,205,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(125,38,205,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(125,38,205,0.4)",
                    pointHoverBorderColor: "rgba(125,38,205,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [42, 95, 75, 48, 1867, 189, 65, 88, 171, 6],
					showLine: true,
					spanGaps: true
					
					},{
					
                    label: "รายงานภาษีแสตมป์- หน่วยเป็นบาท",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data:[55822.55, 94815.01, 119438.62, 126655.84, 288730.42, 145438.62, 75192.41, 81227.39, 110624.99, 21618.84],
					showLine: true,
					spanGaps: true
					
					},{
					
                    label: "รายงานใบอนุญาต- หน่วยเป็นราย",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(0,238,0,0.4)",
                    borderColor: "rgba(0,238,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(0,238,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(0,238,0,0.4)",
                    pointHoverBorderColor: "rgba(0,238,0,10)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [3819, 1956, 3373, 2331, 4543, 4174, 3193, 1411, 1051, 3819],
					showLine: true,
					spanGaps: true
					
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function chartTax() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานภาษี - หน่วยเป็นบาท",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(255,127,0,0.2)",
                    borderColor: "rgba(255,127,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(255,127,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(255,127,0,0.2)",
                    pointHoverBorderColor: "rgba(255,127,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [155822.55, 194815.01, 219438.62, 216655.84, 488730.42, 245438.62, 175192.41, 181227.39, 210624.99, 41618.84],
					showLine: true,
					spanGaps: false
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function chartCasel() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {
		
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานคดี-หน่วยเป็น คดี",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(238,0,0,0.4)",
                    borderColor: "rgba(238,0,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(238,0,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(238,0,0,0.4)",
                    pointHoverBorderColor: "rgba(238,0,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [111, 94, 209, 182, 124, 121, 85, 83, 84, 65],
					showLine: true,
					spanGaps: true
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function chartFactory() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {	
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานจำนวนโรงงาน - หน่วยเป็น แห่ง",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(125,38,205,0.4)",
                    borderColor: "rgba(125,38,205,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(125,38,205,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(125,38,205,0.4)",
                    pointHoverBorderColor: "rgba(125,38,205,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [42, 95, 75, 48, 1867, 189, 65, 88, 171, 6],
					showLine: true,
					spanGaps: true
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function chartStamp() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานภาษีแสตมป์- หน่วยเป็นบาท",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data:[55822.55, 94815.01, 119438.62, 126655.84, 288730.42, 145438.62, 75192.41, 81227.39, 110624.99, 21618.84],
					showLine: true,
					spanGaps: true
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function chartLicense() {
    var ctx = document.getElementById("myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;
    
    var myChart = new Chart(ctx, {	
        type: ("line"),
        data: {
            labels: ["ภาค 1", "ภาค 2", "ภาค 3", "ภาค 4", "ภาค 5", "ภาค 6", "ภาค 7", "ภาค 8", "ภาค 9", "ภาค 10"], //--ชื่อ labels ของข้อมูลที่แสดง
            datasets: [
                {
                    label: "รายงานใบอนุญาต- ก่อตั้ง",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(255,127,0,0.2)",
                    borderColor: "rgba(255,127,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(255,127,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(255,127,0,0.2)",
                    pointHoverBorderColor: "rgba(255,127,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [3819, 1956, 3373, 2331, 4543, 4174, 3193, 1411, 1051, 3819],
					showLine: true,
					spanGaps: false
				},{
					
                    label: "รายงานใบอนุญาต- ผลิต",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(238,0,0,0.4)",
                    borderColor: "rgba(238,0,0,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(238,0,0,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(238,0,0,0.4)",
                    pointHoverBorderColor: "rgba(238,0,0,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [4519, 956, 2373, 1331, 6543, 3174, 1193, 2411, 2051, 4819],
					showLine: true,
					spanGaps: true
					
					},{
					
                    label: "รายงานใบอนุญาต- ขน",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(125,38,205,0.4)",
                    borderColor: "rgba(125,38,205,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(125,38,205,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(125,38,205,0.4)",
                    pointHoverBorderColor: "rgba(125,38,205,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [14519, 1956, 12373, 11331, 16543, 13174, 11193, 12411, 12051, 14819],
					showLine: true,
					spanGaps: true
					
					},{
					
                    label: "รายงานใบอนุญาต- จำหน่าย",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data:[2519, 756, 1373, 831, 4543, 1174, 2193, 2411, 651, 1819],
					showLine: true,
					spanGaps: true
				}
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}*/

//--Chart report
function chartReport(chartReportData) {
    $('#myChart').remove();
    $('.my-chart').append('<canvas id="myChart"><canvas>');
    var ctx = $("#myChart");
    Chart.defaults.global.legend.labels.usePointStyle = true;
    Chart.defaults.global.legend.position = 'right';
    Chart.defaults.global.legend.labels.fontSize = 14;
    Chart.defaults.global.animation.duration = 0;

    var chartData = {
        type: 'line',
        data: {
            labels: chartReportData.monthData,
            datasets: []
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: chartReportData.yAxes
                    },
                    ticks: {
                        callback: function(label, index, labels) {
                            return Number(label).toLocaleString();
                        }
                        //beginAtZero: true
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: chartReportData.xAxes
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return data.datasets[tooltipItem.datasetIndex].label +': '+ Number(tooltipItem.yLabel).toLocaleString() +' '+ chartReportData.yAxes;
                    }
                }
            }
        }
    };

    var colorPatternData = {
        backgroundColor: ['#f1a59f', '#ab7d6b', '#3792ec', '#22ff91', '#ffdc9a', '#ff99cc', '#ff916f', '#d36ff2'],
        borderColor: ['#d62d20', '#4f372d', '#0c457d', '#008744', '#ffa700', '#fc3468', '#ff4408', '#673888']
    };
    
    $.each(chartReportData.chartData, function(index, item) {
        chartData.data.datasets.push({
            label: item.label,
            fill: false,
            lineTension: 0.1,
            backgroundColor: colorPatternData.backgroundColor[index],
            borderColor: colorPatternData.borderColor[index],
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: colorPatternData.borderColor[index],
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: colorPatternData.backgroundColor[index],
            pointHoverBorderColor: colorPatternData.borderColor[index],
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: item.data,
            spanGaps: false
        });
    });

    var myChart = new Chart(ctx, chartData);
}
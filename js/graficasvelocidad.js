         var s = null
        function runTest() {
            var chart1ctx = document.getElementById('chart1Area').getContext('2d')
            var chart2ctx = document.getElementById('chart2Area').getContext('2d')
            var dlDataset = {
                label: 'BAJADA',
                fill: false,
                lineTension: 0.1,
                backgroundColor: 'rgba(75,192,192,0.4)',
                borderColor: 'rgba(75,192,192,1)',
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: 'rgba(75,192,192,1)',
                pointBackgroundColor: '#fff',
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(75,192,192,1)',
                pointHoverBorderColor: 'rgba(220,220,220,1)',
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [0],
                spanGaps: false
            }
            var ulDataset = {
                label: 'SUBIDA',
                fill: false,
                lineTension: 0.1,
                backgroundColor: 'rgba(192,192,75,0.4)',
                borderColor: 'rgba(192,192,75,1)',
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: 'rgba(192,192,75,1)',
                pointBackgroundColor: '#fff',
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(192,192,75,1)',
                pointHoverBorderColor: 'rgba(220,220,220,1)',
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [0],
                spanGaps: false
            }
            var pingDataset = {
                label: 'PING',
                fill: false,
                lineTension: 0.1,
                backgroundColor: 'rgba(75,220,75,0.4)',
                borderColor: 'rgba(75,220,75,1)',
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: 'rgba(75,220,75,1)',
                pointBackgroundColor: '#fff',
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(75,220,75,1)',
                pointHoverBorderColor: 'rgba(220,220,220,1)',
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [],
                spanGaps: false
            }
            var jitterDataset = {
                label: 'JITTER',
                fill: false,
                lineTension: 0.1,
                backgroundColor: 'rgba(220,75,75,0.4)',
                borderColor: 'rgba(220,75,75,1)',
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: 'rgba(220,75,75,1)',
                pointBackgroundColor: '#fff',
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(220,75,75,1)',
                pointHoverBorderColor: 'rgba(220,220,220,1)',
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: [],
                spanGaps: false
            }

            var chart1Options = {
                type: 'line',
                data: {
                    datasets: [dlDataset, ulDataset]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: 'VELOCIDAD',
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            }
            var chart2Options = {
                type: 'line',
                data: {
                    datasets: [pingDataset, jitterDataset]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: 'LATENCIA',
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            }

            var chart1 = new Chart(chart1ctx, chart1Options)
            var chart2 = new Chart(chart2ctx, chart2Options)

            document.getElementById('startBtn').style.display = 'none'
            document.getElementById('testArea').style.display = ''
            document.getElementById('abortBtn').style.display = ''
            s=new Speedtest();
            s.onupdate = function (data) {
                var status = data.testState
                if (status === 1 && Number(data.dlStatus) > 0) {
                    for(var i=~~(20*Number(data.dlProgress));i<20;i++) chart1.data.datasets[0].data[i]=(Number(data.dlStatus))
                    chart1.data.labels[chart1.data.datasets[0].data.length - 1] = ''
                    chart1.update()
                }
                if (status === 3 && Number(data.ulStatus) > 0) {
                    for(var i=~~(20*Number(data.ulProgress));i<20;i++) chart1.data.datasets[1].data[i]=(Number(data.ulStatus))
                    chart1.data.labels[chart1.data.datasets[1].data.length - 1] = ''
                    chart1.update()
                }
                if (status === 2 && Number(data.pingStatus) > 0) {
                    chart2.data.datasets[0].data.push(Number(data.pingStatus))
                    chart2.data.datasets[1].data.push(Number(data.jitterStatus))
                    chart2.data.labels[chart2.data.datasets[0].data.length - 1] = ''
                    chart2.data.labels[chart2.data.datasets[1].data.length - 1] = ''
                    chart2.update()
                }
                ip.textContent = data.clientIp
            }
            s.onend=function(aborted){
                document.getElementById('').style.display = 'none'
                document.getElementById('startBtn').style.display = ''
                s = null
                if (aborted) {
                    document.getElementById('testArea').style.display = 'none'
                }
            }
            s.start();
        }
        function abortTest() {
            if (s) s.abort();
        }
   
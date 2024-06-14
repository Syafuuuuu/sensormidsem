<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Climate Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-4">Climate Data</h1>
        <div class="row">
            <div class="col-lg-4">
                <canvas id="temperatureChart"></canvas>
            </div>
            <div class="col-lg-4">
                <canvas id="humidityChart"></canvas>
            </div>
            <div class="col-lg-4">
                <canvas id="soilMoistureChart"></canvas>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var labels = Array.from({ length: 60 }, (_, i) => i + 1); // labels for 60 seconds

        var ctx1 = document.getElementById('temperatureChart').getContext('2d');
        var temperatureChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperature',
                    data: [],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx2 = document.getElementById('humidityChart').getContext('2d');
        var humidityChart = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Humidity',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        var ctx3 = document.getElementById('soilMoistureChart').getContext('2d');
        var soilMoistureChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Soil Moisture',
                    data: [],
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });

        setInterval(function () {
            // Fetch new data from the server
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    // Update the chart data
                    temperatureChart.data.datasets[0].data = data.temperatureData;
                    humidityChart.data.datasets[0].data = data.humidityData;
                    soilMoistureChart.data.datasets[0].data = data.soilMoistureData;

                    // Update the charts
                    temperatureChart.update();
                    humidityChart.update();
                    soilMoistureChart.update();
                });
        }, 5000); // Fetch new data every 5 seconds
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Climate Data</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Include Chart.js library for creating charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-4">Climate Data</h1>
        <div class="row">
            <!-- Temperature chart canvas -->
            <div class="col-lg-4">
                <canvas id="temperatureChart"></canvas>
                <p id="temperatureStats"></p>
            </div>
            <!-- Humidity chart canvas -->
            <div class="col-lg-4">
                <canvas id="humidityChart"></canvas>
                <p id="humidityStats"></p>
            </div>
            <!-- Soil moisture chart canvas -->
            <div class="col-lg-4">
                <canvas id="soilMoistureChart"></canvas>
                <p id="soilMoistureStats"></p>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // Create an array of labels for the x-axis (60 seconds)
        var labels = Array.from({ length: 60 }, (_, i) => i + 1);

        // Get the context of the temperature chart canvas
        var ctx1 = document.getElementById('temperatureChart').getContext('2d');
        // Create a new line chart for temperature data
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

        // Get the context of the humidity chart canvas
        var ctx2 = document.getElementById('humidityChart').getContext('2d');
        // Create a new line chart for humidity data
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

        // Get the context of the soil moisture chart canvas
        var ctx3 = document.getElementById('soilMoistureChart').getContext('2d');
        // Create a new line chart for soil moisture data
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

        // Set an interval to fetch new data from the server every 5 seconds
        setInterval(function () {
            fetch('fetch_data.php') // Fetch data from the server
                .then(response => response.json()) // Parse the response as JSON
                .then(data => {
                    // Update the chart data with the new data from the server
                    temperatureChart.data.datasets[0].data = data.temperatureData;
                    humidityChart.data.datasets[0].data = data.humidityData;
                    soilMoistureChart.data.datasets[0].data = data.soilMoistureData;

                    // Update the charts to reflect the new data
                    temperatureChart.update();
                    humidityChart.update();
                    soilMoistureChart.update();
                });
        }, 5000); // Fetch new data every 5 seconds
    </script>
</body>

</html>

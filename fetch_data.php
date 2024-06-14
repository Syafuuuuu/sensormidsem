<?php
$conn = mysqli_connect("localhost", "root", "", "midsem");

// Fetch the latest data from the database
$s1result = mysqli_query($conn, "SELECT sensor1 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
$s2result = mysqli_query($conn, "SELECT sensor2 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");
$s3result = mysqli_query($conn, "SELECT sensor3 FROM `sensor` ORDER BY `sensor`.`id` DESC LIMIT 60");

$temperatureData = [];
while($row = mysqli_fetch_array($s1result)){
    $temperatureData[] = $row['sensor1'];
}

$humidityData = [];
while($row = mysqli_fetch_array($s2result)){
    $humidityData[] = $row['sensor2'];
}

$soilMoistureData = [];
while($row = mysqli_fetch_array($s3result)){
    $soilMoistureData[] = $row['sensor3'];
}

// Return the data as a JSON response
header('Content-Type: application/json');
echo json_encode([
    'temperatureData' => $temperatureData,
    'humidityData' => $humidityData,
    'soilMoistureData' => $soilMoistureData
]);
?>

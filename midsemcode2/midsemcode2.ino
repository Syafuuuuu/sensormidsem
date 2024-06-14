/*************************************************************************************************
 *  Originaly Created By: Tauseef Ahmad
 *  Originialy Created On: 3 April, 2023
 *
 *  Adapted By: Muhammad Syafiq
 *  Adapted On: 14 June 2024
 *  
 *  Code was adapted from:
 *
 *  Ahmed Logs - For database connection
 *  - YouTube Video: https://youtu.be/VEN5kgjEuh8
 *  - Youtube Channel: https://www.youtube.com/channel/UCOXYfOHgu-C-UfGyDcu5sYw/
 *
 *  INOVATRIX - For Soil Moisture Sensor
 *  - Github Repo: https://github.com/INOVATRIX/ESP8266-SOIL-MOISTURE-SM/tree/main
 * 
 *  Co-Pilot 
 *  - To Generate the graph code and reactive website
 *  
 ***********************************************************************************************/

// Include the DHT sensor library
#include "DHT.h"

// Define the digital pin connected to the DHT sensor and the sensor type
#define DHTPIN 4       // D2 pin on the ESP8266
#define DHTTYPE DHT22  // DHT22 (AM2302) type
DHT dht(DHTPIN, DHTTYPE);

// Include the ESP8266 WiFi and HTTP client libraries
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

// Define the analog pin connected to the soil moisture sensor
#define soil_moisture_pin A0

// URL for sending sensor data to the server
String URL = "http://192.168.50.91/midterm/test_data.php";

// WiFi credentials
const char* ssid = "azharistudiov3"; 
const char* password = "4zh4r157ud10v3"; 

// Variables to store sensor readings
int s1, s2, s3;

// Setup function runs once at startup
void setup() {
  Serial.begin(115200); // Start serial communication at 115200 baud rate
  connectWiFi();        // Connect to WiFi network
  dht.begin();          // Initialize the DHT sensor
}

// Loop function runs repeatedly
void loop() {
  // Reconnect to WiFi if connection is lost
  if(WiFi.status() != WL_CONNECTED) { 
    connectWiFi();
  }

  // Read temperature and humidity from DHT sensor
  s1 = dht.readTemperature();
  s2 = dht.readHumidity();
  // Read soil moisture level from analog pin
  s3 = analogRead(soil_moisture_pin);

  // Prepare data for HTTP POST request
  String postData = "sensor1=" + String(s1) + "&sensor2=" + String(s2) + "&sensor3=" + String(s3); 

  // Create HTTP and WiFi client objects
  HTTPClient http;
  WiFiClient wclient;
   
  // Begin HTTP connection to the server
  http.begin(wclient, URL);
  // Set the content type for the POST request
  http.addHeader("Content-Type", "application/x-www-form-urlencoded"); 
  
  // Send the POST request and get the response code
  int httpCode = http.POST(postData); 
  // Get the response payload from the server
  String payload = http.getString(); 

  // Check if the HTTP request was successful
  if(httpCode > 0) {
    if(httpCode == HTTP_CODE_OK) {
      // Print the server's response payload
      Serial.println(payload);
    } else {
      // Print the HTTP response code
      Serial.printf("[HTTP] GET... code: %d\n", httpCode);
    }
  } else {
    // Print the HTTP error
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }
  
  // Close the HTTP connection
  http.end();  
  
  // Print debugging information
  Serial.print("URL : "); Serial.println(URL); 
  Serial.print("Data: "); Serial.println(postData); 
  Serial.print("httpCode: "); Serial.println(httpCode); 
  Serial.print("payload : "); Serial.println(payload); 
  Serial.println("--------------------------------------------------");
  // Wait for 5 seconds before repeating the loop
  delay(5000);
}

// Function to connect to the WiFi network
void connectWiFi() {
  // Turn off WiFi to reset any previous configurations
  WiFi.mode(WIFI_OFF);
  delay(1000);
  // Set WiFi to station mode and connect to the network
  WiFi.mode(WIFI_STA);
  
  // Begin connection process
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  
  // Wait until connection is established
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
    
  // Print connection details
  Serial.print("connected to : "); Serial.println(ssid);
  Serial.print("IP address: "); Serial.println(WiFi.localIP());
}

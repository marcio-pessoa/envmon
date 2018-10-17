/* config.h, envmon Mark I - Environment Monitor, Arduino project config file
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 * 
 * Description: Set default values to basic resources
 */

// LED
const byte R_pin = 10;
const byte G_pin = 11;
const byte B_pin = 5;
const byte outdoor_led_pin = 19;

// Speaker
const byte speaker_pin = 6;
const int speaker_timer = 250;  // milliseconds

// System temperature
const byte temperature_pin = 5;
const int temperature_timer = 1;  // seconds

// Fan
const byte fan_control_pin = 13;
// const byte fan_sensor_pin = 0;
const int fan_timer = 2;  // seconds

// DHT (Digital Humidity and Temperature sensor)
const byte dht_pin = 3;

// Soil Moisture
const byte moisture_pin = 0;

// Pump relay
const byte relay1_pin = 17;

// Ultrasonic distance sensor
const byte ultrasonic_trigger_pin = 20;
const byte ultrasonic_echo_pin = 7;

// Debug mode
bool debug = false;

/* envmon.ino, envmon Mark II - Environment Monitor, Arduino main sketch file
 * 
 * This sketch was developed and tested on: Arduino Leonardo and Yún Shield
 * Probably it works fine on original Arduin Yún board, I don't know.
 * To work on other Arduino models, some adaptations may be necessary.
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

#include <Arduino.h>         // Arduino - Main library
#include <Console.h>         // Arduino - Console communication
#include <Project.h>         // Sciemon - Basic project definitions
#include <Timer.h>           // Sciemon - Timer library with nice features
#include <RGB.h>             // Sciemon - RGB LED controller
#include <SigGen.h>          // Sciemon - Signal Generator
#include <Alarm.h>           // Sciemon - Manage alarms
#include <Switch.h>          // Sciemon - Switch manipulation
#include <Fan.h>             // Sciemon - Fan speed control
#include <Temperature.h>     // Sciemon - Temperature Sensors
#include <Ultrasonic.h>      // Sciemon - Ultrasonic distance sensor
#include <SoilMoisture.h>    // Sciemon - Soil moisture sensor
#include "config.h"          // Sciemon - Configuration
#include <DHT.h>             // Adafruit - DHT humidity/temperature sensors
#include <MemoryFree.h>      // 

// Project definitions
Project envmon("envmon",  // Platform
               "II",  // Mark
               "Environment Monitor",  // Name
               "0.06b",  // Version
               "2016-05-10",  // Version date
               "5",  // Serial number
               "Copyright (c) 2013-2015 Marcio Pessoa",  // Owner
               "undefined. There is NO WARRANTY.",  // License
               "http://pessoa.eti.br/",  // Website
               "Marcio Pessoa <marcio.pessoa@sciemon.com>");  // Contact

// RGB LED
RGB led(R_pin, G_pin, B_pin);
SigGen wave;

// Speaker
Timer speaker_period(speaker_timer);
byte speaker_beep_counter = 0;

// System temperature sensor
Temperature temperature;
Timer temperature_period(temperature_timer * 1000);
Alarm system_temperature(60,  // Maximum warning
                         70,  // Maximum critical
                         10,  // Minimum warning
                         5);  // Minimum critical

// Fan
Fan fan_speed(fan_control_pin);
Timer fan_period(fan_timer * 1000);

// DHT sensor
DHT dht(dht_pin, DHT22);

// Soil Moisture sensor
SoilMoisture soil_moisture(moisture_pin);

// Ultrasonic sensor
Ultrasonic water_level;

// Relay 1
Switch relay1(relay1_pin);

void setup() {
  // Start Bridge interface
  Bridge.begin();
  // Start Console interface
  Console.begin();
  // Start up message
  echoln("Starting...");
  CommandM92();
  // RGB LED
  led.inverse();
  // Outdoor LED
  pinMode(19, OUTPUT);
  digitalWrite(19, HIGH);
  // Start DHT sensor
  dht.begin();
  // System Temperature
  temperature.attach(temperature_pin);
  temperature.type(TMP36);
  system_temperature.nameWrite("Temperature");
  system_temperature.unitWrite(" *C");
  // Water
  water_level.attach(ultrasonic_trigger_pin, ultrasonic_echo_pin);
  water_level.type(US100);
  // Water pump relay
  relay1.nameWrite("Relay1");
  // Start up sound
  tone(speaker_pin, 2217, 60);
  // G-code ready to receive commands
  GcodeReady();
}

void loop() {
  NotificationHandler();
  HealthCheckHandler();
  SensorsHandler();
  ActuatorHandler();
  GcodeCheck();
}

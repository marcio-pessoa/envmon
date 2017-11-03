README, envmon Mark I - Environment Monitor, Readme file

1. Introduction

1.1. envmon architecture


2. Mechanics


3. Electronics

3.1. envmon Mark I - Arduino Leonardo pin designation table
╔════╦═════╦═══════════╦═══════════╦══════════════════════════════════════════╗
║ id ║ pin ║   type    ║ direction ║               description                ║
╠════╬═════╬═══════════╬═══════════╬══════════════════════════════════════════╣
║  0 ║   0 ║ digital   ║ input     ║ Serial communication                     ║
║  1 ║   1 ║ digital   ║ output    ║ Serial communication                     ║
║  2 ║   0 ║ interrupt ║ input     ║ Fan speed sensor (reserved)              ║
║  3 ║   3 ║ PWM       ║ input     ║ RJ-45 pin 2 (0/1, PWM)                   ║
║  4 ║   4 ║ -         ║ -         ║ -                                        ║
║  5 ║   5 ║ PWM       ║ output    ║ Fan speed control                        ║
║  6 ║   6 ║ PWM       ║ output    ║ Speaker                                  ║
║  7 ║   7 ║ digital   ║ output    ║ RJ-45 pin 3 (0/1, Int4)                  ║
║  8 ║   8 ║ -         ║ -         ║ -                                        ║
║  9 ║   9 ║ PWM       ║ output    ║ RJ-45 pin 4 (0/1, PWM)                   ║
║ 10 ║  10 ║ PWM       ║ output    ║ LED R (red)                              ║
║ 11 ║  11 ║ PWM       ║ output    ║ LED G (green)                            ║
║ 12 ║  12 ║ -         ║ -         ║ -                                        ║
║ 13 ║  13 ║ PWM       ║ output    ║ LED B (blue)                             ║
║ 14 ║     ║           ║           ║                                          ║
║ 15 ║     ║           ║           ║                                          ║
║ 16 ║     ║           ║           ║                                          ║
║ 17 ║     ║           ║           ║                                          ║
║ 18 ║   0 ║ analog    ║ input     ║ RJ-45 pin 5 (0/1, Analog)                ║
║ 19 ║   1 ║ analog    ║ input     ║ RJ-45 pin 6 (0/1, Analog)                ║
║ 20 ║   2 ║ analog    ║ input     ║ RJ-45 pin 7 (0/1, Analog)                ║
║ 21 ║   3 ║ -         ║ -         ║ -                                        ║
║ 22 ║   4 ║ -         ║ -         ║ -                                        ║
║ 23 ║   5 ║ analog    ║ input     ║ System temperature sensor                ║
╚════╩═════╩═══════════╩═══════════╩══════════════════════════════════════════╝

                                      +-----+
         +----[PWR]-------------------| USB |--+
         |                            +-----+  |
         |                                     |
         |                           A5/SCL[ ] |   C5 
         |                           A4/SDA[ ] |   C4 
         |                             AREF[ ] |
         |                              GND[ ] |
         | [ ]N/C                    SCK/13[x]~|   B5
         | [ ]v.ref                 MISO/12[ ] |   .
         | [ ]RST                   MOSI/11[x]~|   .
         | [ ]3V3   +-----+              10[x]~|   .
         | [ ]5v    |     |               9[x]~|   .
         | [ ]GND   | MCU |               8[ ] |   B0
         | [ ]GND   |     |                    |
         | [x]Vin   +-----+               7[x] |   D7
         |                                6[x]~|   .
         | [x]A0                          5[x]~|   .
         | [x]A1                          4[ ] |   .
         | [x]A2                     INT1/3[x]~|   .
         | [ ]A3                     INT0/2[x] |   .
         | [ ]A4/SDA  RST SCK MISO     TX>1[x] |   .
         | [x]A5/SCL  [ ] [ ] [ ]      RX<0[x] |   D0
         |            [ ] [ ] [ ]              |
         |  Leonardo  GND MOSI 5V  ____________/
          \_______________________/
                          Adapted from: http://busyducks.com/ascii-art-arduinos

3.2. envmon Mark I - RJ-45 female pins
╔═════╦═══════════════════════════════════════════════════════════════════════╗
║ pin ║                              description                              ║
╠═════╬═══════════════════════════════════════════════════════════════════════╣
║   1 ║ +5 Vcc                                                                ║
║   2 ║ rj45_2 (D3, PWM3, Int1)                                               ║
║   3 ║ rj45_3 (D7, Int4)                                                     ║
║   4 ║ rj45_4 (D9, A9, PWM9)                                                 ║
║   5 ║ rj45_5 (D18, A0)                                                      ║
║   6 ║ rj45_6 (D19, A1)                                                      ║
║   7 ║ rj45_7 (D20, A2)                                                      ║ 
║   8 ║ GND                                                                   ║
╚═════╩═══════════════════════════════════════════════════════════════════════╝

3.3. envmon Outdoor Module Mark I

3.3.1. RJ-45 male pins
╔═════╦═══════════════════════════════════════════════════════════════════════╗
║ pin ║                              description                              ║
╠═════╬═══════════════════════════════════════════════════════════════════════╣
║   1 ║ +5 Vcc                                                                ║
║   2 ║ DHT22 (Temperature and Relative humidity sensor)                      ║
║   3 ║ US100 echo_pin (RX)                                                   ║
║   4 ║ Water pump relay 1                                                    ║
║   5 ║ Soil moisture sensor 1                                                ║
║   6 ║ Outdoor LED                                                           ║
║   7 ║ US100 trigger_pin (TX)                                                ║
║   8 ║ GND                                                                   ║ 
╚═════╩═══════════════════════════════════════════════════════════════════════╝

3.3.2. Green connector

 *       ╭───┬─┬───╮
 *     L1│ o │ │ o │R1
 *     L2│ o │ │ o │R2
 *     L3│ o │ │ o │R3
 *       ╰───┴─┴───╯
 *            
L1 +5Vcc
L2 GND
L3 Soil moisture sensor

R1 Relay NO (Normally Open)
R2 Relay Common
R3 Relay NC (Normally Closed)

╔═════╦═══════════════════════════════════════════════════════════════════════╗
║ pin ║                              description                              ║
╠═════╬═══════════════════════════════════════════════════════════════════════╣
║   1 ║ +5 Vcc                                                                ║
║   2 ║ DHT22 (Temperature and Relative humidity sensor)                      ║
║   3 ║ GND                                                                   ║ 
╚═════╩═══════════════════════════════════════════════════════════════════════╝



3.4. envmon Outdoor Module Mark I - RJ-45 male pins
╔═════╦═══════════════════════════════════════════════════════════════════════╗
║ pin ║                              description                              ║
╠═════╬═══════════════════════════════════════════════════════════════════════╣
║   1 ║ +5 Vcc                                                                ║
║   2 ║ DHT22 (Temperature and Relative humidity sensor)                      ║
║   3 ║ Water pump relay 1                                                    ║
║   4 ║ Water pump relay 2                                                    ║
║   5 ║ Soil moisture sensor 1                                                ║
║   6 ║ Soil moisture sensor 2                                                ║
║   7 ║ Water level sensor                                                    ║
║   8 ║ GND                                                                   ║
╚═════╩═══════════════════════════════════════════════════════════════════════╝

4. Software

4.1. Arduino Yún

Access credentials

user: root
pass: envmon

Configure host name, wireless with Internet access, password, timezone and a ssh key.

4.1.1. Software dependencies

./install.sh 192.168.1.11

4.1.1.1. PHP

4.1.1.2. Time zone
  If necessary you can check the available time zones in:

/usr/share/zoneinfo

  Configure timezone in config.php and Dragino web interface.

4.1.2. Log files


system.log
  CPU
  Memory
  Storage

controller.log
  Memory
  Temperature
  Fan

environment.log
  Temperature
  Humidity
  Moisture
  Water

4.1.3. Automatic reliave log files

Add this line to /etc/fstab via web interface
/dev/sda	/mnt	vfat	rw	0	0

Add crontab jobs

-------------------------------------------------------------------------------










  // system_temperature.nameWrite("Temperature");
  // system_temperature.unitWrite(" *C");
  // Fan
  //~ fan.nameWrite("Fan");
  //~ fan.unitWrite("%");
  //~ fan.check(100);
  // Temperature
  // temperature.nameWrite("Temperature");
  // temperature.unitWrite(" *C");
  // temperature.force_check(OK);
  // Humidity
  // humidity.nameWrite("Humidity");
  // humidity.unitWrite("%");
  // humidity.force_check(OK);
  // Moisture
  // moisture.nameWrite("Moisture");
  // moisture.unitWrite("%");
  // Water






  // Memory
  // memory.nameWrite("Memory");
  // memory.unitWrite("%");



  // water.nameWrite("Water");
  // water.unitWrite("%");



















// Temperature
// Alarm temperature(35,  // Maximum warning
                  // 45,  // Maximum critical
                  // 5,   // Minimum warning
                  // 0);  // Minimum critical
// Humidity
// Alarm humidity(30,     // Maximum critical
               // 40,     // Maximum warning
               // false,  // Minimum critical (not used)
               // false,  // Minimum warning (not used)
               // true);  // Inverse parameters above



// Alarm moisture(50,     // Maximum critical
               // 30,     // Maximum warning
               // false,  // Minimum critical (not used)
               // false,  // Minimum warning (not used)
               // true);  // Inverse parameters above


// Alarm water(10,     // Maximum critical
            // 30,     // Maximum warning
            // false,  // Minimum critical (not used)
            // false,  // Minimum warning (not used)
            // true);  // Inverse parameters above


Timer squirt_minimal_period((unsigned long)squirt_minimal_period_timer * 60 * 1000, COUNTDOWN);
Timer squirt_maximum_period((unsigned long)squirt_maximum_period_timer * 60 * 1000);
Timer squirt_duration(squirt_duration_timer * 1000, COUNTDOWN);




// Configuration reload update
Timer configuration_reload_period((unsigned long)configuration_reload_timer * 1000);

// Log files update
Timer log_file_update((unsigned long)log_file_timer * 1000);



// Season
String season = "Unknown";



Timer moisture_period(moisture_timer * 1000);
// Alarm fan(101,   // Warning
          // 102);  // Critical





// Memory
// Alarm memory(75, 85);


// System check timer
Timer health_check_period(health_check_timer);
Timer system_status_period(system_status_timer);



Timer water_period(water_timer * 1000);

// Timers
const int dht_timer = 30;  // seconds
const int moisture_timer = 10;  // seconds
const int squirt_minimal_period_timer = 10;  // minutes
const int squirt_maximum_period_timer = 240;  // minutes
const int squirt_duration_timer = 6;  // seconds
const int water_timer = 20;  // seconds
const int health_check_timer = 250;  // milliseconds
const int system_status_timer = 500;  // milliseconds

// Config files
const char *season_cfg = "/opt/envmon/cfg/season.cfg";
const char *environment_cfg = "/opt/envmon/cfg/environment.cfg";
const int configuration_reload_timer = 90;  // seconds

// Script files
const char *get_option_sh = "/opt/envmon/bin/get_option.sh";

// Log files
const char *environment_log = "/opt/envmon/log/environment.log";
const char *controller_log = "/opt/envmon/log/controller.log";
const int log_file_timer = 60;  // seconds








How to reset the Yun Shield

The Yun Shield has a toggle button which can be used for reset. When the system of the Yun Shield is running, the user can press the toggle button to reset the device. Pressing this button will cause the WLAN LED will blink.
Pressing the toggle button and release after 5 seconds, will reset the WiFi setting and other settings will be kept.
Pressing the toggle button and release after 30 seconds, will reset ALL the setting to factory default .

# envmon Mark I - Environment Monitor

[Change log](CHANGELOG.md)

[Bill of materials](BOM.md)

[Kanban](KANBAN.md)

# Introduction

## System architecture
![System architeture](Documents/Pictures/architecture.png)

# Mechanics


# Electronics

## Arduino
Technical specifications:
- Model: [Arduino Leonardo]

### Pin designation table
 id | pin |   type    | direction |               description                
----|-----|-----------|-----------|--------------------------------------------
  0 |   0 | digital   | input     | Serial communication                     
  1 |   1 | digital   | output    | Serial communication                     
  2 |   0 | interrupt | input     | Fan speed sensor (reserved)              
  3 |   3 | PWM       | input     | RJ-45 pin 2 (0/1, PWM)                   
  4 |   4 | -         | -         | -                                        
  5 |   5 | PWM       | output    | Fan speed control                        
  6 |   6 | PWM       | output    | Speaker                                  
  7 |   7 | digital   | output    | RJ-45 pin 3 (0/1, Int4)                  
  8 |   8 | -         | -         | -                                        
  9 |   9 | PWM       | output    | RJ-45 pin 4 (0/1, PWM)                   
 10 |  10 | PWM       | output    | LED R (red)                              
 11 |  11 | PWM       | output    | LED G (green)                            
 12 |  12 | -         | -         | -                                        
 13 |  13 | PWM       | output    | LED B (blue)                             
 14 |     |           |           |                                          
 15 |     |           |           |                                          
 16 |     |           |           |                                          
 17 |     |           |           |                                          
 18 |   0 | analog    | input     | RJ-45 pin 5 (0/1, Analog)                
 19 |   1 | analog    | input     | RJ-45 pin 6 (0/1, Analog)                
 20 |   2 | analog    | input     | RJ-45 pin 7 (0/1, Analog)                
 21 |   3 | -         | -         | -                                        
 22 |   4 | -         | -         | -                                        
 23 |   5 | analog    | input     | System temperature sensor                

### Diagram
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

### envmon Mark I - RJ-45 female pins
 pin |                              description                              
-----|-------------------------------------------------------------------------
   1 | +5 Vcc                                                                
   2 | rj45_2 (D3, PWM3, Int1)                                               
   3 | rj45_3 (D7, Int4)                                                     
   4 | rj45_4 (D9, A9, PWM9)                                                 
   5 | rj45_5 (D18, A0)                                                      
   6 | rj45_6 (D19, A1)                                                      
   7 | rj45_7 (D20, A2)                                                      
   8 | GND                                                                   

## Outdoor Module Mark I

### RJ-45 male pins
 pin |                              description                              
-----|-------------------------------------------------------------------------
   1 | +5 Vcc                                                                
   2 | DHT22 (Temperature and Relative humidity sensor)                      
   3 | US100 echo_pin (RX)                                                   
   4 | Water pump relay 1                                                    
   5 | Soil moisture sensor 1                                                
   6 | Outdoor LED                                                           
   7 | US100 trigger_pin (TX)                                                
   8 | GND                                                                   

### Green connector

        ╭───┬─┬───╮
      L1│ o │ │ o │R1
      L2│ o │ │ o │R2
      L3│ o │ │ o │R3
        ╰───┴─┴───╯

L1 +5Vcc
L2 GND
L3 Soil moisture sensor

R1 Relay NO (Normally Open)
R2 Relay Common
R3 Relay NC (Normally Closed)

 pin |                              description                              
-----|-------------------------------------------------------------------------
   1 | +5 Vcc                                                                
   2 | DHT22 (Temperature and Relative humidity sensor)                      
   3 | GND                                                                   



### envmon Outdoor Module Mark I - RJ-45 male pins

 pin |                              description                              
-----|-------------------------------------------------------------------------
   1 | +5 Vcc                                                                
   2 | DHT22 (Temperature and Relative humidity sensor)                      
   3 | Water pump relay 1                                                    
   4 | Water pump relay 2                                                    
   5 | Soil moisture sensor 1                                                
   6 | Soil moisture sensor 2                                                
   7 | Water level sensor                                                    
   8 | GND                                                                   

# Software

## Arduino Yún Shield

Access credentials:
- user: root
- pass: envmon

You must configure:
- Host name
- Wireless Internet access
- Password
- Timezone
- SSH public key

After all, run installation program:
``` sh
./setup install 192.168.1.11
```

### PHP

### Time zone
If necessary you can check the available time zones in:

``` sh
/usr/share/zoneinfo
```

Configure timezone in `config.php` and Dragino web interface.

### Log files

system.log:
- CPU
- Memory
- Storage

controller.log:
- Memory
- Temperature
- Fan

environment.log:
- Temperature
- Humidity
- Moisture
- Water

### Automatic reliave log files

Add this line to /etc/fstab via web interface
```
/dev/sda	/mnt	vfat	rw	0	0
```

### How to reset the Yun Shield

The Yun Shield has a toggle button which can be used for reset. When the system of the Yun Shield is running, the user can press the toggle button to reset the device. Pressing this button will cause the WLAN LED will blink.
Pressing the toggle button and release after 5 seconds, will reset the WiFi setting and other settings will be kept.
Pressing the toggle button and release after 30 seconds, will reset ALL the setting to factory default .

---

[Arduino Leonardo]: https://www.arduino.cc/en/Main/arduinoBoardLeonardo/#techspecs

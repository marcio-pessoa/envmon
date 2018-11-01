# envmon Mark I - Environment Monitor

[Main page]

## Software

### Arduino Yún Shield

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

#### PHP

#### Time zone
If necessary you can check the available time zones in:

``` sh
/usr/share/zoneinfo
```

Configure timezone in `config.php` and Dragino web interface.

#### Log files

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

#### Automatic reliave log files

Add this line to /etc/fstab via web interface
```
/dev/sda	/mnt	vfat	rw	0	0
```

#### How to reset the Yun Shield

The Yun Shield has a toggle button which can be used for reset. When the system of the Yun Shield is running, the user can press the toggle button to reset the device. Pressing this button will cause the WLAN LED will blink.
Pressing the toggle button and release after 5 seconds, will reset the WiFi setting and other settings will be kept.
Pressing the toggle button and release after 30 seconds, will reset ALL the setting to factory default .

[Main page]

---

[Main page]: ../README.md

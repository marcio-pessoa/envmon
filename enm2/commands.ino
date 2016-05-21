/* commands.ino, envmon Mark II - Environment Monitor, Arduino commands sketch file
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

/* Command0
 * 
 * Description
 *   .
 * 
 *   Command0()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
void Command0(){
  echoln("Unknown command");
}

/* CommandM10
 * 
 * Description
 *   .
 * 
 *   CommandM10()
 * 
 * Parameters
 *   frequency: Sound frequency (31	~ 65535)
 *   duration: Sound duration (milliseconds)
 * 
 * Returns
 *   void
 */
bool CommandM10(unsigned int frequency, unsigned long duration) {
  
}

/* CommandM20
 * 
 * Description
 *   .
 * 
 *   CommandM20()
 * 
 * Parameters
 *   R: Red color intensity (0 ~ 255)
 *   G: Green color intensity (0 ~ 255)
 *   B: Blue color intensity (0 ~ 255)
 * 
 * Returns
 *   void
 */
bool CommandM20(byte R, byte G, byte B) {
  led.set(R, G, B);
}

/* CommandM30
 * 
 * Description
 *   Shows memory information.
 * 
 *   CommandM30()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM30() {
  int total = (float)2.5 * 1024;
  int free = freeMemory();
  int used = total - free;
  int percent_used = (float)used * 100 / total;
  int percent_free = 100 - (float)percent_used;
  echoln("SRAM:\t" + String(total) + " B");
  echoln("Used:\t" + String(used) + " B (" + percent_used + "%)");
  echoln("Free:\t" + String(free) + " B (" + percent_free + "%)");
}

/* CommandM40
 * 
 * Description
 *   Shows fan sensor information.
 * 
 *   CommandM40()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM40(byte sensor) {
  float detected = 0;
  switch (sensor) {
    case 1:
      detected = fan_speed.readSpeed();
      break;
    default:
      return true;
  }
  echoln("Fan: " + String(detected) + "%");
}

/* CommandM50
 * 
 * Description
 *   Shows water sensor information.
 * 
 *   CommandM50()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM50(byte sensor) {
  float detected = 0;
  switch (sensor) {
    case 1:
      detected = water_level.read();
      break;
    default:
      return true;
  }
  echoln("Distance: " + String(detected) + "cm");
}

/* CommandM60
 * 
 * Description
 *   Shows temperature.
 * 
 *   CommandM60()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM60(byte sensor) {
  float detected = 0;
  switch (sensor) {
    case 1:  // System temperature
      detected = temperature.read();
      break;
    case 2:  // Environment temperature
      detected = dht.readTemperature();
      break;
    default:
      return true;
  }
  echoln("Temperature " + String(sensor) + ": " + String(detected) + " *C");
}

/* CommandM70
 * 
 * Description
 *   Shows humidity.
 * 
 *   Reading temperature or humidity takes about 250 milliseconds!
 *   Sensor readings may also be up to 2 seconds 'old'.
 *   DHT is a very slow sensor.
 * 
 *   CommandM70()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM70(byte sensor) {
  float detected = 0;
  switch (sensor) {
    case 1:
      detected = dht.readHumidity();
      break;
    default:
      return true;
  }
  echoln("Humidity: " + String(detected) + "%");
}

/* CommandM80
 * 
 * Description
 *   Shows relay status.
 * 
 *   CommandM80()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM80(byte relay) {
  bool status = true;
  switch (relay) {
    case 1:
      status = relay1.status();
      break;
    default:
      break;
  }
  echoln("Relay" + String(relay) + ": " + String(status ? "on" : "off"));
}

/* CommandM81
 * 
 * Description
 *   Turns relay on.
 * 
 *   CommandM81()
 * 
 * Parameters
 *   relay: relay number
 * 
 * Returns
 *   void
 */
bool CommandM81(byte relay) {
  switch (relay) {
    case 1:
      relay1.on();
      return !relay1.status();
    default:
      return true;
  }
}

/* CommandM82
 * 
 * Description
 *   Turns relay off.
 * 
 *   CommandM82()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
bool CommandM82(byte relay) {
  switch (relay) {
    case 1:
      relay1.off();
      return relay1.status();
    default:
      return true;
  }
}

/* CommandM92
 * 
 * Description
 *   Shows system information.
 * 
 *   CommandM92()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
void CommandM92() {
  echoln(envmon.version());
  if (debug or (millis() < 100)) {
    echoln(envmon.owner());
    echoln(envmon.compiled());
    echoln(envmon.license());
    echoln(envmon.website());
    echoln(envmon.contact());
  }
}

void CommandM99() {
  echoln("Reseting...\n");
  CommandM10(31, 0);  // Silent speaker
  CommandM20(0, 0, 0);  // Turn off LED
  CommandM82(1);  // Power off relay
  envmon.reset();
}

/* CommandM100
 * 
 * Description
 *   .
 * 
 *   CommandM100()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
void CommandM100(char letter = 0) {
  if (letter == 'G' or letter == 0) {
    echo("No G commands defined.");  // Test: Pending
  }
  if (letter == 'M' or letter == 0) {
    echoln("M10\tSpeaker sond (F=frequency, D=duration)");  // Test: Pending
    echoln("M20\tLed color (R=red, G=blue, B=green)");  // Test: Pending
    echoln("M30\tMemory information");  // Test: Pending
    echoln("M40\tFan sensors");  // Test: Pending
    echoln("M50\tDistance sensor");  // Test: Pending
    echoln("M60\tTemeprature sensors (S1=system, S2=environment)");  // Test: Pending
    echoln("M70\tHumidity sensor");  // Test: Pending
    echoln("M80\tRelay status");  // Test: Pending
    echoln("M81\tRelay on");  // Test: Pending
    echoln("M82\tRelay off");  // Test: Pending
    echoln("M92\tSystem information");  // Test: Pending
    echoln("M99\tReset system");  // Test: Pending
    echoln("M100\tThis help message");  // Test: Pending
    echoln("M111\tDebug mode");  // Test: Pending
  }
}

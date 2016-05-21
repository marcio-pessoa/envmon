/* handlers.ino, envmon Mark II - Environment Monitor, Arduino handlers 
 *                                                     sketch file
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

void ActuatorHandler() {
  // Set fan speed
  // if (fan_period.check()) {
    // fan_speed.writeSpeed(map(constrain(system_temperature.valueRead(), 20, 50),  // temperature
                             // 20, 50,    // From (minimum and maximum)
                             // 0, 100));  // To (minimum and maximum)
  // }
  // Check maximum squirt period
  // It's necessary to protect bio over long period without water
  // if (squirt_maximum_period.check()) {
    // squirt_duration.reset();
    // squirt_duration.enable();
    // if (!relay1.status()) {
      // relay1.set(HIGH);
      // Console.println("Automatic pump: On");
    // }
  // }
  // Check squirt duration
  // It's necessary to protect water pump against over heat
  // if (squirt_duration.check()) {
    // if (relay1.status()) {
      // squirt_minimal_period.reset();
      // squirt_minimal_period.enable();
      // squirt_maximum_period.reset();
      // squirt_maximum_period.enable();
      // relay1.set(LOW);
      // Console.println("Automatic pump: Off");
    // }
  // }
  // Check for shot periods to act water pump
  // It's necessary to protect water pump against over heat
  // if (!squirt_minimal_period.check() and relay1.status() == HIGH) {
    // squirt_maximum_period.enable();
    // relay1.set(LOW);
    // Console.println("Automatic protection pump: Off");
  // }
}

void HealthCheckHandler() {
  // if (health_check_period.check()) {
    // Join alarm status
    // if (system_temperature.status() == UNKNOWN or
        // temperature.status() == UNKNOWN or
        // humidity.status() == UNKNOWN or
        // moisture.status() == UNKNOWN or
        // water.status() ==  UNKNOWN) {
      // general_status = UNKNOWN;
    // }
    // else if (system_temperature.status() == CRITICAL or
             // temperature.status() == CRITICAL or
             // humidity.status() == CRITICAL or
             // moisture.status() == CRITICAL or
             // water.status() ==  CRITICAL) {
      // general_status = CRITICAL;
    // }
    // else if (system_temperature.status() == WARNING or
             // temperature.status() == WARNING or
             // humidity.status() == WARNING or
             // moisture.status() == WARNING or
             // water.status() ==  WARNING) {
      // general_status = WARNING;
    // }
    // else if (system_temperature.status() == OK and
             // temperature.status() == OK and
             // humidity.status() == OK and
             // moisture.status() == OK and
             // water.status() ==  OK) {
      // general_status = OK;
    // }
    // else {
      // general_status = UNKNOWN;
    // }
    // Change LED status
    // switch (general_status) {
      // case OK: {
        // led.set(  0,   0, 255);  // Blue
        // break;
      // }
      // case WARNING: {
        // led.set(255, 255,   0);  // Yellow
        // break;
      // }
      // case CRITICAL: {
        // led.set(255,   0,   0);  // Red
        // break;
      // }
      // case UNKNOWN: 
      // default: {
        // led.set(255, 165,   0);  // Orange
        // break;
      // }
    // }
  // }
}

void NotificationHandler() {
  // LED
  led.set(0, 0, 255);  // Blue
  led.brightness(wave.sine());
  // Speaker
  if (speaker_period.check()) {
    speaker_beep_counter = (speaker_beep_counter + 1) % 8;
    // if (general_status > speaker_beep_counter) {
      // tone(speaker_pin, 2217, 60);
    // }
  }
}

void SensorsHandler() {
  // if (dht_period.check()) {
    // led.set(  0, 255,   0);  // Green
    // float h = dht.readHumidity();
    // float t = dht.readTemperature();
    //
    // if (isnan(h)) {
      // humidity.check(0);
      // humidity.force_check(UNKNOWN);
    // }
    // else {
      // humidity.check(h);
    // }
    //
    // if (isnan(t)) {
      // temperature.check(0);
      // temperature.force_check(UNKNOWN);
    // }
    // else {
      // temperature.check(t);
    // }
  // }
  // if (moisture_period.check()) {
    // led.set(  0, 255,   0);  // Green
    // moisture.check(soil_moisture.read());
  // }
  // if (water_period.check()) {
    // led.set(  0, 255,   0);  // Green
    // water.check(map(constrain(water_level.read(), 10, 50),  // sensor measure
                // 10, 50,    // From (limited input range)
                // 100, 0));  // To (percentual range)
  // }
  // if (system_status_period.check()) {
    // led.set(  0, 255,   0);  // Green
    //~ fan.check(fan_speed.readSpeed());
    // system_temperature.check(tmp36.read());
    // memory.check(getMemUsage());
  // }
}

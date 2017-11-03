#!/usr/bin/php-cli
<?php
/* nrdp_sender.php, envmon Mark I - Environment Monitor, Nagios NRDP sender
 * 
 * Author: Márcio Pessoa <marcio@pessoa.eti.br>
 * Contributors: none
 * 
 * Description:
 * 
 * Tip:
 *   Add this script to cron job.
 */

// NRDP configuration
$nrdp["url"] = "https://sciemon.com/nrdp/";
$nrdp["token"] = "Jenkiactat(twobfossAcyingIlsocPatHyratCoorphefeshepdecNieshvekvu";
$nrdp["hostname"] = "envmon";

// Config file
require("/opt/envmon/cfg/config.php");
// require("item.php");
// require("miner.php");

// Nagios states to IDs
$nagios_states["OK"] = 0;
$nagios_states["Warning"] = 1;
$nagios_states["Critical"] = 2;
$nagios_states["Unknown"] = 3;

// Nagios IDs to states
$nagios_id[0] = "OK";
$nagios_id[1] = "Warning";
$nagios_id[2] = "Critical";
$nagios_id[3] = "Unknown";

// Read status.json file
$status = json_decode(file_get_contents($url["ws_all"]), true);

// Host
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          "",
          $nagios_states["OK"],
          exec("python /opt/envmon/bin/check_myip.pyc"));

// Humidity
$service = "Humidity";
$unit = "%";
$state = $status["environment"]["humidity"]["status"];
$value = $status["environment"]["humidity"]["value"];
$info = $nagios_id[$state] . " - $service = " . round($value, 1) . "$unit";
$perfdata = "|$service=$value$unit";
// echo("Status information: $info");
// echo("Performance data: $perfdata");
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $info . $perfdata);

// Moisture
$service = "Moisture";
$unit = "%";
$state = $status["environment"]["moisture"]["status"];
$value = $status["environment"]["moisture"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|$service=$value$unit");

// Water
$service = "Water";
$unit = "%";
$state = $status["environment"]["water"]["status"];
$value = $status["environment"]["water"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|$service=$value$unit");

// Fan
$service = "Fan";
$unit = "%";
$state = $status["system"]["fan"]["status"];
$value = $status["system"]["fan"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|$service=$value$unit");

// Season
$value = $status["season"]["current"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"], "Season",
          $nagios_states["OK"], $value);

// CPU
$service = "CPU";
$unit = "%";
$state = $status["system"]["cpu"]["status"];
$value = $status["system"]["cpu"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|$service=$value$unit");

// Uptime
//~ send_data_($system["uptime"]);

// Temperature
$service = "Temperature";
$unit = "celsius";
$state1 = $status["environment"]["temperature"]["status"];
$value1 = $status["environment"]["temperature"]["value"];
$state2 = $status["system"]["temperature"]["status"];
$value2 = $status["system"]["temperature"]["value"];
$state = check_multiple_status(array($state1, 
                                     $state2));
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] .
          " - Environment = " . round($value1, 1) . " $unit" .
          ", Controller = " . round($value2, 1) . " $unit" .
          "|Environment=$value1$unit Controller=$value2$unit");

// Memory
$service = "Memory";
$unit = "%";
$state = $status["system"]["memory"]["status"];
$value = $status["system"]["memory"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|System=$value$unit");

// Storage
$service = "Storage";
$unit = "%";
$state = $status["system"]["intstorage"]["status"];
$value = $status["system"]["intstorage"]["value"];
send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"],
          $service,
          $state,
          $nagios_id[$state] . " - $service = " . 
          round($value, 1) . 
          "$unit|Internal=$value$unit");


/* check_multiple_status
 * 
 * Description
 *  description.
 * 
 *  function_name()
 * 
 * Parameters
 *  paramaters.
 * 
 * Returns
 *  ?
 */
function send_data_($item) {
  //
  global $nagios_states;
  global $nrdp;
  //
  $data = get_data($item);
  //
  send_data($nrdp["url"], $nrdp["token"], $nrdp["hostname"], $data[1], $nagios_states[$data[2]],
            "$data[2] - $data[1] = " . round($data[3], 1) . "$data[4]|$data[1]=$data[3]$data[4]");
}

/* check_multiple_status
 * 
 * Description
 *  description.
 * 
 *  function_name()
 * 
 * Parameters
 *  paramaters.
 * 
 * Returns
 *  ?
 */
function check_multiple_status($status) {
  // Import Nagios states IDs
  global $nagios_states;
  //
  $return_status = $nagios_states["OK"];
  // Run over all elements in an array
  for ($i = 0; $i < count($status); $i++) {
    if ($status[$i] == $nagios_states["OK"] and
        $return_status == $nagios_states["OK"]) {
      $return_status = $nagios_states["OK"];
    }
    else if ($status[$i] == $nagios_states["Warning"] and
             $return_status <= $nagios_states["Warning"]) {
      $return_status = $nagios_states["Warning"];
    }
    else if ($status[$i] == $nagios_states["Critical"] and
             $return_status <= $nagios_states["Critical"]) {
      $return_status = $nagios_states["Critical"];
    }
    else if ($status[$i] == $nagios_states["Unknown"] and
             $return_status <= $nagios_states["Unknown"]) {
      $return_status = $nagios_states["Unknown"];
    }
  }
  return $return_status;
}

/* send_data
 * 
 * Description
 *  description.
 * 
 *  function_name()
 * 
 * Parameters
 *  paramaters.
 * 
 * Returns
 *  ?
 */
function send_data($url, $token, $host, $service, $state, $output) {
  // Import $default variable
  global $default;
  // Build command string
  $command = "python /opt/envmon/bin/send_nrdp.pyc " .
             "--url=\"" . $url . "\" " .
             "--token=\"" . $token . "\" " .
             "--hostname=\"" . $host . "\" " .
             "--service=\"" . $service . "\" " .
             "--state=" . $state . " " .
             "--output=\"" . $output . "\"" .
             "\n";
  // Show command string on debug mode
  if ($default["debug"] == true) {
    print($command);
  }
  // Run command
  exec($command);
}
?>

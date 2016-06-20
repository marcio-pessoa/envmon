<?php
/* action.php, envmon Mark II - Environment Monitor
 * Web interface - Action
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg["threshold"]["temp"]["env"]["min"]["warning"] =
        m_post("threshold_temp_env_min_warning");
}

$sub = isset($_GET[sub]) ? $_GET[sub] : null;
if ($sub == 'read') {
  error_reporting(E_ALL);

  echo "Creating socket... ";

  $service_port = 2323;
  $address = gethostbyname('127.0.0.1');

  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  if ($socket === false) {
      echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
  } else {
      echo "OK.<br/>";
  }

  echo "Attempting to connect to '$address' on port '$service_port'... ";
  $result = socket_connect($socket, $address, $service_port);
  if ($result === false) {
      echo "socket_connect() failed.<br/>Reason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
  } else {
      echo "OK.<br/>";
  }

  $in = "read\n";
  $in .= "\n";
  $in .= "bye\n";
  $out = '';

  echo "Sending request...";
  socket_write($socket, $in, strlen($in));
  echo "OK.<br/>";

  echo "Reading response:<br/>";
  while ($out = socket_read($socket, 2048)) {
      echo $out;
  }

  echo "Closing socket... ";
  socket_close($socket);
  echo "OK.<br/>";
}

require($directory["html"] . "action.html");
?>

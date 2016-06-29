<?php
/* action.php, envmon Mark II - Environment Monitor
 * Web interface - Action
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

// error_reporting(E_ALL);

ob_implicit_flush();

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg["threshold"]["temp"]["env"]["min"]["warning"] =
        m_post("threshold_temp_env_min_warning");
}

$sub = isset($_GET[sub]) ? $_GET[sub] : null;
if ($sub == 'read') {
    echo "Creating socket... ";

    $service_port = 2323;
    $address = gethostbyname('localhost');

    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . 
        socket_strerror(socket_last_error());
    } 
    else {
        echo "OK";
    }
    echo ".<br/>";

    echo "Attempting to connect to '$address' on port '$service_port'... ";
    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        echo "socket_connect() failed.<br/>Reason: ($result) " . 
        socket_strerror(socket_last_error($socket));
    }
    else {
        echo "OK";
    }
    echo ".<br/>";

    $tx = "read\r\n";
    $rx = '';

    echo "Sending request:";
    echo "<pre>";
    echo $tx;
    echo "</pre>";
    $result = socket_write($socket, $tx, strlen($tx));
    if ($result === false) {
        echo "socket_write() failed.<br/>Reason: ($result) " . 
        socket_strerror(socket_last_error($socket)) . "<br/>";
    }
    else {
        echo "OK - bytes sent: " . $result;
    }
    echo ".<br/>";

    echo "Reading response...<br/>";
    while (true) {
        socket_recv($socket, $buffer, 1, 0);
        $rx .= $buffer;
        if ($buffer == "\n") {
            break;
        }
    }
    echo "<pre>";
    echo $rx;
    echo "</pre>";








    $tx = "squirt\r\n";
    $rx = '';

    echo "Sending request:";
    echo "<pre>";
    echo $tx;
    echo "</pre>";
    $result = socket_write($socket, $tx, strlen($tx));
    if ($result === false) {
        echo "socket_write() failed.<br/>Reason: ($result) " . 
        socket_strerror(socket_last_error($socket)) . "<br/>";
    }
    else {
        echo "OK - bytes sent: " . $result;
    }
    echo ".<br/>";

    echo "Reading response...<br/>";
    while (true) {
        socket_recv($socket, $buffer, 1, 0);
        $rx .= $buffer;
        if ($buffer == "\n") {
            break;
        }
    }
    echo "<pre>";
    echo $rx;
    echo "</pre>";













    echo "Closing socket... ";
    socket_close($socket);
    echo "OK";
    echo ".<br/>";
}

require($directory["html"] . "action.html");
?>

<?php
/* reload.php, envmon Mark I - Environment Monitor
 * Web interface - Restart
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
ob_implicit_flush();
switch ($action) {
    case "proced":
        require($directory["html"] . "restart.html");
        break;
    case "restart":
        require($directory["cgi"] . "connector.php");
        $c = new Connector();
        $c->open($url["service_host"], $url["service_port"]);
        $c->write("kill");
        $d = $c->read();
        $c->close();
        echo $d;
        sleep(5);
        exec($files["envmon_manager"] . " restart",
             $status, $return);
        require($directory["html"] . "restart_ok.html");
        break;
    default:
        require($directory["html"] . "restart.html");
        break;
}
?>


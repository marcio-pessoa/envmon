<?php
/* reload.php, envmon Mark I - Environment Monitor
 * Web interface - Reload
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
ob_implicit_flush();
switch ($action) {
    case "proced":
        require($directory["html"] . "reload.html");
        break;
    case "reload":
        require($directory["cgi"] . "connector.php");
        $c = new Connector();
        $c->open($url["service_host"], $url["service_port"]);
        $c->write("reload");
        $d = $c->read();
        $c->close();
        echo $d;
        require($directory["html"] . "reload_ok.html");
        break;
    default:
        require($directory["html"] . "reload.html");
        break;
}
?>


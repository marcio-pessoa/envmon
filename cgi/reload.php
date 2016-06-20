<?php
/* reload.php, envmon Mark II - Environment Monitor
 * Web interface - Reload
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
switch ($action) {
    case "proced":
        require($directory["html"] . "reload.html");
        break;
    case "reload":
        exec($files["envmon_manager"] . " restart",
             $status, $return);
        require($directory["html"] . "reload_ok.html");
        break;
    default:
        require($directory["html"] . "reload.html");
        break;
}
?>

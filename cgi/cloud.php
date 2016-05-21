<?php
/* cloud.php, envmon Mark II - Environment Monitor
 * Web interface - Cloud options
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg['system']['remoteadmin'] =
        m_post("system_remoteadmin");
    json_write($config["running"], $cfg);
}
if($cfg['system']['remoteadmin'])
    $system_remoteadmin = "checked";
require($directory["html"] . "cloud.html");
?>

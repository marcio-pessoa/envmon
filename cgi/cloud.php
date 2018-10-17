<?php
/* cloud.php, envmon Mark I - Environment Monitor
 * Web interface - Cloud options
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg['system']['remoteadmin'] =
        m_post("system_remoteadmin");
    json_write($files["running"], $cfg);
}
if($cfg['system']['remoteadmin'])
    $system_remoteadmin = "checked";
require($directory["html"] . "cloud.html");
?>

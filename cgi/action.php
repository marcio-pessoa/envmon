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
require($directory["html"] . "action.html");
?>

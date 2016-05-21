<?php
/* reset.php, envmon Mark II - Environment Monitor
 * Web interface - Reset to factory defaults
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $file = fopen($config["default"], "r");
    $json = fread($file, filesize($config["default"]));
    fclose($file);
    if(!file_put_contents($config["running"], $json)) {
        msg("error_saving") . " " . $file;
    }
    require($directory["html"] . "reset_ok.html");
}
else {
    require($directory["html"] . "reset.html");
}
?>

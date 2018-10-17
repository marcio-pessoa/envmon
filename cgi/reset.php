<?php
/* reset.php, envmon Mark I - Environment Monitor
 * Web interface - Reset to factory defaults
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $file = fopen($files["default"], "r");
    $json = fread($file, filesize($files["default"]));
    fclose($file);
    if(!file_put_contents($files["running"], $json)) {
        msg("error_saving") . " " . $file;
    }
    require($directory["html"] . "reset_ok.html");
}
else {
    require($directory["html"] . "reset.html");
}
?>

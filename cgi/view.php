<?php
/* content.php, envmon Mark II - Environment Monitor
 * Web interface - View
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

if (file_exists($files["status"])) {
    $file = fopen($files["status"], "r");
    $json = fread($file, filesize($files["status"]));
    fclose($file);
    $status = json_decode($json, true);
}
else {
    print("ERROR: Status file not found.");
}
require($directory["html"] . "view.html");
?>

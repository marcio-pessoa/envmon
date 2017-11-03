<?php
/* restore.php, envmon Mark I - Environment Monitor
 * Web interface - Restore backup
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
$tmp = "/tmp/config.json";
switch ($action) {
    case "proced":
        if ($_FILES['restore_file']['name'] == "") {
            require($directory["html"] . "restore_notfound.html");
            break;
        }
        if (move_uploaded_file($_FILES['restore_file']['tmp_name'], $tmp)) {
            $file = fopen($tmp, "r");
            $json = fread($file, filesize($tmp));
            fclose($file);
            if(!json_decode($json)) {
                require($directory["html"] . "restore_corrupted.html");
                break;
            }
        }
        require($directory["html"] . "restore.html");
        break;
    case "restore":
        $file = fopen($tmp, "r");
        $json = fread($file, filesize($tmp));
        fclose($file);
        if(!file_put_contents($files["running"], $json)) {
            echo "Error saving " . $file;
        }
        require($directory["html"] . "restore_ok.html");
        break;
    default:
        require($directory["html"] . "restore_notfound.html");
        break;
}
?>

<?php
/* logs.php, envmon Mark II - Environment Monitor
 * Web interface - Logs
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_GET[action]) ? $_GET[action] : null;
if ($action == "clear") {
    file_put_contents($files["log"], "");
}
$content = nl2br(insert_file($files["log"]));
require($directory["html"] . "logs_events.html");

/* insert_file
 * 
 * Description
 *   .
 * 
 *   ()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
function insert_file($file, $limmit=100) {
    if (!file_exists($file)) {
        return "File not found.";
    }
    $handle = fopen($file, "r");
    $content = "";
    if (filesize($file) == 0) {
        return "File empty.";
    }
    $content = fread($handle, filesize($file));
    for ($x_pos = 0, $ln = 0, $content = array(); 
        fseek($handle, $x_pos, SEEK_END) !== -1; $x_pos--) {
        $char = fgetc($handle);
        if ($char === "\n") {
            $ln++;
            continue;
        }
        $content[$ln] = $char . ((array_key_exists($ln, $content)) ? 
                                 $content[$ln] : '');
        if ($ln > $limmit) {
            break;
        }
    }
    fclose($handle);
    return implode("\n", $content);
}
?>

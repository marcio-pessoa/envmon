<?php
/* reboot.php, envmon Mark II - Environment Monitor
 * Web interface - Reboot
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
  print("<h1><font color=\"red\">Restarting...</font></h1>");
  print("<meta http-equiv=\"refresh\" content=\"4;url=/\" />");
  system("reboot");
}
require($directory["html"] . "reboot.html");
?>

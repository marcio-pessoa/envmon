<?php
/* logs.php, envmon Mark I - Environment Monitor
 * Web interface - Logs
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$content = nl2br(shell_exec("logread"));
require($directory["html"] . "logs_system.html");
?>

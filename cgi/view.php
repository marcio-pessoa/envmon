<?php
/* content.php, envmon Mark II - Environment Monitor
 * Web interface - View
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$status = json_decode(file_get_contents($url["ws_all"]), true);
$timezone = str_replace("_", " ", $cfg['system']['timezone']);
$timestamp = date('U');
$timestamp_UTC = $timestamp;
$uptime = exec("cat /proc/uptime | cut -d ' ' -f 1");
//TODO(Márcio): Veritifcar se é necessário converter a variavel timestamp para UTC
$seconds_to_next_season = strtotime(date($status["season"]["end"])) - 
                          $timestamp_UTC;
require($directory["html"] . "view.html");
?>

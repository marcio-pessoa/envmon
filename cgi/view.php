<?php
/* content.php, envmon Mark II - Environment Monitor
 * Web interface - View
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$status = json_decode(file_get_contents($url["ws_all"]), true);
exec($files["envmon_manager"] . " status",
     $status["running"],
     $status["running_id"]);
$status["running"] = $status["running_id"] == 0 ? "running" : "stopped";
$cpu = json_decode(file_get_contents($url["ws_cpu"]), true);
$memory = json_decode(file_get_contents($url["ws_memory"]), true);
$timezone = str_replace("_", " ", $cfg['system']['timezone']);
$timestamp = date('U');
$uptime = exec("cat /proc/uptime | cut -d ' ' -f 1");
//TODO(Márcio): Converter para UTC
$seconds_to_next_season = strtotime(date($status["season"]["end"])) - 
                          $timestamp;
require($directory["html"] . "view.html");
?>

<?php
/* config.php, envmon Mark II - Environment Monitor, Web interface config file
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

// Directory list
$directory["work"] = "/opt/envmon/";
$directory["html"] = $directory["work"] . "html/";
$directory["cgi"] = $directory["work"] . "cgi/";
$directory["zoneinfo"] = "/usr/share/zoneinfo/";
$directory["strings"] = $directory["work"] . "strings/";

// Files
$files["running"] = $directory["work"] . "cfg/config.json";
$files["default"] = $directory["work"] . "cfg/default.json";
$files["seasons"] = $directory["work"] . "cfg/seasons.json";
$files["languages"] = $directory["work"] . "cfg/languages.json";
$files["themes"] = $directory["work"] . "cfg/themes.json";
$files["log"] = $directory["work"] . "log/event.log";
$files["status"] = $directory["work"] . "var/status.json";
$files["envmon_manager"] = $directory["work"] . "bin/envmon";

// URLs
$url["main"] = "http://127.0.0.1/www/";
$url["ws_all"] = $url["main"] . "?content=status";
$url["ws_cpu"] = $url["main"] . "?content=status&sub=cpu";
$url["ws_memory"] = $url["main"] . "?content=status&sub=memory";
// $url["ws_swap"] = $url["main"] . "?content=status&sub=swap";
// $url["ws_intstorage"] = $url["main"] . "?content=status&sub=intstorage";
// $url["ws_extstorage"] = $url["main"] . "?content=status&sub=extstorage";

// Defaults
$default["title"] = true;
$default["label"] = false;
$default["debug"] = false;
$default["content"] = "view";
$default["speaker"] = false;
$default["language"] = "English";
$default["timezone"] = "Sao_Paulo";

// Currents
$current["content"] = $default["content"];
$current["language"] = $default["language"];

// Menu
$menu_items = array("view",
                    "action",
                    "setup",
                    array("system",
                          "thresholds",
                          "timers",
                          "notifications",
                          "cloud",
                          "configuration"),
                    "logs",
                    array("logs_events",
                          "logs_system"),
                    "help",
                    "about");
?>

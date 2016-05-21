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

// Configuration files
$config["running"] = $directory["work"] . "cfg/config.json";
$config["default"] = $directory["work"] . "cfg/default.json";
$config["seasons"] = $directory["work"] . "cfg/seasons.json";
$config["languages"] = $directory["work"] . "cfg/languages.json";
$config["themes"] = $directory["work"] . "cfg/themes.json";

// Log files
$log["environment"] = $directory["work"] . "log/environment.log";
$log["controller"] = $directory["work"] . "log/controller.log";
$log["system"] = $directory["work"] . "log/system.log";

// Defaults
$default["title"] = true;
$default["label"] = false;
$default["debug"] = false;
$default["content"] = "view";
$default["speaker"] = false;
$default["language"] = "English";

// Currents
$current["status"] = $directory["work"] . 'var/status.json';
$current["content"] = $default["content"];
$current["timezone"] = 'Sao_Paulo';
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
                    "help",
                    "about");
?>

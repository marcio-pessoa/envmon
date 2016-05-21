<?php
/* index.php, envmon Mark II - Environment Monitor, Web interface index file
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

// Config file
require("/opt/envmon/cfg/config.php");
// Stopwatch library
require($directory["cgi"] . "stopwatch.php");
// Language support
require($directory["cgi"] . "language.php");
// Theme support
require($directory["cgi"] . "theme.php");

// Variable: $debug (bool)
$debug = isset($_REQUEST["debug"]) ? $_REQUEST["debug"] : $default["debug"];
// Variable: $quiet (bool)
$title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : $default["title"];
// Variable: $label (bool)
$label = isset($_REQUEST["label"]) ? $_REQUEST["label"] : $default["label"];

// Start stopwatch
if ($debug) {
    stopwatch();
}

// Read JSON config file
if (file_exists($config["running"])) {
    $file = fopen($config["running"], "r");
    $json = fread($file, filesize($config["running"]));
    fclose($file);
    $cfg = json_decode($json, true);
}
else {
    print("ERROR: Configuration file not found.");
}

// Set configured language
$current["language"] = $cfg['system']['language'];
// if ($current["language"] != "English") {
    $language_default = language_load("English");
// }
$language_defined = language_load($current["language"]);

// Set configured theme
$current["theme_file"] = theme_set($cfg['system']['theme']);

// Set time zone
date_default_timezone_set($current["timezone"]);

// Load content
//TODO(Marcio): Filter $content varible!!!
$current["content"] = isset($_GET["content"]) ? $_GET["content"] : $default["content"];

// Item library
// require($directory["cgi"] . "item.php");

require($directory["html"] . "header.html");

if ($title) {
    require($directory["html"] . "title.html");
    require($directory["cgi"] . "menu.php");
}

require($directory["cgi"] . $current["content"] . ".php");

if ($label) {
    require($directory["html"] . "label.html");
}

if ($title) {
    require($directory["html"] . "copyright.html");
}

require($directory["html"] . "footer.html");

if ($debug) {
    print("<div id=\"debug\"><pre>");
    print("Build time: " . stopwatch() . "ms\n");
    print("default: ");
    print_r($default);
    print("current: ");
    print_r($current);
    print("</pre></div>");
}
?>

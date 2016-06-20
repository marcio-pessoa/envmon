<?php
/* content.php, envmon Mark II - Environment Monitor
 * Web interface - View
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

header("Content-Type: text/plain");


if (file_exists($files["status"])) {
    for ($i=0; $i<=100; $i++) {
        $size = filesize($files["status"]);
        if ($size > 0) {
            $file = fopen($files["status"], "r");
            $json = fread($file, $size);
            fclose($file);
            $status = json_decode($json, true);
            break;
        }
        usleep(1000);
        clearstatcache();
    }
}
else {
    print("ERROR: Status file not found.");
}

// Set configured language
$current["language"] = $cfg['system']['language'];
// if ($current["language"] != "English") {
    $language_default = language_load("English");
// }
$language_defined = language_load($current["language"]);

$current["sub"] = isset($_GET["sub"]) ? $_GET["sub"] : null;
if ($current["sub"] != null)
switch ($current["sub"]) {
    case "daemon":
        exec($files["envmon_manager"] . " status",
             $status,
             $status_id);
        $status = $status_id == 0 ? "running" : "stopped";
        $data = '{"daemon":{"status":"' . msg($status) .
                '", "status_id":"' . $status_id . '"}}';
        echo $data;
        exit;
    case "end":
        $current["timezone"] = isset($cfg['system']['timezone']) ? 
                               $cfg['system']['timezone'] : $default["timezone"];
        date_default_timezone_set($current["timezone"]);
        $timestamp = date('U');
        echo strtotime(date($status["season"]["end"])) - $timestamp;
        exit;
    case "cpuonly":
        echo $status["system"]["cpu"]["value"];
        exit;
    case "systemp":
        $current["sub"] = "temperature";
    case "fan":
    case "cpu":
    case "memory":
    case "swap":
    case "intstorage":
    case "extstorage":
        echo json_encode($status["system"][$current["sub"]],
                         JSON_PRETTY_PRINT);
        exit;
    case "envtemp":
    case "humidity":
    case "moisture":
    case "water":
        echo json_encode($status["environment"][$current["sub"]],
                         JSON_PRETTY_PRINT);
        exit;
    default:
        echo "Invalid";
        exit;
}
echo json_encode($status, JSON_PRETTY_PRINT);
?>

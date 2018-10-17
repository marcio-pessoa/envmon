<?php
/* content.php, envmon Mark I - Environment Monitor
 * Web interface - View
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */

header("Content-Type: text/plain");

if (file_exists($files["status"])) {
    // This is a retry and timeout engine
    // Used to wait when status file is already open or empty. 
    for ($i=0; $i<=90; $i++) {  // Retry 90 times
        $size = filesize($files["status"]);
        if ($size > 0) {
            $file = fopen($files["status"], "r");
            $json = fread($file, $size);
            fclose($file);
            $status = json_decode($json, true);
            break;
        }
        usleep(1000);  // Wait for 1 millisecond to try again.
        // Total wait time is 90 milliseconds, or 1 second and a half.
        clearstatcache();
    }
}
else {
    print("ERROR: Can't retrieve data from status file.");
}

$current["sub"] = isset($_GET["sub"]) ? $_GET["sub"] : null;
if ($current["sub"] != null)
switch ($current["sub"]) {
    case "daemon":
        exec($files["envmon_manager"] . " status",
             $status,
             $status_id);
        $status = $status_id == 0 ? "running" : "stopped";
        $date_time = date("Y-m-d H:i:s");
        $data = json_decode('{"daemon":{"status":"' . msg($status) .
                            '", "status_id":"' . $status_id .
                            '", "moment":"' . $date_time . '"}}');
        echo json_encode($data, JSON_PRETTY_PRINT);
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
    case "wifisignal":
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

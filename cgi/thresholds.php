<?php
/* thresholds.php, envmon Mark I - Environment Monitor
 * Web interface - Thesholds
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg["threshold"]["temp"]["env"]["min"]["warning"] =
        m_post("threshold_temp_env_min_warning");
    $cfg["threshold"]["temp"]["env"]["min"]["critical"] =
        m_post("threshold_temp_env_min_critical");
    $cfg["threshold"]["temp"]["env"]["max"]["warning"] =
        m_post("threshold_temp_env_max_warning");
    $cfg["threshold"]["temp"]["env"]["max"]["critical"] =
        m_post("threshold_temp_env_max_critical");
    $cfg["threshold"]["temp"]["sys"]["min"]["warning"] =
        m_post("threshold_temp_sys_min_warning");
    $cfg["threshold"]["temp"]["sys"]["min"]["critical"] =
        m_post("threshold_temp_sys_min_critical");
    $cfg["threshold"]["temp"]["sys"]["max"]["warning"] =
        m_post("threshold_temp_sys_max_warning");
    $cfg["threshold"]["temp"]["sys"]["max"]["critical"] =
        m_post("threshold_temp_sys_max_critical");
    $cfg["threshold"]["humidity"]["warning"] =
        m_post("threshold_humidity_warning");
    $cfg["threshold"]["humidity"]["critical"] =
        m_post("threshold_humidity_critical");
    $cfg["threshold"]["water"]["warning"] =
        m_post("threshold_water_warning");
    $cfg["threshold"]["water"]["critical"] =
        m_post("threshold_water_critical");
    $cfg["threshold"]["moisture"]["spring"]["warning"] =
        m_post("threshold_moisture_spring_warning");
    $cfg["threshold"]["moisture"]["spring"]["critical"] =
        m_post("threshold_moisture_spring_critical");
    $cfg["threshold"]["moisture"]["summer"]["warning"] =
        m_post("threshold_moisture_summer_warning");
    $cfg["threshold"]["moisture"]["summer"]["critical"] =
        m_post("threshold_moisture_summer_critical");
    $cfg["threshold"]["moisture"]["fall"]["warning"] =
        m_post("threshold_moisture_fall_warning");
    $cfg["threshold"]["moisture"]["fall"]["critical"] =
        m_post("threshold_moisture_fall_critical");
    $cfg["threshold"]["moisture"]["winter"]["warning"] =
        m_post("threshold_moisture_winter_warning");
    $cfg["threshold"]["moisture"]["winter"]["critical"] =
        m_post("threshold_moisture_winter_critical");
    json_write($files["running"], $cfg);
}
require($directory["html"] . "thresholds.html");
?>

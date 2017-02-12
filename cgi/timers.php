<?php
/* content.php, envmon Mark II - Environment Monitor
 * Web interface - Timers
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg['timer']['environment']['temperature'] =
        m_post("timer_environment_temperature", "integer", array(1, 600));
    $cfg['timer']['environment']['humidity'] =
        m_post("timer_environment_humidity", "integer", array(1, 600));
    $cfg['timer']['environment']['moisture'] =
        m_post("timer_environment_moisture", "integer", array(1, 600));
    $cfg['timer']['environment']['water'] =
        m_post("timer_environment_water", "integer", array(1, 600));
    $cfg['timer']['system']['temperature'] =
        m_post("timer_system_temperature", "integer", array(1, 600));
    $cfg['timer']['system']['fan'] =
        m_post("timer_system_fan", "integer", array(1, 600));
    $cfg['timer']['system']['cpu'] =
        m_post("timer_system_cpu", "integer", array(1, 600));
    $cfg['timer']['system']['memory'] =
        m_post("timer_system_memory", "integer", array(1, 600));
    $cfg['timer']['system']['swap'] =
        m_post("timer_system_swap", "integer", array(1, 600));
    $cfg['timer']['system']['intstorage'] =
        m_post("timer_system_intstorage", "integer", array(1, 600));
    $cfg['timer']['system']['extstorage'] =
        m_post("timer_system_extstorage", "integer", array(1, 600));
    $cfg['timer']['squirt']['duration'] =
        m_post("timer_squirt_duration", "integer", array(1, 20));
    $cfg['timer']['squirt']['minimum'] =
        m_post("timer_squirt_minimum", "integer", array(1, 7200));
    $cfg['timer']['squirt']['maximum'] =
        m_post("timer_squirt_maximum", "integer", array(1, 14400));
    $cfg['timer']['display']['timeout'] =
        m_post("timer_display_timeout", "integer", array(1, 600));
    $return = json_write($files["running"], $cfg);
    if ($return != false) {
        echo("<script>alert('As alterações foram aplicadas.');</script>");
    }
}
require($directory["html"] . "timers.html");
?>

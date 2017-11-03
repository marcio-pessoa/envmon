<?php
/* notifications.php, envmon Mark I - Environment Monitor
 * Web interface - Notifications setup
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg['notification']['device']['speaker'] = 
        m_post("notification_device_speaker");
    $cfg['notification']['device']['led'] =
        m_post("notification_device_led");
    $cfg['notification']['email']['user'] =
        m_post("notification_email_user", "string");
    $cfg['notification']['email']['password'] =
        m_post("notification_email_password", "string");
    $cfg['notification']['email']['server'] =
        m_post("notification_email_server", "string");
    $cfg['notification']['email']['port'] =
        m_post("notification_email_port", "integer", array(1, 65535));
    $cfg['notification']['email']['timeout'] =
        m_post("notification_email_timeout");
    $cfg['notification']['email']['tls'] =
        m_post("notification_email_tls");
    $cfg['notification']['email']['to'] =
        m_post("notification_email_to", "string");
    $cfg['notification']['email']['temperature'] =
        m_post("notification_email_temperature");
    $cfg['notification']['email']['humidity'] =
        m_post("notification_email_humidity");
    $cfg['notification']['email']['water'] =
        m_post("notification_email_water");
    $cfg['notification']['email']['moisture'] =
        m_post("notification_email_moisture");
    $cfg['notification']['email']['season'] =
        m_post("notification_email_season");
    $cfg['notification']['twitter']['token'] =
        m_post("notification_twitter_token", "string");
    $cfg['notification']['twitter']['token_secret'] =
        m_post("notification_twitter_token_secret", "string");
    $cfg['notification']['twitter']['consumer_secret'] =
        m_post("notification_twitter_consumer_secret", "string");
    $cfg['notification']['twitter']['consumer_key'] =
        m_post("notification_twitter_consumer_key", "string");
    $cfg['notification']['twitter']['temperature'] =
        m_post("notification_twitter_temperature");
    $cfg['notification']['twitter']['humidity'] =
        m_post("notification_twitter_humidity");
    $cfg['notification']['twitter']['water'] =
        m_post("notification_twitter_water");
    $cfg['notification']['twitter']['moisture'] =
        m_post("notification_twitter_moisture");
    $cfg['notification']['twitter']['season'] =
        m_post("notification_twitter_season");
    $cfg['notification']['nrdp']['url'] =
        m_post("notification_nrdp_url", "string");
    $cfg['notification']['nrdp']['token'] =
        m_post("notification_nrdp_token", "string");
    $cfg['notification']['nrdp']['freshness'] =
        m_post("notification_nrdp_freshness", "integer", array(1, 3000));
    $cfg['notification']['nrdp']['obsess'] =
        m_post("notification_nrdp_obsess");
    json_write($files["running"], $cfg);
}
if($cfg['notification']['device']['speaker'])
    $notification_device_speaker = "checked";
if($cfg['notification']['device']['led'])
    $notification_device_led = "checked";
if($cfg['notification']['email']['tls'])
    $notification_email_tls = "checked";
if($cfg['notification']['email']['temperature'])
    $notification_email_temperature = "checked";
if($cfg['notification']['email']['humidity'])
    $notification_email_humidity = "checked";
if($cfg['notification']['email']['water'])
    $notification_email_water = "checked";
if($cfg['notification']['email']['moisture'])
    $notification_email_moisture = "checked";
if($cfg['notification']['email']['season'])
    $notification_email_season = "checked";
if($cfg['notification']['twitter']['temperature'])
    $notification_twitter_temperature = "checked";
if($cfg['notification']['twitter']['humidity'])
    $notification_twitter_humidity = "checked";
if($cfg['notification']['twitter']['water'])
    $notification_twitter_water = "checked";
if($cfg['notification']['twitter']['moisture'])
    $notification_twitter_moisture = "checked";
if($cfg['notification']['twitter']['season'])
    $notification_twitter_season = "checked";
if($cfg['notification']['nrdp']['obsess'])
    $notification_nrdp_obsess = "checked";
require($directory["html"] . "notifications.html");
?>

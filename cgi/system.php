<?php
/* system.php, envmon Mark II - Environment Monitor
 * Web interface - System setup
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg['system']['hostname'] =
        m_post("system_hostname", "string");
    $cfg['system']['location'] =
        m_post("system_location", "string");
    $cfg['system']['hemisphere'] =
        m_post("system_hemisphere", "string");
    $cfg['system']['timezone'] =
        m_post("system_timezone", "string");
    $cfg['authentication']['username'] =
        m_post("authentication_username", "string");
    // If language changes
    if ($cfg['system']['language'] != m_post("system_language", "string")) {
        $cfg['system']['language'] = m_post("system_language", "string");
        $reload = true;
    }
    // If theme changes
    if ($cfg['system']['theme'] != m_post("system_theme", "string")) {
        $cfg['system']['theme'] = m_post("system_theme", "string");
        $reload = true;
    }
    // If password changes
    if ((m_post("authentication_password1", "string") != "" and
         m_post("authentication_password2", "string") != "") and
        m_post("authentication_password1", "string") ==
         m_post("authentication_password2", "string")) {
    $cfg['authentication']['password'] = 
        crypt(m_post("authentication_password1", "string"));
    }
    // Save JSON file
    json_write($config["running"], $cfg);
    // Reload page on language change
    if ($reload) {
        print("<META HTTP-EQUIV=\"refresh\" CONTENT=\"0\">");
    }
}
// Create language list options
$language_options = "";
foreach (language_list() as $language) {
    $language_options .= "    <option value=\"$language\">" . $language . 
                             "</option>\n";
}
// Create themes list options
$theme_options = "";
foreach (theme_list() as $theme) {
    $theme_options .= "    <option value=\"$theme\">" . $theme . 
                             "</option>\n";
}
require($directory["html"] . "system.html");
?>

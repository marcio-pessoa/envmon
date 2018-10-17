<?php
/* action.php, envmon Mark I - Environment Monitor
 * Web interface - Action
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */


$action = isset($_POST[action]) ? $_POST[action] : null;
if ($action == "proced") {
    $cfg["threshold"]["temp"]["env"]["min"]["warning"] =
        m_post("threshold_temp_env_min_warning");
}

$sub = isset($_GET[sub]) ? $_GET[sub] : null;
if ($sub != '') {
    require($directory["cgi"] . "connector.php");
    $c = new Connector();
    $c->open($url["service_host"], $url["service_port"]);
}
switch ($sub) {
    case 'read':
    case 'squirt':
    case 'reload':
        $c->write($sub);
        break;
    default:
        break;
}
if ($sub != '') {
    $d = $c->read();
    $c->close();
    echo $d;
    commandCheck($d);
}
require($directory["html"] . "action.html");

/* cmd
 * 
 * Description
 *   .
 * 
 *   ()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
// function cmd($command, $expect) {
        // $c->write('reload');
        // echo $c->read();
        // commandCheck($c);
        // $c->close();
        // break;
// }

/* checkCommand
 * 
 * Description
 *   .
 * 
 *   ()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
function commandCheck($return) {
    if (strlen($return) > 0) {
        $message = msg("cmd_ok");
    }
    else {
        $message = msg("cmd_error");
    }
    echo("<script>alert('" . $message . "');</script>");
}
?>

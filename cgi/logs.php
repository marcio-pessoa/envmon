<?php
/* logs.php, envmon Mark II - Environment Monitor
 * Web interface - Logs
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

// Load content
$current["sub"] = isset($_GET["sub"]) ? $_GET["sub"] : null;
if ($current["sub"] != null) {
    require($directory["cgi"] . $current["sub"] . ".php");
}
else
{
    print("<ul>\n");
    foreach($menu_items as &$item) {
        if (is_array($item) and $last_item == "logs") {
            foreach($item as &$sub_item) {
                print("  <li><a href=\"?content=logs&sub=" . 
                      $sub_item .
                      "\">" . msg("$sub_item") . "</a></li>\n");
            }
        }
        $last_item = $item;
    }
    print("</ul>\n");
}

/* m_post
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
function m_post($variable, $type="integer", $range=array(0, 100)) {
    $default = 0;
    global $_POST;
    $value = isset($_POST[$variable]) ?
             $_POST[$variable] :
             $default;
    $value = html_entity_decode(trim($value));
    switch ($type) {
        case "integer":
            $value = intval($value);
            if ($value < $range[0] or $value > $range[1]) {
                $value = $default;
            }
            break;
        case "string":
        default:
            break;
    }
    return $value;
}

/* json_write
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
function json_write($file, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    if(!file_put_contents($file, $json)) {
        echo "Error saving " . $file;
    }
}
?>

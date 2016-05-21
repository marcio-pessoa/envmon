<?php
/* theme.php, envmon Mark II - Environment Monitor
 * Web interface - Theme library
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

/* 
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
function theme_set($theme) {
    $file = theme_list($theme);
    if ($file == false) {
        print("ERROR: Theme not configured.");
        return true;
    }
    return $file;
}

/* 
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
function theme_list($theme = "") {
    global $config;
    if (file_exists($config["themes"])) {
        $file = fopen($config["themes"], "r");
        $json = fread($file, filesize($config["themes"]));
        fclose($file);
        $themes = json_decode($json, true);
    }
    else {
        print("ERROR: Theme list file not found.");
    }
    // Look for all available themes
    if ($theme == "") {
        $list = array();
        while ($theme = current($themes["theme"])) {
            $key = key($themes["theme"]);
            if ($themes["theme"][$key]["enable"] == 1) {
                array_push($list, key($themes["theme"]));
            }
            next($themes["theme"]);
        }
        return $list;
        // Check if a specified language exists
    }
    else {
        $file = $themes["theme"][$theme]["file"];
        return $file;
    }
}
?>

<?php
/* logs.php, envmon Mark II - Environment Monitor
 * Web interface - Logs
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
function insert_file($file) {
    if (file_exists($file)) {
        include($file);
    }
    else {
        print("File not found.");
    }
}

require($directory["html"] . "logs_sensor.html");
?>

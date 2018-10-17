<?php
/* stopwatch.php, envmon Mark I - Environment Monitor
 * Web interface - Stopwatch library
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */

/* stopwatch
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
function stopwatch($TimeFormat = 'ms') {
    global $StartTime;
    $now = microtime (true);
    switch ($TimeFormat) {
        // seconds
        case 's':
            $now = $now;
            $factor = 1;
            $round = 6;
            break;
        // milliseconds
        case 'ms':
            $factor = 1000;
            $round = 3;
            break;
        // microseconds
        case 'us':
            $factor = 1000000;
            $round = 1;
            break;
        default:
            return true;
    }
    if ($StartTime > 0) {
        $DeltaTime = round($now * $factor - $StartTime * $factor, $round);
        $StartTime = $now;
        return $DeltaTime;
    }
    else {
        $StartTime = $now;
        return 0;
    }
}
?>

<?php
/* menu.php, envmon Mark I - Environment Monitor
 * Web interface - Menu library
 * 
 * Author: Márcio Pessoa <marcio.pessoa@gmail.com>
 * Contributors: none
 */

show_menu($menu_items, $current["content"]);

/* show_menu
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
function show_menu($items, $active) {
    array_push($items, null);
    print("<div id='cssmenu'>\n<ul>\n");
    foreach($items as &$item) {
        // item
        if (is_string($last_item)) {
            $main = $last_item;
            // is active
            if ($last_item == $active) {
                $css_class = "active";
            }
            else {
                $css_class = "";
            }
            // has sub
            if (is_array($item)) {
                if (strlen($css_class) > 1) {
                    $css_class .= " ";
                }
                $css_class .= "has-sub";
            }
            print("  <li class='$css_class'><a href=\"?content=" . 
            $last_item . "\"><span>" . msg($last_item) . "</span></a>");
            if (!is_array($item)) {
                print("</li>\n");
            }
        }
        // sub-item
        if (is_array($item)) {
            print("\n    <ul>\n");
            foreach($item as &$sub_item) {
                print("      <li class='last'><a href=\"?content=" . 
                      $main . "&sub=" . $sub_item .
                      "\"><span>" . msg($sub_item) . "</span></a></li>\n");
            }
            print("    </ul>\n  ");
        print("</li>\n");
        }
        $last_item = $item;
    }
    print("</ul>\n</div>\n");
}
?>

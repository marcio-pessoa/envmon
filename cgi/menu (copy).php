<?php
/* menu.php, envmon Mark II - Environment Monitor, Web interface menu file
 * 
 * Author: Márcio Pessoa <marcio@pessoa.eti.br>
 * Contributors: none
 */

// Menu
$menu_items = array("View",
                    "Logs",
                    "Setup",
                    array("System",
                          "Thresholds",
                          "Timers",
                          "Notifications",
                          "Cloud",
                          "Configuration"),
                    "Help",
                    "About");

show_menu($menu_items, $current["content"]);

//
function show_menu($items, $active) {
    array_push($items, null);
    print("<div id='cssmenu'>\n<ul>\n");
    foreach($items as &$item) {
        // item
        if (is_string($last_item)) {
            $main = $last_item;
            // is active
            if (strtolower($last_item) == $active) {
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
                   strtolower($last_item) .
                   "\"><span>$last_item</span></a>");
            if (!is_array($item)) {
                print("</li>\n");
            }
        }
        // sub-item
        if (is_array($item)) {
            print("\n    <ul>\n");
            foreach($item as &$sub_item) {
                print("      <li class='last'><a href=\"?content=" . 
                      strtolower($main) . "&sub=" . 
                      strtolower($sub_item) .
                      "\"><span>$sub_item</span></a></li>\n");
            }
            print("    </ul>\n  ");
        print("</li>\n");
        }
        $last_item = $item;
    }
    print("</ul>\n</div>\n");
}
?>

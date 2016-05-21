<?php
/* language.php, envmon Mark II - Environment Monitor
 * Web interface - Language library
 * 
 * Author: Márcio Pessoa <marcio.pessoa@sciemon.com>
 * Contributors: none
 */

/* msg
 * 
 * Description
 *   Displays or returns a message.
 * 
 *   msg("message1")
 * 
 * Parameters
 *   element: The element to que displayed/returned.
 *   print: A boolean used to define display or return methods:
 *          false: returns string
 *          true: displays string (echo)
 * 
 * Returns
 *   void
 */
// function msg($element, $print=true) {
function msg($element) {
    global $language_defined;
    global $language_default;
    // Display message on current language
    if (isset($language_defined[$element])) {
        return $language_defined[$element];
    }
    // If element is empty
    if ($element == "") {
        echo "Argh... Nothing to say.";
        return true;
    }
    // If message is not present on current language pack, display message
    // using default language (English)
    if (isset($language_default[$element])) {
        return $language_default[$element];
    }
    // If message is not present even in the default language, displey error
    else {
        echo "Oops... Message \"" . $element . "\" was not defined.";
        return true;
    }
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
function language_load($language) {
    global $config;
    global $directory;
    // Check if language is configured and get get language file name
    $file = language_list($language);
    if ($file == false) {
        print("Aww... Language not configured.");
        return true;
    }
    // Load language pack
    $path = $directory["strings"] . $file;
    if (file_exists($path)) {
        $file = fopen($path, "r");
        $json = fread($file, filesize($path));
        fclose($file);
        $elements = json_decode($json, true)["elements"];
        return($elements);
    }
    else {
        print("Blah... Language pack file not found: " . $path);
        return true;
    }
}

/* 
 * 
 * Description
 *   Return current language code.
 * 
 *   ()
 * 
 * Parameters
 *   none
 * 
 * Returns
 *   void
 */
function language_code($language) {
    global $config;
    if (file_exists($config["languages"])) {
        $file = fopen($config["languages"], "r");
        $json = fread($file, filesize($config["languages"]));
        fclose($file);
        $lang = json_decode($json, true);
    }
    else {
        print("Aarrghh... Language list file not found.");
    }
    // 
    $code = $lang["language"][$language]["code"];
    return $code;
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
function language_list($language = "") {
    global $config;
    if (file_exists($config["languages"])) {
        $file = fopen($config["languages"], "r");
        $json = fread($file, filesize($config["languages"]));
        fclose($file);
        $lang = json_decode($json, true);
    }
    else {
        print("Aarrghh... Language list file not found.");
    }
    // Look for all available languages
    if ($language == "") {
        $list = array();
        while ($language = current($lang["language"])) {
            $key = key($lang["language"]);
            if ($lang["language"][$key]["enable"] == 1) {
                array_push($list, key($lang["language"]));
            }
            next($lang["language"]);
        }
        return $list;
    }
    // Check if a specified language exists
    else {
        $file = $lang["language"][$language]["file"];
        $code = $lang["language"][$language]["code"];
        return $file;
    }
}
?>

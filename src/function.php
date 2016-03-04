<?php
/**
 * format variable in the internet brower
 *
 * @return void
 **/
function bPrint($var) {
    $vars = func_get_args();
    $counts = 0;
    foreach ($vars as $var) {
        echo 'argument: ', ++$counts, '<br/>';
        if (is_string($var) || is_numeric($var)) {
            var_dump($var);
        }
        if (is_array($var) || is_object($var)) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        echo str_repeat('<br/>', 3);
    }
}

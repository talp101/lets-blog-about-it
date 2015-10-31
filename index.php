<?php
/**
 * Created by PhpStorm.
 * User: Tal
 * Date: 31/10/2015
 * Time: 20:08
 */
//    print_r($_REQUEST);
//    print_r($_SERVER['REQUEST_METHOD']);
//    print_r($_SERVER['REQUEST_URI']);
//    print_r($_GET);
//    print_r($_POST);

//    print_r(json_encode($_SERVER));

    $the_array = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

//    parse_str($ur.lArr['query'], $output);

//    print_r(json_encode($urlArr));
    print_r("Model is " . $the_array[2] );
    print_r(json_encode($the_array));
?>
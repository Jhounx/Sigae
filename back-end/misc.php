<?php
function proteger($string) {
    $string = strip_tags($string);
    $string = addslashes($string);
    return $string;
}

<?php
    function randomString($chars, $length) {
        $tamaho = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $tamaho - 1)];
        }
        return $randomString;
    }
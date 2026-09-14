<?php
    
    if(!function_exists('str_contains')){

        function str_contains($haystack, $needle) {
            return $needle !== '' && strpos($haystack, $needle) !== false;
        }
    }
?>
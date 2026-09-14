<?php
final class Functions
{
    
}







function requireAllFilesInFolder($folderPath, $extension = 'php') {
    // Check if the folder exists
    if (!is_dir($folderPath)) {
        throw new Exception("The folder '$folderPath' does not exist.");
    }

    // Get all files with the specified extension
    $files = glob($folderPath . "/*.$extension");

    // Require each file
    foreach ($files as $file) {
        // var_dump($file);
        require_once $file;
    }
}


if (!function_exists('array_key_last')) {
    function array_key_last(array $array) {
        if (empty($array)) {
            return null;
        }
        end($array);
        return key($array);
    }
}
    
  
function generateUuid() {
return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
    mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
);
}
?>

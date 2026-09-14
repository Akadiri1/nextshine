<?php

class FileHandler {

    // Method to upload a file
    public static function upload($file, $destinationDir) {
        $response = [];
        try {
            // Check if the file is uploaded successfully
            if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
                // Get file info
                $fileName = basename($file['name']);
                $fileTmpPath = $file['tmp_name'];
                $destinationPath = rtrim($destinationDir, '/') . '/' . $fileName;

                // Ensure the directory exists
                if (!file_exists($destinationDir)) {
                    mkdir($destinationDir, 0777, true);
                }

                // Move the uploaded file to the destination directory
                if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                    $response['status'] = 'success';
                    $response['message'] = 'File uploaded successfully.';
                    $response['destination'] = $destinationPath;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error uploading file.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error with file upload.';
            }
        } catch (Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = 'Error processing file upload.';
        }

        return $response;
    }

    // Method to download a file
    public static function download($filePath) {
        $response = [];
        try {
            // Check if the file exists
            if (file_exists($filePath)) {
                // Set headers to prompt file download
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
                header('Content-Length: ' . filesize($filePath));
                flush(); // Flush system output buffer
                readfile($filePath);
                exit;
            } else {
                $response['status'] = 'error';
                $response['message'] = 'File not found.';
            }
        } catch (Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = 'Error downloading file.';
        }

        return $response;
    }

    // Method to delete a file
    public static function delete($filePath) {
        $response = [];
        try {
            // Check if the file exists
            if (file_exists($filePath)) {
                if (unlink($filePath)) {
                    $response['status'] = 'success';
                    $response['message'] = 'File deleted successfully.';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error deleting file.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'File not found.';
            }
        } catch (Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = 'Error deleting file.';
        }

        return $response;
    }

    // Method to get file content (for reading text files)
    public static function getFileContent($filePath) {
        $response = [];
        try {
            // Check if the file exists
            if (file_exists($filePath)) {
                $response['status'] = 'success';
                $response['message'] = 'File content retrieved successfully.';
                $response['content'] = file_get_contents($filePath); // Read file content
            } else {
                $response['status'] = 'error';
                $response['message'] = 'File not found.';
            }
        } catch (Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = 'Error reading file content.';
        }

        return $response;
    }

    // Method to check if file exists
    // public static function fileExists($filePath) {
    //     $response = [];
    //     try {
    //         if (file_exists($filePath)) {
    //             $response['status'] = 'success';
    //             $response['message'] = 'File exists.';
    //         } else {
    //             $response['status'] = 'error';
    //             $response['message'] = 'File does not exist.';
    //         }
    //     } catch (Throwable $th) {
    //         $response['status'] = 'error';
    //         $response['message'] = 'Error checking file existence.';
    //     }

    //     return $response;
    // }

    public static function fileExists($filePath) {
        return file_exists($filePath);
    }

    // Method to move a file from one location to another
    public static function move($sourcePath, $destinationDir) {
        $response = [];
        try {
            // Check if the source file exists
            if (file_exists($sourcePath)) {
                $fileName = basename($sourcePath);
                $destinationPath = rtrim($destinationDir, '/') . '/' . $fileName;

                // Ensure the destination directory exists
                if (!file_exists($destinationDir)) {
                    mkdir($destinationDir, 0777, true);
                }

                // Move the file
                if (rename($sourcePath, $destinationPath)) {
                    $response['status'] = 'success';
                    $response['message'] = 'File moved successfully.';
                    $response['source'] = $sourcePath;
                    $response['destination'] = $destinationPath;
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Error moving file.';
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Source file not found.';
            }
        } catch (Throwable $th) {
            $response['status'] = 'error';
            $response['message'] = 'Error moving file.';
        }

        return $response;
    }
}

?>

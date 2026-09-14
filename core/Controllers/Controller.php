<?php
    
    class Controller 
    {
        
        public function createJWT($payloadData, $secret = APP_SECRET_KEY) {
            // Create the header as a JSON string and encode it to Base64Url
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        
            // Create the payload as a JSON string and encode it to Base64Url
            $payload = json_encode($payloadData);
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
            // Create the signature hash
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
            // Concatenate the header, payload, and signature to form the JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        
            return $jwt;
        }
        
        
        public  function verifyJWT($jwt, $secret = APP_SECRET_KEY) {
            // Split the JWT into its three parts
            $tokenParts = explode('.', $jwt);
            if (count($tokenParts) !== 3) {
                return false; // Invalid token format
            }
        
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];
        
        
            // Verify the signature
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
            $signatureExpected = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
            $base64UrlSignatureExpected = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signatureExpected));
        
            // var_dump($tokenParts, base64_encode($signatureExpected));
        
            if ($base64UrlSignatureExpected !== $signatureProvided) {
                return false; // Invalid signature
            }
        
            // Decode the payload and check its content
            $payloadData = json_decode($payload, true);
        
            // Optional: Check if the token has expired (if 'exp' claim is set)
            if (isset($payloadData['exp']) && $payloadData['exp'] < time()) {
                return false; // Token has expired
            }
        
            // Token is valid and can be trusted
            return $payloadData;
        }
        

    public function uploadFile($file, $uploadDir =  null, $filePrefix = null, $fileName = null,  $maxFileSize = 5 * 1024 * 1024, $allowedExtensions = "*")
    {
        if ($uploadDir === null) {
            $uploadDir = 'uploads/';
        }
        // Check if the input is a base64 string
        if (strpos($file, ';base64,') !== false) {
            // Handle Base64 File Upload
            $base64Parts = explode(';base64,', $file);
            if (count($base64Parts) !== 2) {
                return ['status' => 'error', 'message' => 'Invalid base64 format'];
            }

            // Decode the base64 file
            $decodedFile = base64_decode($base64Parts[1]);
            if ($decodedFile === false) {
                return ['status' => 'error', 'message' => 'Invalid base64 data'];
            }

            // Extract MIME type and determine the extension
            $mimeType = explode(':', $base64Parts[0])[1];
            $fileExtension = $this->getFileExtensionFromMimeType($mimeType);

            if ($allowedExtensions !== "*" && !in_array($fileExtension, $allowedExtensions)) {
                return ['status' => 'error', 'message' => 'Invalid file type'];
            }

            // Check file size
            if (strlen($decodedFile) > $maxFileSize) {
                return ['status' => 'error', 'message' => 'File size exceeds limit'];
            }

            // Generate a unique filename
            
            if (!$fileName) {
                $fileName = uniqid();
                # code...
            }
            if ($filePrefix) {
                $fileName = $filePrefix . $fileName;
            }
            // Generate a unique filename
            $uniqueFileName = $fileName. '.' . $fileExtension;
            $uploadPath = $uploadDir . $uniqueFileName;

            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    return ['status' => 'error', 'message' => 'Failed to create upload directory'];
                }
            }

            // Save the file
            if (file_put_contents($uploadPath, $decodedFile)) {
                return ['status' => 'success', 'message' => 'File uploaded successfully', 'file_path' => $uploadPath];
            } else {
                return ['status' => 'error', 'message' => 'Failed to save file'];
            }

        } elseif (isset($file['tmp_name'])) {
            // Handle Standard File Upload
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if ($allowedExtensions !== "*" && !in_array($fileExtension, $allowedExtensions)) {
                return ['status' => 'error', 'message' => 'Invalid file type'];
            }

            if ($file['size'] > $maxFileSize) {
                return ['status' => 'error', 'message' => 'File size exceeds limit'];
            }

            if (!$fileName) {
                $fileName = uniqid();
                # code...
            }
            if ($filePrefix) {
                $fileName = $filePrefix . $fileName;
            }
            // Generate a unique filename
            $uniqueFileName = $fileName. '.' . $fileExtension;
            $uploadPath = $uploadDir . $uniqueFileName;

            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    return ['status' => 'error', 'message' => 'Failed to create upload directory'];
                }
            }

            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return ['status' => 'success', 'message' => 'File uploaded successfully', 'file_path' => $uploadPath];
            } else {
                return ['status' => 'error', 'message' => 'Failed to save file'];
            }
        }

        return ['status' => 'error', 'message' => 'Invalid input'];
    }

    public function deleteFile($filePath)
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }


    private function getFileExtensionFromMimeType($mimeType)
    {
        $mimeMap = [
            // Images
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'image/webp' => 'webp',
            'image/tiff' => 'tiff',
            'image/svg+xml' => 'svg',

            // Documents
            'application/pdf' => 'pdf',
            'text/plain' => 'txt',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',

            // Archives
            'application/zip' => 'zip',
            'application/x-rar-compressed' => 'rar',
            'application/x-tar' => 'tar',
            'application/gzip' => 'gz',
            'application/x-7z-compressed' => '7z',

            // Audio
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
            'audio/ogg' => 'ogg',
            'audio/aac' => 'aac',
            'audio/flac' => 'flac',
            'audio/x-ms-wma' => 'wma',

            // Video
            'video/mp4' => 'mp4',
            'video/x-msvideo' => 'avi',
            'video/x-ms-wmv' => 'wmv',
            'video/mpeg' => 'mpeg',
            'video/quicktime' => 'mov',
            'video/x-flv' => 'flv',
            'video/webm' => 'webm',

            // Application files
            'application/javascript' => 'js',
            'application/json' => 'json',
            'application/xml' => 'xml',
            'text/html' => 'html',
            'text/css' => 'css',
            'application/x-httpd-php' => 'php',
            'application/x-sh' => 'sh',
            'application/x-python-code' => 'py',
            'application/x-java-archive' => 'jar',
        ];

        return $mimeMap[$mimeType] ?? null;
    }

    
    public function generateUuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    }

?>
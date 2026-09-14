<?php
class FileUpload
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function getName()
    {
        return $this->file['name'] ?? null;
    }

    public function getType()
    {
        return $this->file['type'] ?? null;
    }

    public function getTmpName()
    {
        return $this->file['tmp_name'] ?? null;
    }

    public function getError()
    {
        return $this->file['error'] ?? null;
    }

    public function getSize()
    {
        return $this->file['size'] ?? null;
    }

    public function hasError()
    {
        return $this->file['error'] !== UPLOAD_ERR_OK;
    }

    //Get Extension
    public function getExtension()
    {
        return pathinfo($this->getName(), PATHINFO_EXTENSION);
    }

    public function save($destination = null)
    {
        
        if ($destination === null) {
            $destination = "";
        }
        $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getName();

        if ($this->hasError()) {
            throw new Exception('File upload error: ' . $this->getError());
        }

        if (!move_uploaded_file($this->getTmpName(), $destination)) {
            throw new Exception('Failed to move uploaded file.');
        }

        return true;
    }

    public function saveAs($newName, $directory = null)
    {
        if(empty($newName)){
            throw new Exception('New file name cannot be empty');
            return false;
        }
        
        if ($directory === null) {
            $directory = "";
        }
        if ($this->hasError()) {
            throw new Exception('File upload error: ' . $this->getError());
        }

        // Ensure the directory has a trailing slash
        $directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // Build the full destination path
        $destination = $directory . $newName;

        if (!move_uploaded_file($this->getTmpName(), $destination)) {
            throw new Exception('Failed to move uploaded file to: ' . $destination);
        }

        return true;
    }
}

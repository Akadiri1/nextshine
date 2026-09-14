<?php
    
    class Logger
{
    private $logFile;
    private $logDir;


    public function __construct($logDir = null )
    {
        $this->logDir = $logDir;

        if ($this->logDir === null) {
            $this->logDir = "/tmp/{project_folder_name}/logs/"; // Default log file path}"

            $projectFolderName = basename(dirname(dirname(__DIR__)));
            $this->logFile = str_replace('{project_folder_name}', $projectFolderName, $this->logDir) . 'application.log';
        }

        $this->logDir = rtrim($this->logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->logFile = $this->logDir . 'application.log';
        $this->initializeLogFile();
        return $this;
    }


    
    private function initializeLogFile()
    {

         // Ensure the directory for the log file exists
         // If the directory is nested, create it recursively
         if (!is_dir($this->logDir)) {
             mkdir($this->logDir, 0755, true);
         }

         // Ensure the log file exists & if not file create a file name log

         if (!file_exists($this->logFile)) {            
             touch($this->logFile);
             chmod($this->logFile, 0644);
         }

         return $this;
     }

    public function log($message, $file_name = null)
    {
        // If a specific file name is provided, use it; otherwise, use the default log file
        if ($file_name !== null) {
            $this->logFile = rtrim($this->logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file_name;
        }

        // Initialize the log file if it hasn't been done yet
        $this->initializeLogFile();

        // Log the message
        return $this->writeLog($message);
    }
    
    private function writeLog($message)
    {
        // Ensure the log file exists
        if (!file_exists($this->logFile)) {
            touch($this->logFile);
            chmod($this->logFile, 0644);
        }

        // Write the log entry
        $logEntry = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);

        return $this;
    }
}

?>
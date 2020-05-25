<?php

class Log {

    /**
     * @var global $config global variable in config.php file
     */
    private $global_config;

    function __construct() {
        global $config;
        $this->global_config = $config;
    }

    /**
     * log an record in defined file in valid path and file.
     * your path should be exist if your entry path is not default log file in default directory.
     * is better define a path in app folder or a protected folder for increase security of your log file.
     * all system messages, errors and other actions logged in default log file path that defined in constant.php file in config folder.
     * Type of Log define that this log is which kind of log. for example this log is a Message Log, or Warning log or error log.
     * Type of Log Message, Error, Warning, or Custom Type of Log, For example Login Error, Internal Error etc.
     * @param type $text the description of record
     * @param type $path the path of file you want record logged  in it
     */
    function write($text, $type, $path = '') {
        $p = $path ? $path : $this->get_log_path();
        if (file_exists($p)) {
            $file = fopen($p, 'a');
            $record = "\n" . date($this->global_config['log_date_format']) . " |_ $type _| $text";
            fwrite($file, $record);
            fclose($file);
        } else {
            if (!file_exists(DEFAULT_LOG_PATH)) {
                mkdir(DEFAULT_LOG_PATH, 0777, true);
            }
            $file = fopen(DEFAULT_LOG_PATH_FILE, 'a');
            $date = date($this->global_config['log_date_format']);
            $record = "\n" . $date . " |_ Error _| Your defined path ({$p}) is invalid, your message automatically logged in default log file.";
            fwrite($file, $record);
            $record = "\n" . $date . " |_ $type _| " . $text;
            fwrite($file, $record);
            fclose($file);
        }
    }

    private function get_log_path() {
        if (!empty($this->global_config['log_path'])) {
            return $this->global_config['log_path'];
        }

        return DEFAULT_LOG_PATH_FILE;
    }

}

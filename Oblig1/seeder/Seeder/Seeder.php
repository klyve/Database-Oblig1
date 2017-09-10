<?php
require_once('Table.php');
require_once('TableSeeder.php');



class SeederException extends Exception {
    
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

class Seeder {

    /*
        Database connection handle
    */
    private static $databaseConnection;

    /*
    * Configure the seeder
    */
    public static function configure($host, $username, $password, $database) {
        self::$databaseConnection = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
    }
    private static function query($query) {
        $handle = self::$databaseConnection->prepare($query);
        $handle->execute();
    }
    public static function table($callback) {
        if(!is_callable($callback)) {
            throw new SeederException('Callback is not a valid function');
        } else {
            $table = call_user_func_array($callback, [new Table()]);
            self::query($table->build());
        }
    }

    public static function seed($tableName, $callback) {
        if(!is_callable($callback)) {
            throw new SeederException('Callback is not a valid function');
        } else {
            $table = call_user_func_array($callback, [new TableSeeder($tableName)]);
            self::query($table->build());
        }
    }

}






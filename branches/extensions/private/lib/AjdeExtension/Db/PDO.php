<?php
/**
 * @source http://www.coderholic.com/php-database-query-logging-with-pdo/
 * Modified for use with Ajde_Document_Format_Processor_Html_Debugger
 */
 
/** 
* Extends PDO and logs all queries that are executed and how long 
* they take, including queries issued via prepared statements 
*/  
class AjdeExtension_Db_PDO extends PDO  
{  
    public static $log = array();  
  
    public function __construct($dsn, $username = null, $password = null, $options = array()) {
    	$options = $options + array(
    		PDO::ATTR_STATEMENT_CLASS => array('AjdeExtension_Db_PDOStatement', array($this))
		);
        parent::__construct($dsn, $username, $password, $options);  
    }  
  
    public function query($query) {  
        $start = microtime(true);  
        $result = parent::query($query);  
        $time = microtime(true) - $start;  
        self::$log[] = array(
        	'query' => $query,  
            'time' => round($time * 1000, 0)
		);  
        return $result;  
    }  
  
    public static function getLog() {  
        return self::$log;  
    }  
}  
  

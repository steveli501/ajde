<?php
/**
 * @source http://www.coderholic.com/php-database-query-logging-with-pdo/
 * Modified for use with Ajde_Document_Format_Processor_Html_Debugger
 */
/** 
* PDOStatement decorator that logs when a PDOStatement is 
* executed, and the time it took to run 
* @see LoggedPDO 
*/  
class AjdeExtension_Db_PDOStatement extends PDOStatement {  
    
	/**
	 * @see http://www.php.net/manual/en/book.pdo.php#73568
	 */
	public $dbh;
	
    protected function __construct($dbh) {
        $this->dbh = $dbh;
    }
	
    /** 
    * When execute is called record the time it takes and 
    * then log the query 
    * @return PDO result set 
    */  
    public function execute($input_parameters = array()) {
        $start = microtime(true);  
        $result = parent::execute($input_parameters);  
        $time = microtime(true) - $start;  
        AjdeExtension_Db_PDO::$log[] = array(
        	'query' => '[PS] ' . $this->queryString,  
            'time' => round($time * 1000, 0)
		);  
        return $result;  
    }
}  
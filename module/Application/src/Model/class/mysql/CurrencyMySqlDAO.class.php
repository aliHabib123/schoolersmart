<?php
/**
 * Class that operate on table 'currency'. Database Mysql.
 */
class CurrencyMySqlDAO implements CurrencyDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return CurrencyMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM currency WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM currency';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM currency ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param currency primary key
 	 */
	public function delete($id){
		$sql = 'DELETE FROM currency WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param CurrencyMySql currency
 	 */
	public function insert($currency){
		$sql = 'INSERT INTO currency (currency_name, currency_symbol, conversion_rate, display_order, active) VALUES (?, ?, ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($currency->currencyName);
		$sqlQuery->setNumber($currency->currencySymbol);
		$sqlQuery->setNumber($currency->conversionRate);
		$sqlQuery->setNumber($currency->displayOrder);
		$sqlQuery->setNumber($currency->active);

		$id = $this->executeInsert($sqlQuery);	
		$currency->id = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param CurrencyMySql currency
 	 */
	public function update($currency){
		$sql = 'UPDATE currency SET currency_name = ?, currency_symbol = ?, conversion_rate = ?, display_order = ?, active = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->set($currency->currencyName);
		$sqlQuery->setNumber($currency->currencySymbol);
		$sqlQuery->setNumber($currency->conversionRate);
		$sqlQuery->setNumber($currency->displayOrder);
		$sqlQuery->setNumber($currency->active);

		$sqlQuery->setNumber($currency->id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM currency';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	public function queryByCurrencyName($value){
		$sql = 'SELECT * FROM currency WHERE currency_name = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	public function queryByCurrencySymbol($value){
		$sql = 'SELECT * FROM currency WHERE currency_symbol = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByConversionRate($value){
		$sql = 'SELECT * FROM currency WHERE conversion_rate = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByDisplayOrder($value){
		$sql = 'SELECT * FROM currency WHERE display_order = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByActive($value){
		$sql = 'SELECT * FROM currency WHERE active = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}


	public function deleteByCurrencyName($value){
		$sql = 'DELETE FROM currency WHERE currency_name = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByCurrencySymbol($value){
		$sql = 'DELETE FROM currency WHERE currency_symbol = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByConversionRate($value){
		$sql = 'DELETE FROM currency WHERE conversion_rate = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByDisplayOrder($value){
		$sql = 'DELETE FROM currency WHERE display_order = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByActive($value){
		$sql = 'DELETE FROM currency WHERE active = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}


	
	/**
	 * Read row
	 *
	 * @return CurrencyMySql 
	 */
	protected function readRow($row){
		$currency = new Currency();
		
		$currency->id = $row['id'];
		$currency->currencyName = $row['currency_name'];
		$currency->currencySymbol = $row['currency_symbol'];
		$currency->conversionRate = $row['conversion_rate'];
		$currency->displayOrder = $row['display_order'];
		$currency->active = $row['active'];

		return $currency;
	}
	
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
	}
	
	/**
	 * Get row
	 *
	 * @return CurrencyMySql 
	 */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($sqlQuery);
		if(count($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}
	
	/**
	 * Execute sql query
	 */
	protected function execute($sqlQuery){
		return QueryExecutor::execute($sqlQuery);
	}
	
		
	/**
	 * Execute sql query
	 */
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($sqlQuery);
	}

	/**
	 * Query for one row and one column
	 */
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($sqlQuery);
	}

	/**
	 * Insert row to table
	 */
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($sqlQuery);
	}
}
?>
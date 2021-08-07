<?php
/**
 * Class that operate on table 'currency_country'. Database Mysql.
 */
class CurrencyCountryMySqlDAO implements CurrencyCountryDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return CurrencyCountryMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM currency_country WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM currency_country';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM currency_country ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param currencyCountry primary key
 	 */
	public function delete($id){
		$sql = 'DELETE FROM currency_country WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param CurrencyCountryMySql currencyCountry
 	 */
	public function insert($currencyCountry){
		$sql = 'INSERT INTO currency_country (currency_id, country_id, is_default) VALUES (?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($currencyCountry->currencyId);
		$sqlQuery->setNumber($currencyCountry->countryId);
		$sqlQuery->setNumber($currencyCountry->isDefault);

		$id = $this->executeInsert($sqlQuery);	
		$currencyCountry->id = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param CurrencyCountryMySql currencyCountry
 	 */
	public function update($currencyCountry){
		$sql = 'UPDATE currency_country SET currency_id = ?, country_id = ?, is_default = ? WHERE id = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($currencyCountry->currencyId);
		$sqlQuery->setNumber($currencyCountry->countryId);
		$sqlQuery->setNumber($currencyCountry->isDefault);

		$sqlQuery->setNumber($currencyCountry->id);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM currency_country';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	public function queryByCurrencyId($value){
		$sql = 'SELECT * FROM currency_country WHERE currency_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByCountryId($value){
		$sql = 'SELECT * FROM currency_country WHERE country_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByIsDefault($value){
		$sql = 'SELECT * FROM currency_country WHERE is_default = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}


	public function deleteByCurrencyId($value){
		$sql = 'DELETE FROM currency_country WHERE currency_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByCountryId($value){
		$sql = 'DELETE FROM currency_country WHERE country_id = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByIsDefault($value){
		$sql = 'DELETE FROM currency_country WHERE is_default = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}


	
	/**
	 * Read row
	 *
	 * @return CurrencyCountryMySql 
	 */
	protected function readRow($row){
		$currencyCountry = new CurrencyCountry();
		
		$currencyCountry->id = $row['id'];
		$currencyCountry->currencyId = $row['currency_id'];
		$currencyCountry->countryId = $row['country_id'];
		$currencyCountry->isDefault = $row['is_default'];

		return $currencyCountry;
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
	 * @return CurrencyCountryMySql 
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
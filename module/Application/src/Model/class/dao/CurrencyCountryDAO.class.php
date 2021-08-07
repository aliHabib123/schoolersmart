<?php
/**
 * Intreface DAO
 */
interface CurrencyCountryDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return CurrencyCountry 
	 */
	public function load($id);

	/**
	 * Get all records from table
	 */
	public function queryAll();
	
	/**
	 * Get all records from table ordered by field
	 * @Param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Delete record from table
 	 * @param currencyCountry primary key
 	 */
	public function delete($id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param CurrencyCountry currencyCountry
 	 */
	public function insert($currencyCountry);
	
	/**
 	 * Update record in table
 	 *
 	 * @param CurrencyCountry currencyCountry
 	 */
	public function update($currencyCountry);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByCurrencyId($value);

	public function queryByCountryId($value);

	public function queryByIsDefault($value);


	public function deleteByCurrencyId($value);

	public function deleteByCountryId($value);

	public function deleteByIsDefault($value);


}
?>
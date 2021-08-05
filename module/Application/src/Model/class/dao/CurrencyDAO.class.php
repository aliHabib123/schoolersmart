<?php
/**
 * Intreface DAO
 */
interface CurrencyDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return Currency 
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
 	 * @param currency primary key
 	 */
	public function delete($id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param Currency currency
 	 */
	public function insert($currency);
	
	/**
 	 * Update record in table
 	 *
 	 * @param Currency currency
 	 */
	public function update($currency);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByCurrencyName($value);

	public function queryByCurrencySymbol($value);

	public function queryByConversionRate($value);

	public function queryByDisplayOrder($value);

	public function queryByActive($value);


	public function deleteByCurrencyName($value);

	public function deleteByCurrencySymbol($value);

	public function deleteByConversionRate($value);

	public function deleteByDisplayOrder($value);

	public function deleteByActive($value);


}
?>
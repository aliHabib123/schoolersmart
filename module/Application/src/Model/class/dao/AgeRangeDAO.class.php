<?php
/**
 * Intreface DAO
 */
interface AgeRangeDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return AgeRange 
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
 	 * @param ageRange primary key
 	 */
	public function delete($id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param AgeRange ageRange
 	 */
	public function insert($ageRange);
	
	/**
 	 * Update record in table
 	 *
 	 * @param AgeRange ageRange
 	 */
	public function update($ageRange);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByName($value);

	public function queryByDisplayOrder($value);


	public function deleteByName($value);

	public function deleteByDisplayOrder($value);


}
?>
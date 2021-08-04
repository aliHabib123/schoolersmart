<?php
/**
 * Intreface DAO
 */
interface BannerLocationDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return BannerLocation 
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
 	 * @param bannerLocation primary key
 	 */
	public function delete($id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param BannerLocation bannerLocation
 	 */
	public function insert($bannerLocation);
	
	/**
 	 * Update record in table
 	 *
 	 * @param BannerLocation bannerLocation
 	 */
	public function update($bannerLocation);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByLocationName($value);

	public function queryByActive($value);

	public function queryByDisplayOrder($value);


	public function deleteByLocationName($value);

	public function deleteByActive($value);

	public function deleteByDisplayOrder($value);


}
?>
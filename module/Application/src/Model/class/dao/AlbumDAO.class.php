<?php
/**
 * Intreface DAO
 */
interface AlbumDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return Album 
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
 	 * @param album primary key
 	 */
	public function delete($id);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param Album album
 	 */
	public function insert($album);
	
	/**
 	 * Update record in table
 	 *
 	 * @param Album album
 	 */
	public function update($album);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByTitle($value);

	public function queryByImage($value);

	public function queryByAlbumType($value);

	public function queryByDisplayOrder($value);

	public function queryByActive($value);


	public function deleteByTitle($value);

	public function deleteByImage($value);

	public function deleteByAlbumType($value);

	public function deleteByDisplayOrder($value);

	public function deleteByActive($value);


}
?>
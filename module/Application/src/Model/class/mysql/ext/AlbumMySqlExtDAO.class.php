<?php
/**
 * Class that operate on table 'album'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2021-04-23 23:35
 */
class AlbumMySqlExtDAO extends AlbumMySqlDAO{

    public function select($condition){
		$sql = "SELECT * FROM album WHERE $condition";
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
}
?>
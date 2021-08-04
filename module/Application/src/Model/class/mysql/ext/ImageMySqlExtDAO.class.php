<?php
/**
 * Class that operate on table 'image'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2021-04-23 23:35
 */
class ImageMySqlExtDAO extends ImageMySqlDAO{

    public function select($condition){
		$sql = "SELECT * FROM `image` WHERE $condition";
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
}
?>
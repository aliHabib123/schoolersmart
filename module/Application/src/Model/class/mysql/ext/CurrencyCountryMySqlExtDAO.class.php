<?php
/**
 * Class that operate on table 'currency_country'. Database Mysql.
 */
class CurrencyCountryMySqlExtDAO extends CurrencyCountryMySqlDAO{

    public function deleteByCurrencyIdAndCond($id, $cond = "1")
    {
        $sql = 'DELETE FROM currency_country WHERE currency_id = ? AND ' . $cond;
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->executeUpdate($sqlQuery);
    }
}
?>
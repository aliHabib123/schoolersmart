<?php
class Team_member
{
    public static function select($condition)
    {
        if ($condition == "") {
            $condition=1;
        }
        $query = "select * from team_member where $condition ";
        $result = mysqli_query($_SESSION["db_conn"], $query);
        $objects = array();
        while ($data = mysqli_fetch_object($result)) {
            array_push($objects, $data);
        }
    
        return $objects;
    }
    public static function selectById($id)
    {
        $query = "select * from team_member where team_member_id=$id ";
        $result = mysqli_query($_SESSION["db_conn"], $query);
        $data = mysqli_fetch_object($result);
        return $data;
    }
    public static function save($id, $team_member_firstname, $team_member_lastname, $team_member_title, $team_member_details, $team_member_image, $team_member_order, $company_id)
    {
        if ($id == "") {
            $query = "insert into team_member set `team_member_firstname` = '$team_member_firstname' , `team_member_lastname` = '$team_member_lastname' , `team_member_title` = '$team_member_title' , `team_member_details` = '$team_member_details' , `team_member_image` = '$team_member_image' , `team_member_order` = '$team_member_order' , `company_id` = '$company_id'  ";
        } else {
            $query = "update team_member set `team_member_firstname` = '$team_member_firstname' , `team_member_lastname` = '$team_member_lastname' , `team_member_title` = '$team_member_title' , `team_member_details` = '$team_member_details' , `team_member_image` = '$team_member_image' , `team_member_order` = '$team_member_order' , `company_id` = '$company_id'  where team_member_id = $id";
        }
        mysqli_query($_SESSION["db_conn"], $query);

        if (mysqli_affected_rows($_SESSION["db_conn"])>0) {
            return true;
        } else {
            return false;
        }
    }
    public static function delete($id)
    {
        $query = "delete from team_member where team_member_id=$id ";
        mysqli_query($_SESSION["db_conn"], $query);

        if (mysqli_affected_rows($_SESSION["db_conn"])>0) {
            return true;
        } else {
            return false;
        }
    }
    public static function updateCondition($id, $condition)
    {
        $query = "update team_member set $condition where team_member_id=$id ";
        mysqli_query($_SESSION["db_conn"], $query);

        if (mysqli_affected_rows($_SESSION["db_conn"])>0) {
            return true;
        } else {
            return false;
        }
    }
    public static function count($condition)
    {
        $query = "select count(*) as cc from team_member where $condition";
        $result = mysqli_query($_SESSION["db_conn"], $query);
        $data = mysqli_fetch_object($result);
        return $data->cc;
    }
}

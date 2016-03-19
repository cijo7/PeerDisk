<?php

 /*
 * Edit:getroot,database
 */

/**
 * Adds vistor details
 * @param type $db dbconn
 */
function common($db){
    $con=$db->start();
    if($db->status){
        $agent=$_SERVER['HTTP_USER_AGENT'];
        $ip=$_SERVER['REMOTE_ADDR'];
        $page=$_SERVER['REQUEST_URI'];
        $query="INSERT INTO `visitors`(`ip`, `time`, `page`, `agent`) VALUES ('$ip',now(),'$page','$agent')";
        mysqli_query($con, $query);
    }
}

/**
 * 
 * @param type $len length of string hex
 * @return type random number
 */
function generateName($len=10)
{
    $cstrong=true;
    $bytes = openssl_random_pseudo_bytes($len, $cstrong);
    $hex   = bin2hex($bytes);
    return $hex;
}
function getRoot()
{
    return   "http://localhost"; 
}

function getDB($dbname)
{
    if($dbname=="site")
    {
        $i=2;
    }
    else if($dbname=="user")
    {
        $dbname="user";
        $i=1;
    }
    return mysqli_connect("localhost","root","",$dbname,3306);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * 
 * @param string $error The error code to be passed
 * @param string $page  The page error is on
 * @param int $loc the location of error
 * @param database $db the database to connect to('site').Do not give any other 
 * @return null        The token returned
 */
function setTracking($error,$page,$loc=1,$db=NULL)
{
    $ip=$_SERVER["REMOTE_ADDR"];
    $con= $db->start();
    $token=  generateName();
    $status=FALSE;
    if (!mysqli_connect_errno())
    {
        $query="INSERT INTO `tracking`(`ip`, `time`, `token`, `useOf`, `error` ,`location`,`page`) VALUES ('$ip',now(),'$token',0,'$error',$loc,'$page')";
        if(mysqli_query($con, $query))
        {
            $status=TRUE;
        }
        if(mysqli_errno($con)==1062)
        {
            $status=FALSE;
            $query="UPDATE tracking SET token='$token',useOf=1,error='$error',location='$loc',page='$page' WHERE ip='$ip'";
            if(mysqli_query($con, $query))
            {
                $status=TRUE;
            }
        }
    }
    if(!$status)
    {
        $token=NULL;
    }
    
    return $token;
}

function removeTracking($t)
{
    $ip=$_SERVER["REMOTE_ADDR"];
    $con= getDB("site");
    $status=FALSE;
    if (!mysqli_connect_errno())
    {
        $query="DELETE FROM tracking WHERE token='$t' AND ip='$ip'";
        if(mysqli_query($con, $query))
        {
            $status=TRUE;
        }
    }
    return $status;
}

function error($error,$src,$line)
{
    
}

class db
{
    /**
     *database name
     * @var string
     */
    var $database;
    var $link;
    /**
     * data base connection status
     * @var boolean
     */
    var $status;
    /**
     * initialize connection to database
     * @param string $data the name of database to be connected
     */
    function db($table)
    {
        if($table=="site")
        {
            $i=2;
        }
        else if($table=="user")
        {
            $i=1;
            $table="user";
        }
        $this->database=$table;
        $this->link= mysqli_connect("localhost","root","",$this->database,3306);
        if(!mysqli_connect_errno())
        {
            $this->status=TRUE;
        }
        else
        {
            $this->status=FALSE;
        }
        
    }
    /**
     * return connection object
     * @return databaseConnection
     */
    
    function start()
    {
        return $this->link;
    }
    /**
     * close a mysql connection
     */
    function close()
    {
        mysqli_close($this->link);
    }
}
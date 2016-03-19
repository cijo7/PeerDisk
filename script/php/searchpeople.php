<?php

require 'universal.php';
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Type: text/html");
echo '<b>Your Search returned</b> <br/>';
$name=  filter_input(INPUT_POST, "we", FILTER_SANITIZE_STRING);
if(isset($name))
{
    $dbuser=new db("user");
    $con=$dbuser->start();
    $status=FALSE;
    if($dbuser->status)
    {
        $query="SELECT * FROM user_lkjfndjrcs WHERE firstName LIKE '$name%' OR lastName LIKE '$name%'";
        if($result=mysqli_query($con, $query))
        {
            while ($row = mysqli_fetch_array($result)) {
                $namea=$row['firstName']." ".$row['lastName'];
                $status=TRUE;
                display($namea);             
            }
            
        }
    if(!$status)
    {
        echo 'No user Found';
    }
    }
    $dbuser->close();
}

function display($name)
{
    echo '<span class="msgopt">'.$name.'</span><br/>';
}
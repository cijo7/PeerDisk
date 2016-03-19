<?php

require 'script/php/universal.php';
require 'script/php/header.php';

if(($action=filter_input(INPUT_GET, "action", FILTER_VALIDATE_INT)) && ($token=filter_input(INPUT_GET, "sand", FILTER_SANITIZE_STRING)) &&($mail=filter_input(INPUT_GET, "box", FILTER_SANITIZE_ENCODED))&&($src=filter_input(INPUT_GET, "src", FILTER_SANITIZE_STRING)) )
{
    
    $mail=  urldecode($mail);
    $reply=confirm($token);
    list($status,$key,$mail)=  explode(".", $reply,3);
    $track=  setTracking($key.'.'.$mail,"confirm.php",1,$datu=new db("site"));
    $datu->close();
    if($status)
    {
        createuserspace($mail, $dat=new db("user"));
        header("Location:".getRoot()."/setup.php?red=$track");
        $dat->close();
        exit();
    }
}
 else {
    printmsg($msg="Invalid Request");
     
 }
 function printmsg($msg)
 {
        echo '<!DOCTYPE html>
            <!--
            This Program is licensed by CS APPS
            Developed by Cijo Saju
            unauthorised Use Prohibited
            -->
            <html>
                <head>
                    <meta charset="UTF-8">
                    <link type="text/css" href="script/css/login.css" rel="stylesheet" >
                    <link type="text/css" href="script/css/register.css" rel="stylesheet" >
                    <link type="text/css" href="script/css/common.css" rel="stylesheet" >
                    <title>Confirm Registration</title>
                </head>
                <body class="bodyMain">';
        printHeader();
        echo        '<br/><br/><br/><div>
                        <span class="registerMsg">'.$msg.'</span>
                            <br/>
                        <form action="register.php" method="post" name="confirm" class="cform">
                            <label>Confirmation Code:</label>
                            <input type="text" class="ctext commonText" name="regsucess" value="" />
                            <input type="submit" value="Submit" id="confirmbutton" class="button"/>
                        </form>
                    </div>';
                    printFooter();
        echo    '</body>
            </html>';
          
   
}
    function confirm($code)
    {
        $cona=new db("user");
        $con=$cona->start();
        $status=FALSE;
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM bufferuser WHERE confirmToken='$code'";
            if($result= mysqli_query($con, $query))
            {
                while ($row = mysqli_fetch_array($result)) {
                    $fname=$row['firstName'];
                    $lname=$row['lastName'];
                    $email=$row['email'];
                    $key=$row['keyId'];
                    $userid=$row['userId'];
                    $day=$row['day'];
                    $month=$row['month'];
                    $year=$row['year'];
                    $groupid=$row['groupId'];
                    $sign=$row['sign'];
                    $password=$row['password'];
                    $status=TRUE;
                }
                if($status)
                {
                    $grp=$groupid;
                    $query="INSERT INTO `user_$grp`(`firstName`, `lastName`, `email`, `password`, `day`, `month`, `year`, `userId`, `groupId`, `sign`, `keyId`) VALUES ('$fname','$lname','$email','$password','$day','$month','$year','$userid','$groupid','$sign','$key')";
                    mysqli_query($con, $query);
                    $query="INSERT INTO `user`(`email`, `password`, `groupId`, `hash`,`username`) VALUES ('$email','$password','$groupid','$sign','$key')";
                    mysqli_query($con, $query);
                    $query="DELETE FROM `bufferuser` WHERE userId=$userid";
                    mysqli_query($con, $query);
                }
            }
        }
        mysqli_close($con);
        return $status.'.'.$key.'.'.$email;
    }
    
    
    
/**
 * it create the database space for the user for messaging
 * @param type $mail email address
 * @param type $db database connection
 */
function createuserspace($mail,$db)
{
    $con=$db->start();
    $status=FALSE;
    if(!mysqli_connect_errno())
    {
        $query="SELECT * FROM user WHERE email='$mail'";
        if($result=mysqli_query($con, $query))
        {
            while ($row = mysqli_fetch_array($result)) {
                $groupId=$row['groupId'];
                $status=TRUE;
            }
            if($status)
            {
                $status=FALSE;
                $query="SELECT * FROM user_$groupId WHERE email='$mail'";
                if($result=mysqli_query($con, $query))
                {
                    while ($row = mysqli_fetch_array($result)) {
                        $key=$row['keyId'];
                        $userId=$row['userId'];
                        $status=TRUE;
                    }
                    if($status)
                    {
                        $query="CREATE TABLE usercontent_$groupId"."_$userId"."_msg(msg text NOT NULL,msgId varchar(32) NOT NULL,time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
                        mysqli_query($con, $query);
                    }
                }
            }
        }
    }
    
}

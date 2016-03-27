<?php

require 'script/php/universal.php';
require 'script/php/header.php';

    
    $firstname= strip_tags(filter_input(INPUT_POST, "firstName",FILTER_SANITIZE_STRING));
    $lastname= strip_tags(filter_input(INPUT_POST, "lastName",FILTER_SANITIZE_STRING));
    $email= strip_tags(filter_input(INPUT_POST, "regEmail",FILTER_VALIDATE_EMAIL));
    $password=  strip_tags(filter_input(INPUT_POST, "regPassword",FILTER_SANITIZE_STRING));
    $day= strip_tags(filter_input(INPUT_POST, "day",FILTER_VALIDATE_INT));
    $year= strip_tags(filter_input(INPUT_POST, "year",FILTER_VALIDATE_INT));
    $month= strip_tags(filter_input(INPUT_POST, "month",FILTER_SANITIZE_STRING));
    /**
     * database connection to user table
     */
    $db=new db("user");
    if($firstname&&$email&&$day&&$lastname&&$month&&$password&&$year)
    {
        $confirmcode=  generateName(30);
        $code=  rand(100000,1000000);
        $error="<b>Correct the Following Errors</b><br/>";
        $abort=FALSE;
        if($firstname==$lastname||  strlen($firstname)<3 && !preg_match("/ /", $firstname))
        {
            $error=$error."Invalid First Name<br/>";
            $abort=TRUE;
        }
        if(strlen($lastname)<3&&!preg_match("/ /", $firstname))
        {
            $error=$error."Invalid Last Name<br/>";
            $abort=TRUE;
        }
        if(!sentMail($email, $firstname, $confirmcode, $code))
        {
            $error=$error."Invalid Email<br/>";
            $abort=TRUE;
        }
        if(strlen($password)<7)
        {
            $error=$error."Please enter a password with atleast 8 digits<br/>";
            $abort=TRUE;
        }
        $d1=strtotime("$day $month $year");
        $d2=ceil((time()-$d1)/60/60/24);
        if($d2<4749)
        {
                $error='Sorry you are not eligible to be a member. Read our <a href="tos.php#membership">Terms</a>';
                $abort=TRUE;
        }
        $maildup=verify($email,$db);
        
        if(!$abort&&!$maildup)
        {
            
            $dbstatus=insertData($firstname,$lastname,$email,$password,$day,$month,$year,$confirmcode,$code,$db);
            if($dbstatus)
            {
               
                startTutorial();
                
            }
            else 
            {
                echo 'Db error';
            }
        }
        elseif ($maildup&&!$abort) {
            $token=setTracking("You are already registered! Please login","login",3,new db("site"));
            if($token!=NULL)
            {
                header("Location:".getRoot()."/login.php?red=$token&ml=".urlencode($email));
                exit();
            }
        }
        else
        {
            $token=setTracking($error,"login",2,new db("site"));
            if($token!=NULL)
            {
                header("Location:".getRoot()."/login.php?red=$token&fn=".urlencode($firstname)."&ln=".urlencode($lastname)."&m=".urlencode($email));
                exit();
            }
        }
        
    
    }
    else if(isset ($_GET['me']))
    {
        if($_GET['me']==405)
        {
            confirm("Please Click on the link sent to you E-mail <b>Or</b> Enter the confirmation code below");
        }
    }
    elseif(isset($_POST['regsucess']))
    {
        $reply=maintask(filter_input(INPUT_POST, "regsucess", FILTER_VALIDATE_INT),$db);
        list($stat,$keyidj,$useridt,$Email)=  explode(".", $reply,4);
        if($stat)
        {
             createuserspace($Email,$db);
            if($token=  setTracking($keyidj.'.'.$useridt.'.'.$Email,"setup.php",0,new db("site")))
            {
                header("Location:".getRoot()."/setup.php?red=$token");
                exit();
            }
            else
            {
                $str=  generateRandomString(11)."pie";
                setcookie("alt",$str);
            }
        }
        else 
        {
            confirm("Invalid Confirmation Code");
        }
    }
    else 
    {
        $day=$_POST['day'];
        $year=$_POST['year'];
        $month=$_POST['month'];
        $firstname=$_POST['firstName'];
        $lastname=$_POST['lastName'];
        $email=$_POST['regEmail'];
        $password=$_POST['regPassword'];
        $error="<b>Correct the following errors:</b><br/>";
       
        if($firstname==NULL|| preg_match("/ /", $firstname))
        {
            $error=$error."Please provide your Firstname<br/>";
        }
        if($lastname==NULL|| preg_match("/ /", $lastname))
        {
            $error=$error."Please provide your Lastname<br/>";
        }
        
        if($email==NULL|| preg_match("/ /", $email))
        {
            $error=$error."Please provide your E-mail<br/>";
        }
        $abort=FALSE;
         if(($day>31&&$day<1)&&($month!="January" &&$month!="February"&&$month!="March"&&$month!="April"&&$month!="May"&&$month!="June"&&$month!="July"&&$month!="August"&&$month!="September"&&$month!="October"&&$month!="November"&&$month!="December")&&($year<2015&&$year>1909)||$month=="month"||$day==0 ||$year==0)
        {
            $error=$error."Please Provide A Valid Birthday<br/>";
            
        }
        else 
        {
            $d1=strtotime("$day $month $year");
            $d2=ceil((time()-$d1)/60/60/24);
            echo $d2;
            $abort=TRUE;
            if($d2<4749)
            {
                $error='Sorry you are not eligible to be a member. Read our <a href="tos.php#membership">Terms</a>';
            }
        }
        if(($password==NULL||strlen($password)<8)&&!$abort)
        {
            $error=$error."Please provide a new Password<br/>";
        }
        $token=setTracking($error,"login",2,new db("site"));
        if($token==NULL)
        {
            echo 'Unsucess'.$error;
        }
        else
        {
            header("Location:".getRoot()."/login.php?red=$token&fn=".urlencode($firstname)."&ln=".urlencode($lastname)."&m=".urlencode($email));
                exit();
        }
    }
    $db->close();
    exit();
    
    /**
     * function insert the finall processed info to buffer database along with confirmation code
     * @param type $firstname           first name of user
     * @param type $lastname            last name of user
     * @param type $email               user email
     * @param type $password            user password
     * birthdates
     * @param type $day                 uer birth day
     * @param type $month               birth month
     * @param type $year                birth year
     */
    function insertData($firstname,$lastname,$email,$password,$day,$month,$year,$confirmcode,$code,$db)
    {
        
        
        $row=  getGroupid($db);
        $groupId=$row['groupId'];
        $userId=$row['userId'];
        $hash=  generateName(10);
        $key=  getPassword($password.generateRandomString(10).$userId.$groupId,  generateName(10));
        $password= getPassword($password,$hash);
        $con=$db->start();
        $firstname=mysqli_real_escape_string($con,$firstname) ;
        $lastname=mysqli_real_escape_string($con,$lastname) ;
        $email=mysqli_real_escape_string($con,$email) ;
        $day=mysqli_real_escape_string($con,$day) ;
        $month=mysqli_real_escape_string($con,$month) ;
        $year=mysqli_real_escape_string($con,$year);
        if(!mysqli_connect_errno())
        {
            $query="INSERT INTO `bufferuser`(`firstName`, `lastName`, `email`, `password`, `userId`, `groupId`, `confirmToken`, `day`, `month`, `year`,`keyId`,`sign`,`code`) VALUES ('$firstname','$lastname','$email','$password','$userId','$groupId','$confirmcode',$day,'$month',$year,'$key','$hash',$code)";
            if(mysqli_query($con, $query))
            {
                mysqli_close($con);
                return TRUE;
            }
            else
            {
            echo mysqli_error($con);
            }
        }
        
        mysqli_close($con);
        return FALSE;
    }
    /**
     * 
     * @param type $to          Receptiant
     * @param type $firstName   First name
     * @param type $token       confirmation code
     * @param type $code        code for alternate activation
     * @return boolean          return true if mail transfered for delivery
     */
    function sentMail($to,$firstName,$token,$code)
    {
        $url=  getRoot()."/confirm.php?sand=$token&box=".urlencode($to)."&src=mail&action=101";
        $from = 'signup@peerdisk.tk';
        $subject="$firstName ,Confirm Your PeerDisk Account";
        $headers = "From: " . strip_tags($from) . "\r\n";
        $headers .= "Reply-To: ". strip_tags($from) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message="<div>"
                . "<h3>Confirm SignUp!</h3>"
                . "<br/><br/><br/>"
                . "<span>Hello $firstName,</span><br/>"
                . "<p>Thank you for registering at PeerDisk. To confirm your E-mail address <a href='$url'>Click Here</a> </p>"
                . "<br/>"
                . "<p><b>Or</b></p><br/>"
                . "<p>Use This Code:$code</p><br/><br/><br/>"
                . "----------------------------------------------------------------------------"
                . ""
                . "----------------------------------------------------------------------------"
                . "<br/>"
                . "<p>You are receving this mail beacuse you have registered at PeerDisk!"
                . "If this wasn't you please ignore this</p><br/>"
                . "<span>Regards<span><br/>"
                . "<span>PeerDisk Team<span>"
                . "</div>";
        $result=mail($to, $subject, $message, $headers);
        if($result)
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
    
    
    
    /**
     * display the confirmation code box
     * @param type $msg the message to user
     */
    function confirm($msg="")
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
        printHead();
        echo        '<br/><br/><br/><br/><div>
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
    /**
     * verify the user email
     * @param type $code    the code entered by user
     * @return boolean  true on sucessfull confirmation
     */
    function mainTask($code,$db)
    {
        $con=$db->start();
        $status=FALSE;
        $email=$key="";
        $userid=0;
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM bufferuser WHERE code=$code";
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
        return $status.'.'.$key.'.'.$userid.'.'.$email;
    }

    function getPassword($password,$hash)
    {
        $password=base64_encode($password);
        return md5(md5(md5($password.$hash)));
    }
    
    function getgroupid($db)
    {
        
        $con=$db->start();
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM `group` WHERE keyId='none'";
            if($result= mysqli_query($con, $query))
            {
                while ($row = mysqli_fetch_array($result)) {
                    $r=$row;
                }
                $uid=  rand(1000, 5000);
                $gid=$r['groupId'];
                $query="UPDATE `group` SET userId=userId+$uid WHERE groupId='$gid'";
                if(mysqli_query($con, $query))
                {
                    return $r;
                }
                
            }
            else 
            {
                return NULL; 
            }
        }
    }
    
    function printHead()
{
    echo    '<div id="uBand" class="upperBand">
                <div id="logo" class="logo">
                    <img src="site/img/peerdisk.png" title="PeerDisk.tk" alt="PeerDisk.tk />
                </div>
            </div>';
}

function verify($mail,$db)
{
    $con=$db->start();
    $status=FALSE;
    $query="select * from user where email='$mail'";
    if($result=mysqli_query($con, $query))
    {
        while ($row = mysqli_fetch_array($result)) {
            $status=TRUE;
        }
    
    }
    if(!$status)
    {
        $query="select * from bufferuser where email='$mail'";
        if($result=mysqli_query($con, $query))
        {
            while ($row = mysqli_fetch_array($result)) {
            $status=TRUE;
            }
        }
    }
    return $status;
        
}

function startTutorial()
{
    header("Location:".getRoot()."/register.php?me=405");
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
                       // $query="CREATE TABLE usercontent_$groupId"."_$userId"."_msg(msg text NOT NULL,msgId varchar(32) NOT NULL,time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
                        //mysqli_query($con, $query);
                    }
                }
            }
        }
    }
    
}

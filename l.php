<?php

    require 'script/php/universal.php';
    $Email=  filter_input(INPUT_POST, "lEmail",FILTER_VALIDATE_EMAIL,NULL );
    $Password=filter_input(INPUT_POST, "lPassword",FILTER_SANITIZE_STRING,NULL );
    $db=  new db("user");
    if( $Email!=NULL&&$Email&&$Password&&$Password!=NULL){
        if(preg_match("/@/", $Email)&&preg_match("/./", $Email)&&  strlen($Email)>7){
            
            if($row=confirm($Email,$Password,$db)){
                startSession($row,$db);
                header("Location:".getRoot()."/home.php");
                $db->close();
                exit();
            }
            else if(searchbuffer($Email,$Password,$db)){
                header("Location:".getRoot()."/confirm.php");
                $db->close();
                exit();
            }
            else {
                $token=  setTracking("Invalid E-mail Or Password!","login",1,$t=new db("site"));
                $t->close();
                if($token!=NULL){
                    header("Location:".getRoot()."/login.php?red=$token");
                    $db->close();
                    exit; 
                }
            }
             
        }
    }
    else{
        $token=  setTracking("Invalid E-mail Or Password!","login",1,$t=new db("site"));
        $t->close();
        if($token){
            header("Location:".getRoot()."/login.php?red=$token");
            $db->close();
            exit; 
        }
   }
$db->close();

   /**
    * confirm the login and return group id
    * @param type $mail  entered mail
    * @param type $pass     entered password
    * @return groupid       group id if login sucessful
    */
   function confirm($mail,$pass,$db){
       $con=$db->start();
       $status=FALSE;
       if(!mysqli_connect_errno()){
           $query="SELECT * FROM user WHERE email='$mail'";
           if($result=mysqli_query($con, $query)){
               while ($row = mysqli_fetch_array($result)) {
                   $dbpass=$row['password'];
                   $dbsign=$row['hash'];
                   $gid=$row['groupId'];
                   $status=TRUE;
               }
           }
           else{
               error(mysqli_error($con),"l.php",63);
           }
       }
       if($status){
           $status=FALSE;
           $query="SELECT * FROM user_$gid WHERE email='$mail'";
           if($result=mysqli_query($con, $query)){
               while ($row = mysqli_fetch_array($result)) {
                   $r=$row;
                   $status=TRUE;
               }
                if($status){
                    $pass=getPassword($pass, $dbsign);
                    if($pass==$dbpass){        
                        return $r;
                    }  
                    else{
                        return NULL;
                    }
                }
           }
           
       }
       else{
           return NULL;
       }
   }
   /**
    * 
    * @param type $db database connection
    */
   function searchbuffer($mail,$pass,$db){
       $con=$db->start();
       $status=FALSE;
       if(!mysqli_connect_errno()){
           $query="SELECT * FROM bufferuser WHERE email='$mail'";
           if($result=mysqli_query($con, $query)){
               while ($row = mysqli_fetch_array($result)) {
                   $dbpass=$row['password'];
                   $dbsign=$row['sign'];
                   $status=TRUE;
                   $r=$row;
                }
                if($status){
                    $pass=getPassword($pass, $dbsign);
                    if($pass==$dbpass){        
                        return $r;
                    }  
                    else {
                        return NULL;
                    }
                }
           }
           else{
               error(mysqli_error($con),"l.php",63);
           }
       }
   }
   
/**
 * encrypt the password securely
 * @param type $password    password to encrypt
 * @param type $hash        hash to include
 * @return type             hashed password
 */   
   function getPassword($password,$hash){
        $password=base64_encode($password);
        return md5(md5(md5($password.$hash)));
    }
    
    /**
     * set a session for sucessfully logged user
     * @param type $gid     user group id
     * @param type $mail    user email
     */
    function startSession($row,$db){
        $rem=  filter_input(INPUT_POST, "save", FILTER_VALIDATE_BOOLEAN);
        if($rem){
            $day=60;
        }
        else {
            $day=4;
        }
            $key=$row['keyId'];
            $userId=$row['userId'];
            $gid=$row['groupId'];
            $mail=$row['email'];
            $cookies= generateRandomString(20);                     //generate a cookie
            addCookies($cookies,"login.".$userId,$gid,$key,$mail,$db);           //add cookies to database
            setcookie("lemon", $cookies, time()+ $day*24*60*60);    //set cookies
    }
    
    /**
     * Return entire user data
     * @param type $gid group id
     * @param type $mail    user mail
     * @return row     return the entire row as an array with variables in table user_(groupid)
     */
   /* function getUser($gid,$mail)
    {
        $con=  getDB("user");
        $status=FALSE;
       
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM user_$gid WHERE email='$mail'";
            if($result=mysqli_query($con, $query))
            {
                while ($row = mysqli_fetch_array($result)) {
                    $r=$row;
                   $status=TRUE;
                }
            }
            else{
               echo mysqli_error($con);
            }
        }
        if($status)
        {
            return $r;
        }
       else 
       {
           return NULL;
       }
    }*/
    /**
     * add the cookie to database for further use
     * @param type $cookies     cookie genrated
     * @param type $userId      user id
     * @param type $gid         group id
     * @param type $key         secret key of user
     * @param type $mail        user mail
     */
    function addCookies($cookies,$userId,$gid,$key,$mail,$db) {
        $con= $db->start();
        $ip=  filter_input(INPUT_SERVER, "REMOTE-ADDR",FILTER_VALIDATE_IP);
        $userId=$ip.".".$userId;
        if(!mysqli_connect_errno()){
            $query="DELETE FROM `cookies` WHERE userId='$userId'";
            mysqli_query($con, $query);
            $query="INSERT INTO `cookies`(`id`, `userId`, `groupId`, `key`,`time`) VALUES ('$cookies','$userId','$gid','$key',null)";
            mysqli_query($con, $query);
            if(mysqli_errno($con)==1062){
                startSession($gid, $mail,$db);
            }
            if(mysqli_errno($con)) {
                error(mysqli_error($con),"l.php",180);
            }
        }
    }
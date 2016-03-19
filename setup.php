<?php


?>



<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Set Up Your Account</title>
        <link type="text/css" href="script/css/common.css" rel="stylesheet" >
        <script src="script/js/setup.js"></script>
    </head>
    <body class="bodyMain">
        <?php
        require 'script/php/universal.php';
        require 'script/php/header.php';
        printHeader();
        $sitedb=new db("site");
        $userdb=new db("user");
        
common($sitedb);
        //$cookie=new cookies($userdb);
        //$usrverify=$cookie->valid;
        echo '</div>';
        ?>
    <br/>
    <div class="maincontent">
            <?php
                    printmaincontent($sitedb,$userdb);
            ?>
    </div>
        <?php
        
        //some global variables
        $fail=FALSE;
        $addnodes_count=0;
        /**
         * 
         * @param dbconnect $sitedb database connection to site
         * @param string $token token passed
         * @return mixed boolean.string
         */
        function verifySource($sitedb,$token=NULL){
            
            $tstatus=FALSE;   
            $uid='.';
            if($token){
                $con=$sitedb->start();
                $token= mysqli_real_escape_string($con,$token);
                    if (!mysqli_connect_errno()){
                        $ip=$_SERVER['REMOTE_ADDR'];//filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
                        $query="SELECT * FROM tracking WHERE (token='$token' AND ip='$ip')";
                        if($result=mysqli_query($con, $query)){
                            while ($row = mysqli_fetch_array($result)){
                                $tstatus=TRUE;
                                $uid=$row['error'];
                            }
                        }
                    }
                if(!$tstatus) {
                    header("Location:".getRoot());
                    exit();
                }
            }
            return $tstatus.'.'.$uid;
        }
        
         
         /**
          * 
          * @global boolean $fail
          * @global db $userdb
          * @param bigint $userid the userid of user
          */
        function initiateupload($keyid,$mail,$uid){  //starts uploload script
            global $fail;
            
            $photo=  uploadit("profilepic");
            if($photo) {
               // $photo=  getRoot()."/".$photo;
                $cid=getcontentid("profile_pic");
                if($cid==0){
                    $fail=TRUE;
                }
               // $con= $userdb->start(); //Initialising Connection 
                if(!$fail){ //If Connection Established Sucessfully
                   $photoinfo=array('link'=>$photo,'id'=>$cid,'type'=>"profile_pic",'date'=>date('j F Y'),'time'=>date('g:i A'),'keyid'=>$keyid);
                    writecontentid($photoinfo,$uid);
                    startSession($keyid,$mail);
                    echo '<script type="text/javascript">
window.location.replace("'.getRoot().'/home.php");
</script><noscript><a href="/home.php">Click here</a> to continue</noscript>';
                }
                else if($fail){
                    echo "<a>Upload File Transfer Failed!";
                }
            }
            else{
                echo 'upload fail'; // echo "<a>Uncomplete Form</a>";
            }
    }
    
    /**
     * write the contrent id of user to XML file
     * @param array $photo contentid to be written
     */
    function writecontentid($photo,$uid){
        global $userdb;
        $query='INSERT INTO `photos`(`url`, `time`, `type`, `id`, `owner`) VALUES ("'.$photo["link"].'",null,"'.$photo["type"].'",'.$photo["id"].',"'.$photo["keyid"].'.'.$uid.'")';
        mysqli_query($userdb->start(), $query);
        
        
        /*        $filename="usr/content/post/".$photo['keyid']."_photo.xml";
        if (!file_exists($filename))
        {
            //create the file
            $handle=fopen($filename, 'w');
            fwrite($handle,'<?xml version="1.0" encoding="UTF-8"?>
<BASE><PHOTO id="'.$photo['id'].'" type="'.$photo['type'].'" sno="1"><URL>'.$photo['link'].'</URL><DATE>'.$photo['date'].'</DATE><TIME>'.$photo['time'].'</TIME></PHOTO></BASE>');
            fclose($handle);
                //initialise xml writer
           /* $xmlwriter=new XMLWriter();
            $xmlwriter->openUri("usr/info/s.xml");
                //start document
            $xmlwriter->startDocument($version='1.0', $encoding='UTF-8');
            $xmlwriter->setIndent(FALSE);
            $xmlwriter->startElement("BASE");
            $xmlwriter->startElement("PHOTO");
                //attributes
            $xmlwriter->writeAttribute("id",$photo['id']);
            $xmlwriter->writeAttribute("type",$photo['type']);
            $xmlwriter->writeAttribute("sno", 1);
                    //content
                $xmlwriter->startElement("URL");
                $xmlwriter->writeRaw($photo['link']);
                $xmlwriter->endElement();
        
                $xmlwriter->startElement("DATE");
                $xmlwriter->writeRaw($photo['date']);
                $xmlwriter->endElement();
            
                $xmlwriter->startElement("TIME");
                $xmlwriter->writeRaw($photo['time']);
                $xmlwriter->endElement();
                
                //end document
            $xmlwriter->endElement();
            $xmlwriter->endElement();   
            $xmlwriter->endDocument();
            $xmlwriter->flush();*/
        
    }

    function startsession($key,$mail){
        global $userdb;
        $con=$userdb->start();
        $status=FALSE;
        if($userdb->status){
            $query="SELECT * FROM user WHERE email='$mail'";
            if($result=mysqli_query($con, $query)){
                while ($row = mysqli_fetch_array($result)) {
                    $grp=$row['groupId'];
                    $status=TRUE;
                }
                if($status){
                    $status=FALSE;
                    $query="SELECT * FROM user_$grp WHERE email='$mail'";
                    if($result=  mysqli_query($con, $query)){
                        while ($row = mysqli_fetch_array($result)) {
                            $status=TRUE;
                            $userid=$row['userId'];
                        }
                        if($status){
                            $cookies=generateRandomString(20);
                            insertcookie($cookies,"login.".$userid,$grp,$key,$mail,$userdb);
                            $day=60;
                            setcookie("lemon", $cookies, time()+$day*24*60*60);
                        }
                    }
                }
            }
        }
    }
    
      /**
     * add the cookie to database for further use
     * @param type $cookies     cookie genrated
     * @param type $userId      user id
     * @param type $gid         group id
     * @param type $key         secret key of user
     * @param type $mail        user mail
     */
    function insertcookie($cookies,$userId,$gid,$key,$mail,$db) {
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


    /**
     * 
     * @global boolean $fail
     * @param string $id the id/ name of the file
     * @return string
     */
    function uploadit($id)
   {
        global $fail;
        $allowedExts = array( "jpeg", "jpg", "png","JPEG","JPG","PNG");
        $temp = explode(".", $_FILES[$id]["name"]);
        $extension = end($temp);
        if (( ($_FILES[$id]["type"] == "image/jpeg")|| ($_FILES[$id]["type"] == "image/jpg")|| ($_FILES[$id]["type"] == "image/pjpeg")|| ($_FILES[$id]["type"] == "image/x-png")|| ($_FILES[$id]["type"] == "image/png"))&& ($_FILES[$id]["size"] <= 512000)&& in_array($extension, $allowedExts))
        {
            if ($_FILES[$id]["error"] > 0)
            {
                $fail=true;
            }
            else
            {
                $folder=printDetails($id);
                return $folder;
            }
        }
        else
        {
            echo ' <span style="font-size: 20px;color: black;">
                Upload your profile picture
            </span><br/>
            <div class="dialog">
                    <form action="" method="post" enctype="multipart/form-data" class="dialogform">
                    <span style="color:red;">Invalid File Type</span><br/>
                    <label>Choose a profile pic:</label>
                    <input type="file" name="profilepic" id="profilepic" class="highlightfield"/><br/>
                <span class="specialtext">Maximum size 500 Kb<br/>Allowed file type:JPEG,PNG,JPG </span>
                <input type="text" value="'.$token.'" name="red" style="visibility:hidden;"/>
                <input type="number" style="visibility: hidden;" name="pie" value="108" /><br/>
                <input type="text" value="0x000000" style="visibility: hidden;" name="temp"/><br/>
                <input type="submit" value="Upload" class="button"/>
            </form>
            </div>
            <hr/>
            <form action="setup.php" method="post" style="float: right;">
                <input type="number" value="109" name="pie" style="visibility:hidden;"/>
                <input type="submit" value="Skip" class="button"/>
            </form>';
            $fail=true;
        }
        return NULL;
    }
    
    /**
     * deside the file name and copy the file to the specified folder
     * @global boolean $fail
     * @param string $id
     * @param int $count
     * @return string
     */
    function printDetails($id,$count=0)
    {
        global $fail;
        $temp = explode(".", $_FILES[$id]["name"]);
        $extension = end($temp);
        $fname= generateRandomString(15).'_' .rand(1000000000, 100000000000);
        //echo "Upload: " . $_FILES[$id]["name"] . "<br>";
        //echo "Type: " . $_FILES[$id]["type"] . "<br>";
        //echo "Size: " . ($_FILES[$id]["size"] / 1024) . " kB<br>";
        //echo "File Name: " . $fname.'.'.$extension. "<br>";
        if (file_exists("usr/upload/" . $fname.'.'.$extension) )
        {
            if($count==0)
            {
                printDetails($id,++$count);
            }
            else 
            {
                $fail=TRUE;
            }
            //echo $_FILES[$id]["name"] . " already exists. ";
            
        }
        else 
        {
            if( move_uploaded_file($_FILES[$id]["tmp_name"],"usr/content/photo/" .$fname.'.'.$extension))
            {
                $folder= "usr/content/photo/" . $fname.'.'.$extension;                     
                return $folder;
            }
        }
    }
    
    /**
     * echo the main contents according to POST and GET requests
     * @param db $sitedb
     * @param db $userdb
     */
    function printmaincontent($sitedb,$userdb){
        $request=  filter_input(INPUT_POST, "pie", FILTER_VALIDATE_INT);
        if($request>10)
        {
            $token=  filter_input(INPUT_POST,"red", FILTER_SANITIZE_STRING);
        }
        else 
        {
            $token=  filter_input(INPUT_GET,"red", FILTER_SANITIZE_STRING);
        }
        $treply=  verifySource($sitedb,$token);
        list($tstatus,$key,$uid,$mail)=  explode(".", $treply,4);       //Databse status,keyId,Email,UserId. The first parameter shows the request sucess and tthe rest are the database pipe.
        $uname=  filter_input(INPUT_POST, "uname",FILTER_SANITIZE_STRING);
        if($request==102&&isset($uname)&&$uname&&$tstatus)
        {
            $reply=addusername($sitedb,$userdb,$token,$uname);
            if($reply)
            {
            echo ' <span style="font-size: 20px;color: black;">
                Upload your profile picture
            </span><br/>
            <div class="dialog">
                    <form action="" method="post" enctype="multipart/form-data" class="dialogform">
                    <label>Choose a profile pic:</label>
                    <input type="file" name="profilepic" id="profilepic" class="highlightfield"/><br/>
                <span class="specialtext">Maximum size 500 Kb<br/>Allowed file type:JPEG,PNG,JPG </span>
                <input type="text" value="'.$token.'" name="red" style="visibility:hidden;"/>
                <input type="number" style="visibility: hidden;" name="pie" value="108" /><br/>
                <input type="submit" value="Upload" class="button"/>
            </form>
            </div>
            <hr/>
            <form action="setup.php" method="post" style="float: right;">
            <input type="text" value="'.$token.'" name="red" style="visibility:hidden;"/>
                <input type="number" value="109" name="pie" style="visibility:hidden;"/>
                <input type="submit" value="Skip" class="button"/>
            </form>';
            }
            else 
            {
                error("username adding failed".mysqli_errno($userdb->start()), 'setup.php', 202);
            }
        }
        elseif ($request==108&&$tstatus) 
        {
            initiateupload($key,$mail,$uid);
           
        }
        elseif($request==109&&$tstatus){
             startSession($key,$mail);
                    echo '<script type="text/javascript">
window.location.replace("'.getRoot().'/home.php");
</script><noscript><a href="/home.php">Click here</a> to continue</noscript>';
        }
        
        elseif (!$uname&&$request==102&&$tstatus)
        {
            echo ' <span style="font-size: 20px;color: black;">
                Set Your User Name
            </span><br/>
            <div class="dialog">
                <form action="setup.php" method="post" class="dialogform">
                <span style="color:red;">Please enter a valid Username</span><br/>
                <label>Username:</label>
                <input type="text" name="uname" class="text" placeholder="Username"/><br/>
                <span class="specialtext" >Please note that you cannot change this in future</span><br/>
                <input type="number" value="102" name="pie" style="visibility:hidden;"/>
                <input type="submit" class="button" value="Save" style="float: right;"/>
            </form>';
        }
        else if($tstatus)
        {
            echo ' <span style="font-size: 20px;color: black;">
                Set Your User Name
            </span><br/>
            <div class="dialog">
                <form action="setup.php" method="post" class="dialogform">
                <label>Username:</label>
                <input type="text" name="uname" class="text" placeholder="Username"/><br/>
                <span class="specialtext" >Please note that you cannot change this in future</span><br/>
                <input type="number" value="102" name="pie" style="visibility:hidden;"/>
                <input type="text" value="'.$token.'" name="red" style="visibility:hidden;"/>
                <input type="submit" class="button" value="Save" style="float: right;"/>
            </form>';
        }
        else 
        {
            echo 'Invalid Request!';
        }
    }
   
        printFooter();//prints the footer of page func located@ universal.php
        
        /**
         * adds the user name to table user
         * @param db $sitedb
         * @param db $userdb
         * @param string $token
         * @param string $name
         * @return boolean true on sucess
         */
        function addusername($sitedb,$userdb,$token,$name){
            $con=$sitedb->start();
            $conusr=$userdb->start();
            $status=FALSE;
            $ip=$_SERVER['REMOTE_ADDR'];
            if($sitedb->status){
                $query="SELECT * FROM tracking WHERE (token='$token' AND ip='$ip')";
                if($result=mysqli_query($con, $query)){
                    while ($row = mysqli_fetch_array($result)){
                        $keyid=$row['error'];
                       list( $keyid,$temp)=  explode(".", $keyid,2);
                        $status=TRUE;
                    }
                    if($status){
                        $status=FALSE;
                        $query="UPDATE user SET username='$name' WHERE username='$keyid'";
                        if(mysqli_query($conusr, $query)){
                            $status=TRUE;
                        }
                        elseif(mysqli_errno($conusr)==1062){
                            echo ' <span style="font-size: 20px;color: black;">
                                    Set Your User Name
                                    </span><br/>
                                    <div class="dialog">
                                    <form action="setup.php" method="post" class="dialogform">
                                        <span style="color:red;">Sorry! The username you entered already exist</span><br/>
                                        <label>Username:</label>
                                            <input type="text" name="uname" class="text" placeholder="Username" value="'.getvalidusername($conusr,$name).'"/><br/>
                                        <span class="specialtext" >Please note that you cannot change this in future</span><br/>
                                        <input type="number" value="102" name="pie" style="visibility:hidden;"/>
                                        <input type="text" value="'.$token.'" name="red" style="visibility:hidden;"/>
                                        <input type="submit" class="button" value="Save" style="float: right;"/>
                                    </form>';
                        }
                    }
                }
            }
            return $status;
        }
        
        /**
         * 
         * @param dbconnection $con
         * @param string $name
         * @param int $count
         * @return string
         */
        function getvalidusername($con,$name,$count=0)
        {
            $number=rand(0,999);
            $query="SELECT username from user where username='$name$number'";
            if($result=mysqli_query($con, $query))
            {
                if($row=  mysqli_fetch_array($result))
                {
                    if($count<3)
                    {
                        getvalidusername($con, $name, ++$count);
                    }
                    else
                    {
                        return '';
                    }
                }
                else 
                {
                    return $name.$number;
                }
            }
        }
        
        /**
         * generate a valid content id as per the previous requests
         * @global db $userdb
         * @param string $type
         * @return int
         */
        function getcontentid($type){
            global $userdb;
            $con=$userdb->start();
            $query="SELECT * FROM content_id WHERE (`group_id`='lkjfndjrcs' AND `type`='$type')";
            if($result=  mysqli_query($con, $query))
            {
                $cno;
                while ($row = mysqli_fetch_array($result)) {
                    $cno= $row['id'];
                }
                if($cno>0)
                {  
                    $cid= rand(1000, 10000);
                    $cno=$cno+$cid;
                    $query="UPDATE content_id SET id=$cno WHERE (`group_id`='lkjfndjrcs' AND `type`='$type')";
                    if(mysqli_query($con, $query))
                    {
                        return $cno;
                    }
                }
            }
            return 0;
        }
        
        
        
        
        //terminates initialisations
        $userdb->close();
        $sitedb->close();
        ?>
    </body>
</html>

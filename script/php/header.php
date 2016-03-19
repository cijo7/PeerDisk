<?php

function printHeader()
{
    echo    '<div id="uBand" class="upperBand">
                <div id="logo" class="logo">
                    <img src="site/img/peerdisk.png" title="PeerDisk.tk" width="300px" height="70px" alt="PeerDisk.tk" />
                </div><div id="profile"></div> 
            ';
}

function printFooter()
{
    echo '<div class="footer">
            <hr class="mseperator">
            <div class="footerContent">
                <a href="contact.php" class="footerlinks">Contact Us</a>
                <a href="tos.php" class="footerlinks">Terms Of Service</a>
                <a href="policy.php" class="footerlinks">Privacy Policy</a><br>
                <a href="advertise.php" class="footerlinks">Advertise With Us</a>
                <a href="troubleshooting.php" class="footerlinks">Trouble Shooting</a><br>
                <a href="/faq.php" class="footerlinks">FAQ</a>
            </div>
            <br/>
            <div id="copyright" > © CUBEBeans 2014-'.date("Y").'</div>
        </div>';
}

function imports()
{
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Download the world" >
        <meta name="keywords" content="connect,Message,share,meet friends,free" >
        <link type="text/css" href="script/css/common.css" rel="stylesheet" >
        <link rel="shortcut icon" href="site/img/favicon.ico" type="image/x-icon">
        <script src="script/js/common.js" type="text/javascript" ></script>';
}

//all classes to be used for all users
/**
 * manage cookies here
 */
class cookies{
    /**
     *userid
     * @var bigint
     */
    var $userId;
    /**
     *groupid
     * @var string
     */
    var $groupId;
    var $key;
    /**
     *cookie databaseconnection
     * @var dbconnection
     */
    var $cdb;
    /**
     * if the set cookies is valid
     * @var boolean
     */
    var $valid;
            
    
    /**
     * checks and store cookie info
     * @param string $db the user db name
     */
    function cookies($db)
    {
        $this->cdb=$db;
        $this->userId=NULL;
        $this->groupId=NULL;
        $this->key=NULL;
        $this->valid=FALSE;
        
        if(isset($_COOKIE["lemon"]))
        {
            $con= $this->cdb->start();
            $lemon= mysqli_real_escape_string($con,strip_tags($_COOKIE["lemon"]));
            if(!mysqli_connect_errno())
            {
                $query="SELECT * FROM cookies WHERE id='$lemon'";
                if($result=mysqli_query($con, $query))
                {
                    while ($row = mysqli_fetch_array($result)) {
                        $uidt=explode(".",$row['userId']);
                        $this->userId=end($uidt);
                        $this->groupId=$row['groupId'];
                        $this->key=$row['key'];
                        $this->valid=TRUE;
                    }
                }
            else
            {
                echo mysqli_error($con);
            }
            }
           
        }
    }
    
    /**
     * checks whether a user is currently logged in. if he is then he is redirected to home page
     */
    function checkLogged()
    {
        if($this->userId!=NULL && $this->groupId!=NULL)
        {
            header("Location:".getRoot()."/home.php");
            exit;
        }
    }
    
    /**
     * checks if a user is not logged in and redirect to login page
     * This can be placed all over the pages
     */
    function checkUnlogged()
    {
        if($this->userId==NULL||  $this->groupId==NULL)
        {
            header("Location:".getRoot()."/login.php");
            exit;
        }
    }
    
}





class user extends cookies
{
    
    var $firstName;
    var $lastName;
    var $email;
    var $udb;
    /**
     * initialaise the user info
     * @param string $db the user database
     */
    function user($db)
    {
        $this->udb=$db;
        $this->firstName=NULL;
        $this->lastName=NULL;
        $this->email=NULL;
        $this->cookies($db);
        $con= $this->udb->start();
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM user_$this->groupId WHERE (groupId='$this->groupId' AND userId='$this->userId' AND keyId='$this->key')";
            if($result=mysqli_query($con, $query))
            {
                while ($row = mysqli_fetch_array($result)) {
                    $this->firstName=$row['firstName'];
                    $this->lastName=$row['lastName'];
                    $this->email=$row['email'];
                    
                }
            }
        }
        
    }
}


class usercontent extends cookies
{
    static $msgId;
    static $fileId;
    static $postId;
    static $mediaId;
    var $ucdb;
    /**
     * initialise the basic info of user
     * @param string $db user db
     */
    function usercontent($db)
    {
        $this->ucdb=$db;
        $this->msgId=NULL;
        $this->fileId=NULL;
        $this->postId=NULL;
        $this->mediaId=NULL;
        $this->user($db);
        $con= $this->ucdb->start();
        if(!mysqli_connect_errno())
        {
            $query="SELECT * FROM userContent WHERE keyId='$this->key'";
            if($result=mysqli_query($con, $query))
            {
                while ($row = mysqli_fetch_array($result)) {
                    $this->msgid=$row['msgId'];
                    $this->postId=$row['postId'];
                    $this->mediaId=$row['mediaId'];
                    $this->fileId=$row['fileId'];
                }
            }
            else
            {
                echo mysqli_error($con);
            }
        }
        
    }
}
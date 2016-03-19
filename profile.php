<!DOCTYPE html>

<html>
    <?php 


        require 'script/php/universal.php';
        require 'script/php/header.php';
        $fail=FALSE;
$site= new db("site");
common($site);
        $page= filter_input(INPUT_GET, 'u', FILTER_SANITIZE_STRING);
        $userdb=new db("user");
        $con=$userdb->start();
        if($userdb->status){  //if database connection
            $query="SELECT * FROM user where username='$page'";
            if($result=  mysqli_query($con, $query)){
                $status=FALSE;
                while ($row = mysqli_fetch_array($result)) {
                    $status=TRUE;
                    $gid=$row['groupId'];
                    $mail=$row['email'];
                }
                if($status){
                    $status=FALSE;
                    $query="SELECT * FROM user_$gid WHERE email='$mail'";
                    if($result=mysqli_query($con, $query)){
                        while ($row = mysqli_fetch_array($result)) {
                            $status=TRUE;
                            $fname=$row['firstName'];
                            $lname=$row['lastName'];
                            $day=$row['day'];
                            $month=$row['month'];
                            $year=$row['year'];
                            $key=$row['keyId'];
                            $uid=$row['userId'];
                        }
                        if($status){
                            $query="SELECT `url` from  `photos` WHERE  `owner`='$key.$uid' AND `type`='profile_pic'";
                            if($result=  mysqli_query($con, $query) ){
                                while ($row= mysqli_fetch_array($result)) {
                                    $profile_pic=$row['url'];
                                }
                                $userinfo=  array('fname'=>$fname,'lname'=>$lname,'mail'=>$mail,'day'=>$day,'month'=>$month,'year'=>$year,'key'=>$key,'url'=>$profile_pic);                           
                            }
                        }
                    }
                }
                else{
                    $fail=TRUE;
                }
            }
            else{
                $fail=TRUE;
            }
        }
    ?>
    <head>
        <meta charset="UTF-8">
        <title id="ptitle"><?php if(!$fail){echo $userinfo['fname']." ".$userinfo['lname']; }else{
          echo 'Not found!';}?></title>
        <link type="text/css" href="script/css/common.css" rel="stylesheet" >
    </head>
    <body class="bodyMain text">
       
        <?php 
        printHeader();
        //terminates
        $site->close();
        echo '</div><br/>';
        ?>
       
        <div class="maincontent">
        <?php 
        if(!$fail){
            printprofile($userinfo);
        }
        else{
                    echo 'The page you are looking is not found or may have been removed!';
        }
        /**
         * 
         * @param array $userinfo
         */
        function printprofile($userinfo)
        {
            echo '<div id="profile-tab">';
            if(isset($userinfo['url'])){
                echo '<img src="'.$userinfo['url'].'" width="100px" height="100px" alt="'.$userinfo['fname'].' '.$userinfo['lname'].'"/>';
            }else{
                echo '<img src=site/img/user/default.jpg width="100px" height="100px" alt="'.$userinfo['fname'].' '.$userinfo['lname'].'"/>';
            }
            echo '<div><h3>'.
                    $userinfo['fname'].' '.$userinfo['lname']
                    .'</h3>';
            echo '<h4>Info</h4>'
            . '<span style="padding-left:40px;">He was born on '.$userinfo['month'].' '.$userinfo['day'].' '.$userinfo['year'].'</span>' ;
            echo '</div>';
        }
        ?>
        </div>
        <?php
        printFooter();
        $userdb->close();
        ?>
    </body>
</html>
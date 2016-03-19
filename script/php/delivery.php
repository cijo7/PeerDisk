<?php
require 'header.php';
require 'universal.php';

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Type: text/html");
$db=new db("user");
$me=new user($db);
$email=$me->email;
$con=$db->start();
$query="SELECT username FROM user WHERE email='$email'";
$result=mysqli_query($con, $query);
$user=mysqli_fetch_array($result);
$username=$user['username'];
        //$me->checkUnlogged();
echo '<div class="feedsegment commoncontent" style="background-color: #FFC46D;">Dear<a href="'.$username.'" class="n_link" > '.$me->firstName.' '.$me->lastName.'</a>, We are currently under construction. ThankYou for your intrest in testing this service. More 
                facility will be added shortely</div><hr/>';
 for($i=0;$i<8;$i++)
         {
        echo '<div class="feedsegment commoncontent">Posted By <a class="n_link" href="'.$username.'">'.$me->firstName.' '.$me->lastName.'</a><br/><br/>'
       .'<span >This is an automated post<br/>';
            
                 for ($r=0;$r<50;$r++)
                {
                    echo generateRandomString(8).' ';
                }
        echo '</span></div>';
        if($i!=7)
            echo '<hr/>';
         }
         
    

$db->close();
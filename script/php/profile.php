<?php

require 'header.php';
require 'universal.php';
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Type: text/html");
$db=new db("user");
$me=new user($db);
?>
<span id="profiler" onclick="tiggerProfile()"><a style="color: #EEEEEE;text-decoration: none;" href="#">
   <?php
   echo $me->firstName;
   $db->close();
   ?>
    </a>
</span>
<menu id="menu">
    <a href="settings.php" class="menuitem">Settings</a><br/>
    <a href="logout.php" class="menuitem">Logout</a>
    
</menu>
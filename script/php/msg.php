<?php

/* 
::::::::::::::DEVELOPER NOTES::::::::::::::::
just render the data on mysql

*/
require_once 'header.php';
require_once 'universal.php';
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Content-Type: text/html");
$d=new db("user");

?>
<div id="msgbind">
   <span style="padding: 10px;">
       No new messages.
   </span>
</div>
<?php

$d->close();
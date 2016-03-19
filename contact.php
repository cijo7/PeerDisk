<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Contact Us</title>
     <?php
  
        require 'script/php/header.php';
        require 'script/php/universal.php';
        imports();
$site= new db("site");
common($site);
        ?>
    </head>
    <body class="bodyMain">
        <?php
        printHeader();
        echo '<br/>';
        printFooter();
        //terminates
        $site->close();
        ?>
    </body>
</html>

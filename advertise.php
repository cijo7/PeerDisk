<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Advertise With Us!</title>
         <?php
        require 'script/php/header.php';
        $site= new db("site");
        common($site);
        imports();
        ?>
    </head>
    <body class="bodyMain">
        <?php
        printHeader();
        ?>
        <br/>
        <?php
        printFooter();
    
        
        //terminates
        $site->close();
        ?>
    </body>
</html>

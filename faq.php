<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>FAQ'S</title> 
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
        echo '<br/>';
        printFooter();
        // put your code here
        ?>
    </body>
</html>

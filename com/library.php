<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="#" method="post">
            <input type="text" name="word" />
            <input type="submit" value="Add" />
        </form>
        <?php
        require '../script/php/universal.php';
        $word=  filter_input(INPUT_POST, "word", FILTER_SANITIZE_STRING);
        if(isset($word)){
            $site=new db("site");
            $con=$site->start();
            $query="INSERT INTO `namelibrary`(`word`) VALUES ('$word')";
            if(mysqli_query($con, $query))
            {
                echo "Word $word added";
            }
        }
        ?>
    </body>
</html>

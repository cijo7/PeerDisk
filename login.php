<?php

require 'script/php/universal.php';
require 'script/php/header.php';
$db=new db("user");
$me=new cookies($db);
$me->checkLogged();
$site= new db("site");
common($site);
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Welcome To Cubebeans.com| Connect With Your Friends And Family</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="New generation of social networking. Chat with your friends!" >
        <meta name="keywords" content="chat,play,games,downloads,playstore,apps,free,free downloads" >
        <link type="text/css" href="script/css/login.css" rel="stylesheet" >
        <link type="text/css" href="script/css/common.css" rel="stylesheet" >
        <link rel="shortcut icon" href="site/img/favicon.ico" type="image/x-icon">
        <script src="script/js/login.js" type="text/javascript" ></script>
        <script src="script/js/common.js" type="text/javascript" ></script>

    </head>
    
        <?php
        $error="";
        $loc=0;
        $status=FALSE;
        $ln=filter_input(INPUT_GET,"ln" , FILTER_SANITIZE_STRING);
        $m=filter_input(INPUT_GET,"m" , FILTER_VALIDATE_EMAIL);
        $fn=filter_input(INPUT_GET,"fn" , FILTER_SANITIZE_STRING);
        $ml=filter_input(INPUT_GET,"ml" , FILTER_VALIDATE_EMAIL);
        if(isset($_GET['red'])||( $fn&&$ln&&$m))
        {
            $con=$site->start();
            if (!mysqli_connect_errno())
            {
                $token=$_GET['red'];
                $ip=$_SERVER['REMOTE_ADDR'];
                $query="SELECT * FROM tracking WHERE token='$token' ";
                if($result=mysqli_query($con, $query))
                {
                    while ($row = mysqli_fetch_array($result)) {
                            $error=$row['error'];
                            $loc=$row['location'];
                            $status=TRUE;
                    }
                    if($status)
                    {
                        $rSt=removeTracking($token);
                    }
                    else 
                    {
                        $error="Please give valid inputs!";
                    }
               }
            }
           
        }
        $site->close();
        $db->close();
    ?>
    <body class="bodyMain">
        <div id="loguBand" class="upperlogBand">
            <div id="logo" class="logo">
                <img src="site/img/peerdisk.png" title="CUBEBeans.tk" width="300" height="70" alt="CUBEBeans.com" />
                </div>
            <div class="loginPortal">
                <div class="loginForm" >
                    <form action="l.php" method="post" >
                        <input class="logintextbox commonText" type="text" name="lEmail" id="email" placeholder="Email" aria-required="true" value="<?php if($loc==3){ echo $ml;}  ?>"/>
                        <input class="logintextbox commonText" type="password" name="lPassword" id="password" placeholder="Password" aria-required="true" value=""/>
                        <input id="loginButon" class="button" type="submit" value="Login" />
                        <br/>
                        <span id="error" class="error"><?php if($loc==1){ echo $error;} ?>
                    </span>
                        <br/>
                        <input type="checkbox" value="TRUE" name="save" checked/>
                        <span class="commonText">Remember me </span> <a onclick="displayHelp(1)" class="question commonText" href="#" style="text-decoration: none;" > ?</a>
                        
                    </form>
                    <span id="q1" class="popup"></span><span id="c1" class="close commonText" onclick="removeHelp(1)"><a class="nolink" href="#">X</a></span>
                </div>
            </div>
        </div>
        
       
        
        <div class="content">
            <div class="description commonText">
                
                <div id="paragraph" class="paragraph">
                    <h3 class="paragraphTitle" >Open Up </h3>
                    <p class="paragraphText">
                        CUBEBeans Gives a new meaning to social Networking!<br/>
                        We Help you to be always connected with your loved ones on the go.<br/>
                    </p>
                    <h3 class="paragraphTitle">Connect</h3>
                    <p class="paragraphText" >
                        Connect with your friends and family from any device!
                    </p>
                    <h3 class="paragraphTitle">Share</h3>
                    <p class="paragraphText">
                        Share the Beautiful moments of life with your friends!
                    </p>
                    <h3 class="paragraphTitle" >Privacy</h3>
                    <p class="paragraphText">
                        We Respect your privacy. We don't sell your information.
                    </p>
                </div>
            </div>
            <div class="signUp commonText">
                <div><h1 id="signUpBanner"> Sign Up Now!</h1>
                    <h2 id="quote">It's Totally Free!</h2></div>
                <noscript><div id="jserror">
                    Javascript in your browser is disabled. Kindly enable it on your browsers settings
                    <b><i>Or</i></b> Visit our <a href="http://www.m.cubebeans.tk">mobile site</a>!</div>
                </noscript>
                <form id="signUpForm" action="register.php" method="post">
                    <div>
                        <span class="error" > <?php if($loc==2){    echo $error;  } if($loc==3){    echo $error;  } ?> </span>
                    </div>
                    <div class="signUpName">
                        <input id="firstName" name="firstName" class="signUpTextboxname signUpTextbox fll commonText" value="<?php echo $fn; ?>" onclick="helpPop(this)" onchange="helpError(this)" type="text" placeholder="First Name" data-signup-help="Your Name" data-signup-hint="Invalid Name" data-id="1" />
                        <span id="firstNameh" class="popup"></span><img id="pfirstName" class="pointer" src="site/img/login/pointr.png" alt="" width="20" height="20"/>
                        <input id="lastName" name="lastName" class="signUpTextboxname signUpTextbox flr commonText" value="<?php echo $ln; ?>" onclick="helpPop(this)" onchange="helpError(this)" type="text" placeholder="Last Name" data-signup-help="Your Last Name" data-signup-hint="Invalid Last Name" data-id="2"/>
                        <span id="lastNameh" class="popup"></span><img id="plastName" class="pointer" src="site/img/login/pointl.png" alt="" width="20" height="20"/>
                    </div>
                    <div>
                    <input id="regEmail" class="signUpTextbox commonText" type="text" name="regEmail" value="<?php echo $m; ?>" onclick="helpPop(this)" onchange="helpError(this)" placeholder="Your Émail" data-signup-help="Your E-mail To Which We Can Contact You" data-signup-hint="Please Provide A Valid E-mail" data-id="3"/><br/>
                    <span id="regEmailh" class="popup"></span><img id="pregEmail" class="pointer" src="site/img/login/pointr.png" alt="" width="20" height="20"/>
                    <span id="birthDateh" class="commonText">Birthday</span> <a class="question commonText" onclick="displayHelp(2)"  href="#" style="text-decoration: none;" > ?</a><br/>
                    <span id="birthDate">
                        <select id="birthDated" class="bselbox" name="day">
                        <option  value="0" selected>Day</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>
                    <select id="birthDatem" class="bselbox" name="month">
                        <option value="Month" selected>Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>
                    <select id="birthDatey" class="bselbox" name="year">
                        <option value="0">Year</option>
                       <?php
                            for($i=2014;$i>1909;$i--)
                            {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                        
                    </select>
                    </span>
                    <span id="q2" class="popup" style="visibility: hidden"></span><span id="c2" class="close commonText" onclick="removeHelp(2)"><a class="nolink" href="#">X</a></span>
                    <input id="regPassword" class="signUpTextbox commonText" type="password" name="regPassword"  value="" onclick="helpPop(this)" onchange="helpError(this)" placeholder="Your Password" data-signup-help="Your New Password" data-signup-hint="Please Make Sure Your Password Is Atleast 8 Charaters" data-id="4"/><br/>
                    <span id="regPasswordh" class="popup"></span><img src="site/img/login/pointr.png" id="pregPassword" class="pointer" alt="" width="20" height="20"/>
                    </div>
                    <span id="terms">By  Signing Up you Agree to Our <a href="tos.php">Terms And Conditions</a>.</span><br/>
                    <input id="signUpButton" class="button" type="submit" value="Sign Up"/>
                </form>
            </div>
        </div>
        <?php
        printFooter(); ?>
   </body>
</html>

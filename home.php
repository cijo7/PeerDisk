<?php

        require 'script/php/header.php';
        require 'script/php/universal.php';
        $userdb=new db("user");
        $cokie=new cookies($userdb);
        $cokie->checkUnlogged();
        $site= new db("site");
        common($site);
        //header("Access-Control-Allow-Origin");
?>
<!DOCTYPE html>

<html>
    <head>
        <meta  http-equiv="Content-Type" content="text/html; charset=UTF-8" >
        
        <?php
        imports();
        ?>
        <title>Home</title>
        <script src="script/ajax/pump.js?45"></script>
        <link href="script/css/styles.css" type="text/css" rel="stylesheet"/>
        </head>
        
        <body class="bodyMain text">
         <?php
        printHeader();
        ?>
            <div id="searchboxa">
                <input type="text" id="searchbox" class="text"  onchange="loads()" placeholder="Search People"/>
                <div id="searchresult"></div>
            </div>
        <?php
        echo '</div>';
        ?>
          <br/>  
          <div class="sideBar">
              <div id="dmsg" class="sOption icon-email" onclick="triggermsg()" >
            </div>
            <div id="dimg" class="sOption icon-images" onclick="triggerPhoto()">
            </div>
            <div id="dvideo" class="sOption icon-video-camera" onclick="triggerVideo()">
            </div>
            <div id="dmusic" class="sOption icon-headphone" onclick="triggerMusic()">
            </div>
           
        </div>
          <div id="maincontent" class="maincontent" style="visibility: hidden;">
            
            <noscript>
            <span style="padding: 10px;background-color: #ccdab9;">Please enable JavaScript on your browser to enjoy all the feature of this
                website <b>Or</b> Go to our <a style="text-decoration: none;" href="www.m.cubebeans.com">mobile site</a>!</span><br/><br/>
            </noscript>
              <div id="feed">
                  <div id="feedsegment" style="background-color: #FCFBF7;" class="feedsegment commoncontent">
                      
                </div>
                  
              </div>
            <div id="extra">
                <div id="files">
            <div id="upload" class="commoncontent">
                <form action="#" method="post" >
                    <textarea id="update" name="update" style="max-width: 100%;"></textarea><br/>
                    
                    <button id="updatebutton">Post</button>
                    
                </form>
            </div>
                
            <div id="media" class="commoncontent">
                media
            </div>
                
            </div>
            <div id="online" class="commoncontent">
                online
            </div>
            
            </div>
        </div>
          <div id="frames">
              <div id="msgframe" class="frame" style="visibility: hidden;margin-top: -490px;">
                  
                      <div class="frametitle">Messages</div>
                  <hr/>
                  <div id="msgframebody">
                      <img class="loading" src="site/img/icons/loading.gif" width="66" height="17" alt="Loading"/> 
                  </div>
              </div>
              <div id="photoframe" class="frame" style="visibility: hidden;margin-top: -440px;">
                  <div class="frametitle">
                      Photos
                  </div>
                  <hr/>
                  <div style="padding: 10px;">
                      No Data Available.
                  </div>
              </div>
              <div id="videoframe" class="frame" style="visibility: hidden;margin-top: -370px;">
                   <div class="frametitle">
                      Videos
                  </div>
                  <hr/>
                  <div style="padding: 10px;">
                      No Data Available.
                  </div>
              </div>
              <div id="musicframe" class="frame" style="visibility: hidden;margin-top: -300px;">
                   <div class="frametitle">
                      Musics
                  </div>
                  <hr/>
                   <div style="padding: 10px;">
                      No Data Available.
                  </div>
              </div>
          </div>
        <?php
        printFooter();
        $userdb->close();
        //terminates
        $site->close();
        ?>
    </body>
</html>
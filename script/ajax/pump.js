

/**
 * return active x
 * @returns {ActiveXObject|XMLHttpRequest}
 */
var x=(function (){
    

function sendfeedRequest(xhr){
    if (xhr){
        xhr.open("GET","script/php/delivery.php",true);
        xhr.onreadystatechange = function(){handleResponsefeed(xhr);};
        xhr.send(null);
    }
};

function handleResponsefeed(xhr){
    if (xhr.readyState === 4 && xhr.status === 200){
        var pa = xhr.responseText;
        var responseOutput = document.getElementById("feed");
        responseOutput.innerHTML = pa;
    }
};

//align();
    /**
     * Aligns the window
     * @returns {undefined}
     */
function align(){
    gWidth=window.outerWidth;
    gHeight=window.outerHeight;
    contentheight=gHeight*0.8;
    document.getElementById("maincontent").style.height=contentheight+"px";
    var online=document.getElementById("online");
    var upload=document.getElementById("upload");
    var media=document.getElementById("media");
    var frame=document.getElementById("frames");
    var files=document.getElementById("files");
    var update= document.getElementById("update");
    var ubutton=document.getElementById("updatebutton");
    online.style.height=(contentheight/10)*8+"px";
    media.style.height=(contentheight/10)*5+"px";
    frame.style.margin_left="40px";
    files.style.width=(gWidth/10)*3.6+"px";
    update.style.width=(gWidth/10)*2.8+"px";
    update.style.height=(contentheight/13)+"px";
    ubutton.style.width=(gWidth/10)*0.4+"px";
    ubutton.style.height=(contentheight/20)+"px";
    document.getElementById("maincontent").style.visibility="visible";

};
/**
 * tigger online users fetching
 * @param x ajax object
 * @returns {undefined}
 */
    function online(x){
         if (x){
            x.open("GET","script/php/online.php",true);
            x.onreadystatechange = function(){handleResponseonline(x);};
            x.send(null);
        }
    };

    function handleResponseonline(x){
        if (x.readyState === 4 && x.status === 200){
        var pa = x.responseText;
        var responseOutput = document.getElementById("online");
        responseOutput.innerHTML = pa;
    }
    };

    function profiler(x){
        if (x){
            x.open("GET","script/php/profile.php",true);
            x.onreadystatechange = function(){handleResponseprofiler(x);};
            x.send(null);
        }
    };
    function handleResponseprofiler(x){
        if (x.readyState === 4 && x.status === 200){
        var pa = x.responseText;
        var responseOutput = document.getElementById("profile");
        responseOutput.innerHTML = pa;
    }
    };
    function media(x){
        if (x){
            x.open("GET","script/php/media.php",true);
            x.onreadystatechange = function(){handleResponsemedia(x);};
            x.send(null);
        }
    };
    function handleResponsemedia(x){
        if (x.readyState === 4 && x.status === 200){
        var pa = x.responseText;
        var responseOutput = document.getElementById("media");
        responseOutput.innerHTML = pa;
    }
    };
   

    
    window.onload=function onscreen(){
              //  var r = document.getElementById("feedsegment");
               // r.innerHTML = "We are fetching your data";
                align();
                sendfeedRequest(createobj());
                media(createobj());
                online(createobj());
              //  message(createobj());
              profiler(createobj());


            };
            
            //starts background mice

 })();
 
    function sort(o){
        g=Math.floor(Math.random()*5+1);
        o.src="site/file/pic"+g+".jpg";
        w=(window.outerWidth/10)*3.5-15;
        h=((window.outerHeight-160)/10)*5-10;
        o.style.width=w+"px";
        o.style.height=h+"px";
    };
    
    function visible(od,cmd){
       if(!cmd)
       {
           od.style.visibility="visible";
       }
       else{
            var o=document.getElementById(cmd);
            o.style.visibility="visible";
       }
        

    };
    function hide(od,cmd){
        if(!cmd)
        {
            od.style.visibility="hidden";
        }
        else{
            var o=document.getElementById(cmd);
            o.style.visibility="hidden";
        }
    };
    
 var worker;
function loads(){
   
    var vad=document.getElementById("searchbox");
    if(vad.value!==""){
        id=1;
        worker.postMessage(id+"."+vad.value);
        worker.onmessage=function(event){
            var re=document.getElementById("searchresult");
            re.innerHTML=event.data;
            re.style.visibility="visible";
            //worker.postMessage();
        };
    }
    else{
        var re=document.getElementById("searchresult");
        re.innerHTML="";
        re.style.visibility="hidden";
    }
};
function startw()
{
    worker=new Worker("search-worker.js?"+temp_str);
}
 function stopw(){
     worker.terminate();
 };
 

  
 /**
  * rgenerate and eturns a random string
  * @param {type} len length of generated string
  * @returns {String|randstr.ch} string returned
  */
 randstr(3);
 function randstr(len){
     var ch = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',str="";
     for(i=0;i<len;i++){
         str=str+ch[Math.floor(Math.random()*ch.length+1)];
     }
      temp_str=str;
 };
 startw();
 
function createobj(){
try { return new XMLHttpRequest();} catch(e) {}try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch (e) {} try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch (e) {}try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {}try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {}alert("XMLHttpRequest not supported");return null;
};
 function tiggerProfile(){
     try{
    // x.media(createobj());
        var s=document.getElementById("menu");
        if(s.style.visibility.toString()==="visible")
            s.style.visibility="hidden";
        else
            s.style.visibility="visible";
     }catch(e){ DumpException(e);}



    };
function DumpException(o)
{
        
    if ( window.console && window.console.log ) {
      console.log(o);
    }
 }
/**** msg pump starts        *****/
/**
 * trigger messaging
 * @returns {undefined}
 */
function triggermsg(){
        var msgframe=document.getElementById("msgframe");
        if(msgframe===null)
            return ;
        if(msgframe.style.visibility.toString()==="visible"){
            msgframe.style.visibility="hidden";
        }
        else{
             msgpump(createobj());
             hideMenus();
             msgframe.style.visibility="visible";
        }


    };
function msgpump(xhr){


    if (xhr){
        xhr.open("GET","script/php/msg.php",true);
        xhr.onreadystatechange = function(){handleResponsemsgpump(xhr);};
        xhr.send(null);
    }
};

function handleResponsemsgpump(xhr){
    if (xhr.readyState === 4 && xhr.status === 200) {
        var pa = xhr.responseText;
        var responseOutput = document.getElementById("msgframebody");
        responseOutput.innerHTML = pa;
    }
};

function manipulatemsg(p,a){
    if(a.keyCode===13){
       var s=document.getElementById("msgbind");
        s.innerHTML=s.innerHTML+"<br/>"+p.value;
        p.value=null;
    }
};

var heart=new Object();
heart.msgexe=function(a){
    
};

//Temporary Dialog's
function triggerPhoto(){
    var ele=document.getElementById("photoframe");
    if(ele===null)
        return ;
    if(ele.style.visibility.toString()==="visible")
        ele.style.visibility="hidden";
    else{
        hideMenus();
        ele.style.visibility="visible";
    }
};
function triggerVideo(){
    var ele=document.getElementById("videoframe");
    if(ele===null)
        return ;
    if(ele.style.visibility.toString()==="visible")
        ele.style.visibility="hidden";
    else{
        hideMenus();
        ele.style.visibility="visible";
    }
    
};
function triggerMusic(){
    var ele=document.getElementById("musicframe");
    if(ele===null)
        return ;
    if(ele.style.visibility.toString()==="visible")
        ele.style.visibility="hidden";
    else{
        hideMenus();
        ele.style.visibility="visible";
    }
    
};

function hideMenus(){
    hidePopUp("musicframe");
    hidePopUp("videoframe");
    hidePopUp("photoframe");
    hidePopUp("msgframe");
}
function hidePopUp(name){
    var ele=document.getElementById(name);
    if(ele!==null&&ele.style.visibility.toString()==="visible")
        ele.style.visibility="hidden";
};
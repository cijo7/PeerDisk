
load();
function load()
{
    
    onmessage=function(event){
        var dat=event.data;
        var a= new Array();
        a=dat.split(".",2);
        switch (a[0])
        {
            case '1':
                sendsearch(createobj(),a[1]);
                break;
        }
                
    };
}

function createobj()
{
try { return new XMLHttpRequest(); } catch(e) {}
try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch (e) {}
try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch (e) {}
try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) {}
try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {}
alert("XMLHttpRequest not supported");
return null;
}

/**
 * sends ajax request
 * @returns {undefined}
 */
function sendsearch(xhr,event)
{


    if (xhr)
    {
        xhr.open("POST","script/php/searchpeople.php",true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function(){handleResponsesearch(xhr);};
        xhr.send("we="+event);
    }
}


function handleResponsesearch(xhr)
{
    if (xhr.readyState == 4 && xhr.status == 200)
    {
        
        var result = xhr.responseText;
        postMessage(result);
    }
    
}

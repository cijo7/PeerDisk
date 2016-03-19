

function displayHelp(n)
{
    var s=document.getElementById("q"+n);
    if(n==1)
    {
        s.innerHTML="You will Get Fast Acess To Your Account.";
        s.style.visibility="visible";
    }
    if(n==2)
    {
        s.innerHTML='Birthdate Allow Us To Confirm That You Are Old Enough To Be A Member. <a href="tos.php#birthdate">Learn More</a>';
        s.style.visibility="visible";
    }
    document.getElementById("c"+n).style.visibility="visible";
}
function removeHelp(n)
{
   
        
        var s=document.getElementById("q"+n);
        s.innerHTML="";
        s.style.visibility="hidden";
   document.getElementById("c"+n).style.visibility="hidden";
}



function helpError(obj)
{
    
    var ide=obj.id;
    var tValue=obj.value;
    var text=obj.getAttribute("data-signup-hint");
    var doc=document.getElementById(ide+"h");
    var img=document.getElementById("p"+ide);
    var ok;
    if(ide=="firstName"||ide=="lastName")
    {
        for(i=0;i<=9;i++)
        {
            n=tValue.search(i);
            if(n>=0)
            {
                break;
            }
        }
        if(n>=0)
        {
            
            doc.innerHTML=text;
            
            img.style.visibility="visible";
            doc.style.visibility="visible";
            
        }
        else
        {
            ok=true;
        }
    }
    else if(ide=="regEmail")
    {
    
        if(tValue.search("@")<=0 || tValue.search("\\.")<0 || tValue.search("\\.")+1==tValue.length)
        {
            doc.innerHTML=text;
            doc.style.visibility="visible";
            img.style.visibility="visible";
        }
        else
        {
            ok=true;
        }
    }
    else if(ide=="regPassword")
    {
       
        if(tValue.length<8)
        {
            doc.innerHTML=text;
            doc.style.visibility="visible";
            img.style.visibility="visible";
        }
        else
        {
            ok=true;
        }
    }
    if(ok)
    {
        doc.innerHTML="";
        doc.style.visibility="hidden";
        img.style.visibility="hidden";
    }
   
}



function helpPop(obj)
{
    
    var ide=obj.id;
    var text=obj.getAttribute("data-signup-help");
    var doc=document.getElementById(ide+"h");
    var img=document.getElementById("p"+ide);
    doc.innerHTML=text;
    img.style.visibility="visible";
    doc.style.visibility="visible";
     
         
}




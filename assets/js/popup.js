document.write("<div id=\"popup\">" +
					"<table width=\"100%\" bgcolor=\"navy\"><tr><td id = \"_title\">__NO_TITLE__</td><td align=\"right\" style=\"color:white;cursor:pointer;width:20px;\" onClick=\"hidePopup();\"><b> &#215;&nbsp;</b></td></tr></table>"+
					"<div id=\"content\" style=\"overflow:auto;width:99.5%;height:280px;background-color:#f9f9f9;\"></div>" +
				"</div>");

var isDown = false;
var px=-1; 
var py = -1;
function showPopup(title,txt){
	  document.getElementById("_title").innerHTML = "<b>"+title+"</b>";
      var content = document.getElementById("content");
      var popUp = document.getElementById("popup");
      content.innerHTML = txt;
      popUp.style.visibility = "visible"; 
      //el.style.display = "block";
	}
	
	
	
	function hidePopup(){
		
 		var popUp = document.getElementById("popup");
 		popUp.style.visibility = "hidden";
	}
	function ajaxCall(url,callback,title){
		cb = callback;
		showWait();
		var xmlHttp;
		try
  		{
  			// Firefox, Opera 8.0+, Safari
  			xmlHttp=new XMLHttpRequest();
  		}
		catch (e)
  		{
  			// Internet Explorer
  			try
    		{
    			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    		}
  			catch (e)
    		{
    			try
      			{
      				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      			}
    			catch (e)
      			{
      				alert("Your browser does not support AJAX!");
      				return false;
      			}
    		}
  		}
  		xmlHttp.onreadystatechange=function()
    	{
    		if(xmlHttp.readyState==4)
      		{
    			showMessage(xmlHttp.responseText,title);
      		}
    	}
  		xmlHttp.open("GET",url,true);
  		xmlHttp.send(null);
		
	}
	
	
	function showMessage(txt,title){
		if(!title)
			title = "Message";
		if(cb != null && cb.length>0)
			showPopup(title,"<table width=\"100%\" align=\"center\" ><tr><td align=\"center\">"+txt+"</td></tr><tr><td align=\"center\"><input type=\"button\" value=\" OK \" onclick=\"hidePopup();goCallback();\"></td></tr></table>");
		else
			showPopup(title,"<table width=\"100%\" align=\"center\" ><tr><td align=\"center\">"+txt+"</td></tr></table>");
		
	}
	function showWait(){
		showPopup("Wait...","<table width=\"100%\" align=\"center\"><tr><td align=\"center\"><img src=\"./images/loading.gif\" height=\"240px\" /></td></tr></table>");
	}
	function goCallback(){
		if(cb != null && cb.length>0)
			document.location.href = cb;
	}
	
	function checkGroup(){
		if(document.getElementById('trpp').checked)
		{
			document.getElementById('trpp').style.display='';
			document.getElementById('trsp').style.display='';
			document.getElementById('trv').style.display='';
		}else{
			document.getElementById('trpp').style.display='none';
			document.getElementById('trsp').style.display='none';
			document.getElementById('trv').style.display='none';
		}
	}
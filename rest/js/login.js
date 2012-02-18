$(document).ready(function(){
	init();
});


function init(){

    var userid = getCookie("combined");
    var at = getCookie("combined_at");
    if (typeof(userid) != "undefined"){
        $.post('check_user_exists.php',{userid: userid},function(data){
            if (data == "true"){
                window.location.href = 'http://combined-effect.com/CE_core/rest/user_categories_display.php?userid=' + userid;
            }
            else {
                window.location.href = 'http://combined-effect.com/CE_core/install/index.php?user_id=' + userid + "&at=" + at;
            }
        });
    }
    else{
        if (window.location.hash.length > 0){
            $(".connect").html("עוד שניה...");
            doLogin();
        }
        else{
            $(".connect").click(function(){
                doLogin();
            });
        }

    }
}

function doLogin(){
	var appID = "119906071429474";

	if (window.location.hash.length == 0) {
        var path = 'https://www.facebook.com/dialog/oauth?';
		var url = "https://www.facebook.com/dialog/oauth?scope=user_likes&client_id=" + appID +"&redirect_uri=http://combined-effect.com/CE_core/rest/login.php&response_type=token";
   		parent.window.location = url;

     }
     else {
        window.accessToken = window.location.hash.substring(1);
		var path = "https://graph.facebook.com/me?";
		var queryParams = [accessToken, 'callback=setCombinedCookie'];
		var query = queryParams.join('&');
		var url = path + query;

   		var script = document.createElement('script');
		script.src = url;
		document.body.appendChild(script);
     }
}

      function setCombinedCookie(){
           $url = "https://graph.facebook.com/me?" + window.accessToken;
           $.get($url,function(data){
              var server = "<?php echo $_SERVER['SERVER_NAME']?>";

              window.userid = data.id;
              setCookie("combined",window.userid,100);
              setCookie("combined_at", window.accessToken,1);
              window.location = "http://images.fxp.co.il/419";
           },"json");
      }

function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name){
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++){
  		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  		x=x.replace(/^\s+|\s+$/g,"");
  		if (x==c_name){
    		return unescape(y);
    	}
  	}
}
$(document).ready(function(){
	init();
});

function init(){
    //Main
    $login = '<div style="">כניסה</div>';
	$login = '<iframe id="combined" name="combined" height="110" width=468 frameborder="0" src="http://combined-effect.com/CE_core/rest/login.php">';
	if ($("#fb-root").length == 0){
		
	}
	window.counter = 0;

	$("#combined-effect").append($login);

    //Favorites
    $search_string = window.location.search;
    $search_arr = $search_string.split("=");
    $fid = $search_arr['1'];
    $action = "check";
    $check_categories_url = 'http://combined-effect.com/CE_core/rest/add_to_favorites.php?action=' + $action + '&fid=' + $fid;
    $check_categories_iframe = '<iframe height="25" frameborder="0" src="'+$check_categories_url+'"></iframe>';
    $(".ce_control").html($check_categories_iframe);

    $(".ce_control").click(function(){
        $action = $(this).attr('action');
        $fid = $(this).attr('fid');

        $url = 'http://combined-effect.com/CE_core/rest/add_to_favorites.php?action=' + $action + '&fid=' + $fid;
        //$action_script = '<script type="text/javascript" src="http://combined-effect.com/CE_core/rest/js/add_favorite_callback.js"></script>';
        $iframe = '<iframe frameborder="0" src="'+$url+'"></iframe>';
        //setTimeout(responseCallback,5000);
        //$action_script +
        $(this).html($iframe);
    });

}

function fbInit(){
	window.fbAsyncInit = function() {
    FB.init({
      appId      : '311338642243201', // App ID
      //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
    // Additional iniאtialization code here
  };

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));
   
   FB.getLoginStatus(function(response) {
	  if (response.status === 'connected') {
	    // the user is logged in and connected to your
	    // app, and response.authResponse supplies
	    // the user's ID, a valid access token, a signed
	    // request, and the time the access token 
	    // and signed request each expire
	    var uid = response.authResponse.userID;
	    var accessToken = response.authResponse.accessToken;
	    //alert("connected");
	  } else if (response.status === 'not_authorized') {
	    // the user is logged in to Facebook, 
	    //but not connected to the app
	    //alert("not connected");
	  } else {
	    // the user isn't even logged in to Facebook.
	  }
	});
	
	
}

function responseCallback(){
    alert("in response callback");
    $status = getCookie('combined_atf_callback');
    alert($status);
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

function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}


<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl" lang="he">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta id="e_vb_meta_bburl" name="vb_meta_bburl" content="http://www.fxp.co.il" />
		<meta http-equiv="X-UA-Compatible" content="IE=100"/>
		<style type="text/css" id="vbulletin_css">
			@import url("http://images.fxp.co.il/css_static_main/main_css.css");
			body {
				min-width: 468px;
			}
			.main{
				width: 468px;
			}
			
			td a{
				height: 11px;
				padding: 4px 10px 12px;
				overflow: hidden;
				display: block;
				width: 95px;
				border-left:1px solid #C4C4C4;
				border-bottom:1px solid #C4C4C4;
				border-right:1px solid #FFFFFF;
				border-top:1px solid #FFFFFF;
			}
            .logout{
                padding: 4px;
                font-weight: 700;
                color: grey;
                cursor: pointer;
                background-color: #D7FFCE;

            }
            .entryLink{
                float: left;
            }
            .removeForum{
                text-align: center;
                font-size: 8px;
                font-family: arial;
                padding: 1px;
                margin-top: 2px;
                margin-top: -1px;
                position: absolute;
                text-align: center;
                margin-right: 100px;
                cursor: pointer;
            }
		</style>
		<link rel="stylesheet" type="text/css" href="http://www.images.fxp.co.il/css_static/forumhome-rollup.css" />
		<link rel="stylesheet" type="text/css" href="http://images.fxp.co.il/css_static_main/tfooter.css" />
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="http://connect.facebook.net/en_US/all.js"></script>
        <script type="text/javascript">
            function setCategoriesCookie($categories){
                $data = JSON.stringify(object);
                if ($.cookie('combined_userforums') != null){
                    //A cookie exists, so update it
                    $data = JSON.parse($.cookie('combined_userforums'));
                    $cookie_categories = $data.user_categories;
                    $data.categories = $categories;
                    $new_cookie_categories = JSON.stringify($data);
                    $.cookie('combined_userforums',$new_cookie_categories);
                    return $new_cookie_categories;

                }
                else{
                    //No cookie exists, create it.
                    $data = {};
                    $data.categories = $categories;
                    $stringified = JSON.stringify($data);
                    $.cookie('combined_test_data',$stringified, { path: '/' });
                }
            }


            // handle a session response from any of the auth related calls

        </script>

 	</head>
	<body>
		<div class="main">
			<?php 
				include('loader.php');
				//getUserCategories($_GET['userid']);
			?>
			<table>
				<?php getCombinedCategories($_GET['userid']); ?>
			</table>	
		</div>
        <div id="fb-root">
        </div>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '119906071429474', // App ID
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });

            // Additional initialization code here
          };

          // Load the SDK Asynchronously
          (function(d){
             var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = "//connect.facebook.net/en_US/all.js";
             d.getElementsByTagName('head')[0].appendChild(js);
           }(document));
        </script>
        <script type="text/javascript">
            window.userid = '<?php echo $_GET['userid'];?>';
            $(document).ready(function(){
                //FB.init({ apiKey: '119906071429474' });
                $(".logout").click(function(){
                    FB.getLoginStatus(function(response) {
                      if (response.status === 'connected') {
                        // the user is logged in and connected to your
                        // app, and response.authResponse supplies
                        // the user's ID, a valid access token, a signed
                        // request, and the time the access token
                        // and signed request each expire
                        var uid = response.authResponse.userID;
                        var accessToken = response.authResponse.accessToken;

                        FB.logout(function(){
                            deleteCookie("combined");
                            deleteCookie("combined_at");
                            window.location.href = "http://combined-effect.com/fxp/rest/login.php";
                        });
                      } else if (response.status === 'not_authorized') {
                        // the user is logged in to Facebook,
                        //but not connected to the app
                          //window.location.href = "http://combined-effect.com/fxp/rest/login.php";
                      } else {
                        // the user isn't even logged in to Facebook.
                          //window.location.href = "http://combined-effect.com/fxp/rest/login.php";
                      }
                     });

				});

                var user_forums_cookie = JSON.parse(getCookie('combined_userforums'));
                window.user_forums = user_forums_cookie.user_forums;


                $(".removeForum").click(function(){

                    $fid = $(this).attr('fid');
                    $remove = [];
                    $remain = [];

                    for ($entry in window.user_forums){
                        if ($entry == $fid){
                            $remove.push($entry);
                        }
                        else{
                            $remain.push($entry);
                        }
                    }

                    window.user_forums = $remain;
                    setCategoriesCookie(window.user_forums);
                    console.log(window.user_forums);
                    $button = $(this);
                    $clone = $(this).parent().clone(true,true);
                    $clone.html('');

                    $.post("removeForum.php", {user_id:window.userid,fid:$fid},function(data){
                        window.location.reload();
                    });
                });
			});

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
            function deleteCookie( cname ) {
                setCookie(cname,"",-1);
            }
            function logoutWithResponse(response) {
                alert("It's happening");
                alert(response);
                console.log(response);
                // if we dont have a session (which means the user has been logged out, redirect the user)
                if (!response.session) {
                    console.log("problem");
                }
                else{
                    alert(response);
                    console.log(reponse);
                    FB.logout(response);
                }
                //if we do have a non-null response.session, call FB.logout(),
                //the JS method will log the user out
                //of Facebook and remove any authorization cookies
                //FB.logout(logoutWithResponse);

            }
		</script>
        <!-- pull down Facebook's Javascript SDK -->

		<div class="controls">
			<div class="logout">התנתק</div>
		</div>
	</body>
</html>


<?php


function getUserCategories($user_id){
	global $mysqli;
    $_SESSION['user_id'] = $user_id;
    $cookie_user_forums = getCreateCombinedCookie($user_id);
    $user_forums = array();

	$result = $mysqli->query('select distinct * from users_forums_relations where user_id='.intval($user_id). " AND manual=1 ORDER BY score DESC LIMIT 12");
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {

            $forumid = $row["forum_id"];
			$score = $row["score"];
	  		if ($result2 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
                  if ($result2->num_rows > 0){
                    $object= $result2->fetch_object();
                    $forum_name = $object->forum_name;

                }else{
                    $result2 = $mysqli->query("SELECT * FROM forums_list WHERE catid=$forumid");
                    $object= $result2->fetch_object();
                    $forum_name = $object->category_name;
                }
                $user_forums[$forumid] = $forum_name;

	  			//$result3 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$catid");
                //$object2= $result3->fetch_object();
	  			//$category_name= $object2->forum_name;
	  //|| <a href=\"http://www.fxp.co.il/forumdisplay.php?f=$catid\" target=\"_blank\">$category_name</a> <b>[$score]</b>
	  			echo "<br><a href=\"http://www.fxp.co.il/forumdisplay.php?f=$forumid\" target=\"_blank\">$forum_name</a><br>";
	  		}
		}

        if(sizeof($cookie_user_forums) < sizeof($user_forums)){
            $cookie['user_forums'] = $user_forums;
            setcookie('combined_userforums',json_encode($cookie));
        }
	}

}

function getCombinedCategories($user_id){
	global $mysqli;
    $cookie_user_forums = getCreateCombinedCookie($user_id);
    $cookie_user_forums = getCreateCombinedCookie($user_id);

    $user_forums = array();

	echo '<tr>';
	$rows = 0;
	$result = $mysqli->query('select distinct * from users_forums_relations where user_id='.intval($user_id). " AND manual!=-1 ORDER BY score DESC LIMIT 12");
	if ($result->num_rows > 0) {
        //if length of result from db is bigger than length of cookie, refresh cookie.
		while ($row = $result->fetch_assoc()) {
			if ($rows%4 == 0){
				echo '</tr><tr>';
			}	
			$forumid = $row["forum_id"];
			$score = $row["score"];
			
			if ($result2 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$forumid")) {
		    	$object= $result2->fetch_object();
				$forum_name = $object->forum_name;
                $user_forums[$forumid] = $forum_name;
			  	$catid = $object->catid;
				$result3 = $mysqli->query("SELECT * FROM forums_list WHERE forumid=$catid");
			  	$object2= $result3->fetch_object();
			  	$category_name= $object2->forum_name;
			  	echo "<td fid='".$forumid."'><span class='entryLink'><a href=\"http://www.fxp.co.il/forumdisplay.php?f=$forumid\" target=\"_blank\">$forum_name</a></span><span fid='".$forumid."' class='removeForum'>הסר</span></td>";
			}
			$rows++;
		}
        if(sizeof($cookie_user_forums) != sizeof($user_forums)){
            $cookie['user_forums'] = $user_forums;
            setcookie('combined_userforums',json_encode($cookie));
        }
        echo '<script type="text/javascript">window.user_categories=JSON.parse("'.json_encode($user_forums).'");</script>';
	}
	echo '</tr>';
}

function getCreateCombinedCookie($user_id){
    $cookie_data = $_COOKIE['combined_userforums'];
    if (!is_null($cookie_data)){
        $cookie = json_decode($_COOKIE['combined_userforums']);
        if (!is_null($cookie['user_forums'])){
            $user_forums = $cookie['user_forums'];
        }
        else{
            $cookie['user_id'] = $user_id;
            $user_forums = array();
            $cookie['user_forums'] = $user_forums;
            setcookie('combined_userforums',json_encode($cookie));
        }
    }
    else{
        $cookie['user_id'] = $user_id;
        $user_forums = array();
        $cookie['user_forums'] = $user_forums;
        setcookie('combined_userforums',json_encode($cookie));
    }

    return $user_forums;
}

?>
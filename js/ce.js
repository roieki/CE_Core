 window.likecount = 0;
 window.categories = new Array();
 window.selected = new Array();
 window.data = new Array();
 window.search_terms = new Array();
 window.links = new Array();
 window.accessToken = '';
 window.forums = new Array();
 window.fbAsyncInit = function() {
	FB.init({
	  appId      : '119906071429474', // App ID
	  channelUrl : '//www.combined-effect/poc/channel.html', // Channel File
	  status     : true, // check login status
	  cookie     : true, // enable cookies to allow the server to access the session
	  xfbml      : true  // parse XFBML
	});

    // Additional initialization code here
    FB.Event.subscribe('auth.login', function(response) {

	});
 
	FB.Event.subscribe('auth.logout', function(response) {
	
	});
	
	FB.getLoginStatus(function(response) {
	  if (response.status === 'connected') {
	    // the user is logged in and connected to your
	    // app, and response.authResponse supplies
	    // the user's ID, a valid access token, a signed
	    // request, and the time the access token 
	    // and signed request each expire
	    var uid = response.authResponse.userID;
	    window.fbuid = uid;
	    window.accessToken = response.authResponse.accessToken;
	    saveUser(accessToken);
	    //processUserLikes(accessToken);
	    //initCategoriesSelector();
	    
	  } else if (response.status === 'not_authorized') {
	    // the user is logged in to Facebook, 
	    //but not connected to the app
	  } else {
	    // the user isn't even logged in to Facebook.
	  }
	 });
  };

  // Load the SDK Asynchronously
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
}(document));


function saveUser(accessToken){
	$.get('https://graph.facebook.com/me' + '?access_token=' + window.accessToken, function(data){
   		$.post('controller.php', {action: 'set_user', user: data, fbuid: window.fbuid},function(gdata){
		  		
		  		$status = gdata.status; 
		  		log($status);
		  		if ($status == 'exists'){
		  			processExistingData(gdata.data);
		  			
		  		}
		  		else{
		  			processUserLikes(accessToken);
	    			initCategoriesSelector();
		  		}
		  	},"json");
   	},"json");
}

function processExistingData($data){
	for (index in $data){
		$like = $data[index];
		window.data[$like.id] = $data[index];
		
   		if (window.categories[$like.category] == undefined){
   			window.categories[$like.category] = 1;
   			$(".categories").append('<option>' + $like.category + '</option>');	
   		}
   		
	  	$entry = "<div class='like entry' id='"+$like.id+"' eid='"+$like.id+"' value='"+$like.name+"'><span class='like_name'>";
		$entry += $like.name;
		$entry += "</span><span class='like_category'></span><span class='like_picture'><img height=30 width=30 src=''></span></div>";
		$(".likes").append($entry);
		
		$bing_data = $data[index].bing_data.data;
		$links = new Array();
		for (entry in $bing_data){
			
			$item = new Object();
			$item.title = $bing_data[entry].Title;
			$item.url = $bing_data[entry].Url;
			$links.push($item);
		} 
		window.links[$like.id] = $links;
	}
	
	
	$(".like.entry").each(function(){
		$objectid = $(this).attr('id');
		$objectid = $(this).attr('id');
		$additional = window.data[$objectid];
		$links = window.links[$objectid];
	
		if ($links.length == 1){
			$(this).fadeOut().remove();
		}
		$("#" + $objectid).children('.like_picture').children('img').attr('src',$additional.picture);
		$("#" + $objectid).children('.like_category').html($additional.category);
		$("#" + $objectid).attr('type',$additional.category);
		$("#" + $objectid).data('fb',$additional);
		$("#" + $objectid).data('links',$links);
		
		initEntry($("#" + $objectid));	
	});
	
}

function processUserLikes(accessToken){
   	FB.api('/me/likes', function(response) {
		  $likes = response.data;
		  window.likecount = response.data.length;
		  $("#counter").html(window.likecount);
		  for (index in response.data){
		  	$like = $likes[index];
		  	$additional = getAdditional($like);
		  }
	});
	$(".navbutton.back").click(function(){
		addAdditional();
	});
}

function getAdditional($like){
	$objectid = $like.id;
	
	$found = false;
	$.post('controller.php',{action: 'getlike', likeid: $objectid, fbuid: window.fbuid , like: $like},function(data){
		$found = data.found;
		if ($found){
			$additional = data.data;
			$objectid = data.id;
			
			window.data[$objectid] = $additional;
			if (window.categories[$additional.category] == undefined){
	   			window.categories[$additional.category] = 1;
	   			$(".categories").append('<option>' + $additional.category + '</option>');	
	   		}
	   		$entry = "<div class='like entry' id='"+$like.id+"' eid='"+$like.id+"' value='"+$like.name+"'><span class='like_name'>";
			$entry += $like.name;
			$entry += "</span><span class='like_category'></span><span class='like_picture'><img height=30 width=30 src=''></span></div>";
			$(".likes").append($entry);
			if (data.data.bing_data.data == null) {
				window.links[$objectid] = 'empty';
			}
			else {
				window.links[$objectid] = data.data.bing_data.data;	
			}
			
			
			window.likecount--;
	  		if (window.likecount == 0){
	  			finish();
	  			 //$(".navbutton.back").html("Done");
	  		}
	  		else {
	  			$("#counter").html(window.likecount);
	  		}	
		}
	},"json");
	
	if ($found){
		log("found");
		return;
	} 
	//Need to get it from Facebook
	else{
		//log("pressing on");	
	}
	
	
	$.get('https://graph.facebook.com/' + $objectid + '?access_token=' + window.accessToken, function(gdata){
   		$objectid = $like.id;
   		$additional = gdata;
   		window.data[$objectid] = $additional;
		
   		if (window.categories[$additional.category] == undefined){
   			window.categories[$additional.category] = 1;
   			$(".categories").append('<option>' + $additional.category + '</option>');	
   		}
   		
	  	$entry = "<div class='like entry' id='"+$like.id+"' eid='"+$like.id+"' value='"+$like.name+"'><span class='like_name'>";
		$entry += $like.name;
		$entry += "</span><span class='like_category'></span><span class='like_picture'><img height=30 width=30 src=''></span></div>";
		$(".likes").append($entry);
		
		$.post('controller.php', {action: 'process', like: $like, additional: $additional, fbuid: window.fbuid},function(data){
	  		window.links[$like.id] = data;
	  		window.likecount--;
	  		if (window.likecount == 0){
	  			finish();
	  			 //$(".navbutton.back").html("Done");
	  		}
	  		else {
	  			$("#counter").html(window.likecount);
	  		}
	  	},"json");
   	},"json");
   	
}

function parseLikedata(data){
	log(data);
}

function finish(){
	addAdditional();
}

function addAdditional(){
	
	$(".like.entry").each(function(){
		$objectid = $(this).attr('id');
		$additional = window.data[$objectid];
		$links = window.links[$objectid];
		if ($links == 'empty'){
			$links = new Array;
			$links[0] = 'empty';
		}
		if ($additional != undefined){
			if ($links == undefined){
				$links = new Array;
				$links[0] = 'empty';	
			}
			if ($links[0] == "empty"){
				$(this).fadeOut().remove();
			}
			$("#" + $objectid).children('.like_picture').children('img').attr('src',$additional.picture);
			$("#" + $objectid).children('.like_category').html($additional.category);
			$("#" + $objectid).attr('type',$additional.category);
			$("#" + $objectid).data('fb',$additional);
			$("#" + $objectid).data('links',$links);
			initEntry($("#" + $objectid));
		}		
	});
	$size = $(".like.entry").length;
	$(".navbutton.back").html($size + " Likes found");
}

function initMark(){
	$(".like_property").click(function(){
		$objectid = $(this).parent(".like.entry").attr('id');
		$forum_id = $(this).children(".content").attr('fid');
		window.forums[$objectid] = $(this).children(".content").children("a").html();
		$.post('controller.php',{ action: 'save_forum_relation', like_id: $objectid, forum_id: $forum_id },function(data){
			$(this).addClass('selected');	
		});
	});
}

function initEntry($entry){
   	$entry.click(function(){
   		$entry.toggleClass("selected");
   		$id = $(this).attr("id");
   		
   		if ($id in window.selected){
   			delete(window.selected[$id]);
   			$entry.removeClass('selected');
   			$(".expand").children("#" + $id).remove();
   		}
   		else{
   			$entry.addClass('active');
   			$entry.removeClass('selected');
   			window.selected[$id] = $(this).clone();
   			$entry.removeClass('active');
   			$entry.addClass('selected');
   			$(".expand").append(window.selected[$id]);
   			$id = $(this).attr('id');
		   	$data =  window.data[$id];
			for (index in $data){
	   			if (index != 'picture'){
	   				//$(".entry.active[eid="+ $id+"]" ).append('<span class="like_property"><span class="type" value="'+index+'">' + index + "</span><span class='content'>" + $data[index] + '</span></span>');	
	   			}
	   		}
	   		
	   		$links = window.links[$id];
	   		$.post('controller.php', {action: 'get_fxp_categories', links: $links},function(data){
	 			window.links[$id]  = data;
	 			$links = window.links[$id];
	 			log($links);
	 			$i = 0;
		   		for (index in $links){
		   			$i++;
		   			log($links[index]['cid']);
		   			$html = '<span class="like_property link">';
		   			$html += '<span class="type" value="link">Link</span>';
		   			$html += '<span class="label">'+$links[index]['cid']+'</span>';
		   			//$html += '<span class="content" fid="'+$i+'" cid="'+$i+'"><a target="_blank" href="' + $links[index].url + '">';
		   			$html += '<span class="content" fid="'+$links[index].fid+'" cid="'+$links[index].cid+'"><a target="_blank" href="' + $links[index].url + '">';
		   			$html += $links[index].title + '</a></span></span>';
		   			$(".entry.active[eid="+ $id+"]" ).append($html);	
		   		}
		   		
		   		$(".expand").scrollTop(+$(".entry.active[eid="+ $id+"]" ).height());
	 		},'json');
	   		
	   		
		}
		initMark();
   	});
}



function initCategoriesSelector(){
   	$(".categories").change(function(){
   		$(".entry").show();
   		if ($(this).val() != 'All'){
   			$(".entry").not(".entry[type='"+$(this).val()+"']").hide();	
   		}
   	});	
}

function log($msg){
	console.log($msg);
}


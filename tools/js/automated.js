$(document).ready(function(){
	init();
});


function init(){
	/*$(".yep").hover(function(){
		$(this).css('background-color','black');
		$(this).css('color','white');
	},function(){
		$(this).css('background-color','white');
		$(this).css('color','black');
	});
	$(".nope").hover(function(){
		$(this).css('background-color','black');
		$(this).css('color','white');
	},function(){
		$(this).css('background-color','white');
		$(this).css('color','black');
	});*/
	
	$(".yep").click(function(){
		$likeid = $(this).parent().parent(".category").attr('like_id');
		$category_id = $(this).parent().parent(".category").attr('catid');
		$.post('update.php', {likeid: $likeid, category_id: $category_id, approved: 1});
		$(this).toggleClass('active');
		$(this).siblings().removeClass('active');
	});
	$(".nope").click(function(){
		$likeid = $(this).parent().parent(".category").attr('like_id');
		$category_id = $(this).parent().parent(".category").attr('catid');
		$.post('update.php', {likeid: $likeid, category_id: $category_id, approved: -1});
		$(this).toggleClass('active');
		$(this).siblings().removeClass('active');
	});
	
	
	
	
	$(".done").click(function(){
		$likeid = $(this).parent().attr('lid');
		 $.post('update.php', {likeid: $likeid, category_id: -1, approved: 1},function(){
		 	window.location.reload();
		 });
		 
	});
	
	$(".skip").click(function(){
		$likeid = $(this).parent().attr('lid');
		 $.post('update.php', {likeid: $likeid, category_id: -1, approved: -1},function(){
		 	window.location.reload();
		 });
	});
}

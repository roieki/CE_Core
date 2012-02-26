$(document).ready(function(){
	init_crm();
});


function init_crm(){
	$(".yep").click(function(){
		$likeid = $(this).parent().parent(".category").attr('like_id');
		$forumid = $(this).parent().parent(".category").attr('catid');
		$.post('update.php', {likeid: $likeid, forum_id: $forumid, approved: 1});
		$(this).toggleClass('active');
		$(this).siblings().removeClass('active');
	});
	$(".nope").click(function(){
		$likeid = $(this).parent().parent(".category").attr('like_id');
		$forumid = $(this).parent().parent(".category").attr('catid');
		$.post('update.php', {likeid: $likeid, forum_id: $forumid, approved: -1});
		$(this).toggleClass('active');
		$(this).siblings().removeClass('active');
		
	});
	

	$(".done").click(function(){
		if ($(".manualForumInput").val() != ''){
			$(".manualForumSubmit").click();
		}
		$likeid = $(this).parentsUntil(".like_controls").parent().attr('lid');
		 $.post('update.php', {likeid: $likeid, forum_id: -1, approved: 1},function(){
		 	window.location.reload();
		 });
		 
	});
	
	$(".skip").click(function(){
		$likeid = $(this).parentsUntil(".like_controls").parent().attr('lid');
		 $.post('update.php', {likeid: $likeid, forum_id: -1, approved: -1},function(){
		 	window.location.reload();
		 });
	});
	
	getForums();
	
	$(".manualForumSubmit").one('click',function(){
		//$clone = $(this).parent(".manualForum").clone(true);
		//$clone.children(".manualForumInput").val('');
		//$(this).parent(".manualForum").after($clone);
		$likeid = $(this).parents(".like_controls").attr('lid');
		$forumid = $(this).siblings(".manualForum-id").attr('value');
		$.post('update.php', {likeid: $likeid, forum_id: $forumid, approved: 1});
		$(".done").click();
	});
	$(".entry").one('click',function(){
		$target = $(this).attr('type');
		window.location.href = 'http://combined-effect.com/fxp/crm/index.php?target=' + $target;
		
	});
}
	

function getForums(){
	$.post('../crm/controller.php', { action:'get_forums_list', data: '', uid: ''},function(data){
		window.forum_list = data.forum_list;
		window.forum_index = data.forum_index;
		$(".manualForumInput").autocomplete({
				source: window.forum_list,
				focus: function (event, ui) {
	            }, 
	            select:function (event, ui) {
	                $(".manualForum-id").val(ui.item.id);
	                $(".manualForumInput").val(ui.item.label);
	            },
	            change:function (event, ui) {
	                $(".manualForum-id").val(ui.item.id);
	                $(".manualForumInput").val(ui.item.label);
	            }
			});
	},'json');
}

function rtrim(str){
	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');

}

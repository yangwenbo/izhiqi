// Pseudo code for demonstrating interactions
$(function() {
	///////////////////
	//     global    //
	///////////////////
	//"global": follow button
	$(".follow-btn").click(function(){
		if (!$(this).hasClass("followed")){
			$(this).addClass("followed").html("<s></s><i></i>已关注");
		}
		else {
			$(this).removeClass("followed").html("<s></s><i></i>关注");
		}
	});

	//"global": relevance feedback for pseudo buttons and the real ones
	$(".pseudo-btn").click(function(){
		// var nameValue = $(this).attr("name");
		// $("input[name=" + nameValue + "]").click();
		$(this).prev().click();
	});

	///////////////////
	//   nsb-select  //
	///////////////////
	//"global": customed select component
	$(".nsb-select .drop-trigger").live("click", function(){
		$(this).parent().children(".nsb-sel-results").show();
		$(this).hide();
	});

	$(".nsb-select .nsb-sel-value").keyup(function(){
		$(this).parent().children(".nsb-sel-results").show();
	});

	$(".nsb-select .nsb-sel-results li").live("click", function(){
		var selValue = $(this).text();
		$(this).parent().parent().children(".nsb-sel-value").val(selValue);
		$(this).parent().parent().children(".drop-trigger").show();
		$(this).parent().hide();
	});

	$(document).mouseup(function (e)
	{
		var container = $(".nsb-select:not(.nsb-select-search) .nsb-sel-results");
		var container_search = $(".nsb-select.nsb-select-search");
		if (container.has(e.target).length === 0)
		{
			container.parent().children(".drop-trigger").show();
			container.hide();
		}
		if (container_search.has(e.target).length === 0)
		{
			container_search.children(".nsb-sel-results").hide();
		}
	});

	///////////////////
	//    overlay    //
	///////////////////
	//"overlay": overlay trigger clicked
	$(".over-trigger").click(function(){
		$(".background").show();
		$(".overlay").show();
		$("body").addClass("has_overlay");
		var winHeight = $(window).height();
		$(".overlay").css("height", winHeight);
	});
	//"overlay": reset overlay height when resizing window size
	$(window).resize(function(){
		var winHeight = $(window).height();
		$(".overlay").css("height", winHeight);
	});

	//"overlay": close button clicked
	$(".over-body .close-btn").click(function(){
		$(".background").hide();
		$(".overlay").hide();
		$("body").removeClass("has_overlay");
		$(this).parent().parent().hide();
	});

	///////////////////
	//     header    //
	///////////////////
	//"header": toggle dropdown display state
	$("header .head-user > a").click(function(){
		$("header .head-user .dropdown").toggleClass("open");
	});
	$("header .has-drop").click(function(){
		$(this).parent().children(".dropdown").toggleClass("open");
	});

	///////////////////
	//   dashboard   //
	///////////////////
	//"dashboard": send message
	$(".ctrl .msg").click(function(){
		$(".over-body.new-msg").show();
	});

	///////////////////
	//    profile    //
	///////////////////
	// "profile": add a new experience of work or education
	$(".seg-content.add-exp a").live('click',function(){
		var htmlTree = $(this).parent().parent().html();
		$('<div class="edit-seg">' + htmlTree + '</div>').insertAfter($(this).parent().parent()).fadeOut(0).fadeIn(800);
		$(this).parent().parent().next().css("border-top", "1px dashed #eceef1");
		$(this).parent().parent().next().children(".seg-title").css("display", "none");
		$(this).parent().remove();
	});

	// "profile": show and hidden remark
	$(".position .psn-remark").hover(function(){
		$(this).children(".psn-tip").css("display", "block");
	});
	$(".position .psn-remark").mouseleave(function(){
		$(this).children(".psn-tip").css("display", "none");
	});

	// "edit profile": delete work URL
	$(".del-work").live('click', function(){
		$(this).parent().parent().fadeOut(300, function(){
			$(this).remove();
		});
	});

	// "edit profile": add work URL
	$(".seg-content.add-work a").live('click',function(){
		var htmlTree = '<div class="seg-content clearfix"><div class="work-loc clearfix"><input type="text" placeholder="例如：www.mywebsite.com" name="work[]"><a href="javascript:;" class="del-work"></a><span class="input-tip"></span></div></div>';
		$(htmlTree).insertBefore($(this).parent()).fadeOut(0).fadeIn(800);
	});

	///////////////////
	//    message    //
	///////////////////
	//"message": new message button
	$(".message .compose-msg").click(function(){
		$(".over-body.new-msg").show();
	});
	//"message": view and reply message link
	$(".message .msg-list .open, .message .msg-list .reply").click(function(){
		$(".over-body.msg-cvsatn").show();
	});
	//"message": delete message link
	$(".message .msg-list .delete").click(function(){
		$(".over-body.over-alert").show();
	});

	///////////////////
	//     slide     //
	///////////////////
	//"slide": next buttom click
	$(".slide-next").click(function(){
		var target = $(".slide-content");
		var now = target.children().filter(".visible");
		var next = now.next();
		if (next.length === 0) {
			next = now.siblings().first();
		}
		next.addClass("visible");
		now.removeClass("visible");
		
		var pTarget = $(".slide-pager ul");
		var pNow = pTarget.children().filter(".current");
		var pNext = pNow.next();
		if (pNext.length === 0) {
			pNext = pNow.siblings().first();
		}
		pNext.addClass("current");
		pNow.removeClass("current");
	});

	//"slide": prev buttom click
	$(".slide-prev").click(function(){
		var target = $(".slide-content");
		var now = target.children().filter(".visible");
		var prev = now.prev();
		if (prev.length === 0) {
			prev = now.siblings().last();
		}
		prev.addClass("visible");
		now.removeClass("visible");

		var pTarget = $(".slide-pager ul");
		var pNow = pTarget.children().filter(".current");
		var pPrev = pNow.prev();
		if (pPrev.length === 0) {
			pPrev = pNow.siblings().last();
		}
		pPrev.addClass("current");
		pNow.removeClass("current");
	});

	//"slide": slide pager click (the little white dots)
	$(".slide-pager li").click(function(){
		var order = $(".slide-pager li").index(this);
		$(".slide-pager li").removeClass("current");
		$(this).addClass("current");
		$(".slide-item").removeClass("visible");
		$(".slide-item").eq(order).addClass("visible");
	});
});

//"slide": auto-switch
$(document).ready(function () {
	$(".slide-content").each(function(){
		var len = $(this).attr("timer");
		var target = $(this);
		var pTarget = $(".slide-pager ul");

		setInterval(function(){
			var now = target.children().filter(".visible");
			var next = now.next();
			if (next.length === 0) {
				next = now.siblings().first();
			}
			next.addClass("visible");
			now.removeClass("visible");

			var pNow = pTarget.children().filter(".current");
			var pNext = pNow.next();
			if (pNext.length === 0) {
				pNext = pNow.siblings().first();
			}
			pNext.addClass("current");
			pNow.removeClass("current");
		}, len);
	});
});

// avatar upload preview
function imgPre(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.ava-up-pre').attr('src', e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
}


// browser and function patches
$(function() {
	// numeric 0-9 only input
	$(".num-input").keydown(function(event) {
		// Allow: backspace, delete, tab, escape, and enter
		if ( event.keyCode === 46 || event.keyCode === 8 || event.keyCode === 9 || event.keyCode === 27 || event.keyCode === 13 ||
			// Allow: Ctrl+A
			(event.keyCode === 65 && event.ctrlKey === true) ||
			// Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault();
			}
		}
	});
});

// input placeholder in IE
$(function() {
	var input = document.createElement("input");
	if(('placeholder' in input) === false) {
		$('[placeholder]').focus(function() {
			var i = $(this);
			if(i.val() === i.attr('placeholder')) {
				i.val('').removeClass('placeholder');
				if(i.hasClass('password')) {
					i.removeClass('password');
					this.type='password';
				}
			}
		}).blur(function() {
			var i = $(this);
			if(i.val() === '' || i.val() === i.attr('placeholder')) {
				if(this.type ==='password') {
					i.addClass('password');
					this.type = 'text';
				}
				i.addClass('placeholder').val(i.attr('placeholder'));
			}
		}).blur().parents('form').submit(function() {
			$(this).find('[placeholder]').each(function() {
				var i = $(this);
				if(i.val() === i.attr('placeholder'))
				{
					i.val('');
				}
			});
		});
	}
});

// plugins activeness
// sub functions should be dissociated into specific modules
$(function() {
	//background size
	$(".sl-img, .head-blur, .head-bg").css("background-size", "cover");

	//"blur.js": profile head background image blur effect
	$('.head-bg').blurjs({
		radius: 10,
		cache: true
	});

	// chosen
	$(".chzn-select").chosen();
});

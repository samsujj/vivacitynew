var training= {
	page_info : {}
	, current_id : ""
	, speed : 1000
	, url_base : ""
	, init : function() {
		this.url_base = $('#training_vars').attr('data-page_name');

		$(".lesson_link").click(function( e ) {
			if ( training.current_id == "" ) { // race condition conditional
				if ( !$(this).hasClass("training_lessons_block_active") ) {
					training.current_id = $(this).attr("data-id");
					category_id = $(this).attr("data-category");

					if ( window.history.replaceState ) {
						window.history.replaceState(training.page_info, "Test", training.url_base+"/category/" + category_id + "/" + training.current_id);
					}

					var prevLesson = $('.training_lessons_block_active + .training_lessons_editor');

					$(".training_lessons_editor").slideUp({
						duration: training.speed, 
						done: function(o) {
							if ( o.elem.id.replace(/lesson_/, '') == training.current_id ) {
								$(".training_lessons_block").removeClass("training_lessons_block_active");
								$("#lesson_" + training.current_id + "_link").addClass("training_lessons_block_active");
								$("#lesson_" + training.current_id).css('display','block').html('Loading Lesson...');
								$("#lesson_" + training.current_id).addClass('training_lessons_active');

								$.ajax({
									url: training.url_base+'/category/' + category_id + '/' + training.current_id + '?ai_skin=full_page&get_lesson_id=1',
								}).done(function(data) {
									var lolvid = $('.training_lessons_block_active + .training_lessons_editor video');
									
									if ( lolvid.length > 0 ) {
										var lolBaseName = lolvid.attr('id').replace(/^v__|_html5_api$/g, ''),
											lolvidID = 'aivid_' + lolBaseName;

										if ( lolvidID in window) {
											if ( 'videojs' in window) {
												videojs('v__' + lolBaseName).dispose();
											} else {
												_V_('v__' + lolBaseName).dispose();
											}

											window[lolvidID] = null;
										}
									}

									prevLesson.html('');

									$("#lesson_" + training.current_id).html(data).slideDown(training.speed, function() {
										training.current_id = "";
									});
								});
							}
						}
					});
				}
			}
		});
	}
	, mark_complete : function( unit, next, url ) {
		$('#lesson_' + unit + '_link').prepend('<img src=\"includes/modules/training/images/checkmark.png\" class=\"training_checkmark\" />');
		$('#lesson_' + unit + '_link .training_lock').remove();
		$('#lesson_' + unit + ' .training_mark_complete').remove();
		ajax_get_request(url+'/manage/completes?ai_skin=full_page&mark_complete=' + unit, function(e)
			{
				training.next_lesson(next,url);
			});
		
	}
	, next_lesson: function(next,url)
	{
		if(next != '') {
			category_id = $("#lesson_" + next + "_link").attr("data-category");
			
			if ( window.history.replaceState ) {
				window.history.replaceState(training.page_info, "Test", url+"/category/" + category_id + "/" + next);
			}
			$('#lesson_' + next + ' .training_lessons_lock_message').remove();
			$('#lesson_' + next + ' .training_lessons_lock').show();
			$('#lesson_' + next).removeClass('training_lesson_locked');
			$('#lesson_' + next + '_link').addClass('training_lessons_block_active');
			$('.training_lessons_editor').slideUp(this.speed);
			if(next!=0)
			{
				
				$.ajax({
					url: url +'/category/' + category_id + '/' + next + '?ai_skin=full_page&get_lesson_id=1',
				}).done(function(data) {
					var lolvid = $('.training_lessons_block_active + .training_lessons_editor video');
					
					if ( lolvid.length > 0 ) {
						var lolBaseName = lolvid.attr('id').replace(/^v__|_html5_api$/g, ''),
							lolvidID = 'aivid_' + lolBaseName;
						
						if ( lolvidID in window) {
							if ( 'videojs' in window) {
								videojs('v__' + lolBaseName).dispose();
							} else {
								_V_('v__' + lolBaseName).dispose();
							}
							
							window[lolvidID] = null;
						}
					}
					
					$("#lesson_" + next).html(data).slideDown(training.speed, function() {
						$('#lesson_' + next + '_link .training_lock').remove();
						$('.training_lessons_block').removeClass('training_lessons_block_active');
						$('#lesson_' + next + '_link').addClass('training_lessons_block_active');
						$('.training_lessons_editor').removeClass('training_lessons_active');
						$('#lesson_' + next).addClass('training_lessons_active');
					});
				});
			}
			else
			{
				$('.training_lessons_block').removeClass('training_lessons_block_active');
			}			
		}
	}
	
	, show_question : function( hide_id, show_id)
	{
		$('#'+hide_id).hide();
		$('#'+show_id).show();
	
	}
	
	, process_quiz : function (form)
	{
		 var url = "take_quiz.php";
		 var form_data = $(form).serialize();
		 $.post(url, form_data, function( data, success, xhr ) {
		  	 $('#quiz').hide();
		  	 $('#quiz_results').html(data);
		 });
	}
	
	, close_quiz : function(unit,next,url)
	{
		close_jonbox();
		if(next != 0) training.mark_complete(unit,next,url);
	}
	
	
	
};


//DrewL 2016.12.27
//This line was causing an error in jQuery (on Chrome and possibly others) 
// refix for task 31774
//$.data('training',training);

$(document).ready(function( event ) {
	training.init();
});

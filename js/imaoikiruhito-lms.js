(function($) {
	$(document).ready(function(){
		
		//----------------------------------------------------------------------
		// common
		//----------------------------------------------------------------------
		// 対象のセレクトボックスの内容一覧を取得
		var dataListLeft = {};
		var dataListLeftOrg = {};
		selectObject = $('#target-options-left').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListLeft[targetObject.val()] = targetObject.text();
			dataListLeftOrg[targetObject.val()] = targetObject.text();
		}
		
		var dataListRight = {};
		var dataListRightOrg = {};
		selectObject = $('#target-options-right').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListRight[targetObject.val()] = targetObject.text();
			dataListRightOrg[targetObject.val()] = targetObject.text();
		}
		
		// テキストエリアが変更された時
		$('#target-text-left').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-left > option').remove();
				
				for ( var key in dataListLeft ) {
					text = dataListLeft[key];
					$('#target-options-left').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-left > option').remove();
				for ( var key in dataListLeft ) {
					text = dataListLeft[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-left').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('#target-text-right').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-right > option').remove();
				for ( var key in dataListRight ) {
					text = dataListRight[key];
					$('#target-options-right').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-right > option').remove();
				for ( var key in dataListRight ) {
					text = dataListRight[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-right').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		var move = function(_this, target) {
			$('select[name=' + _this + '] option:selected').each(function() {
			$('select[name=' + target + ']').append($(this).clone());
			$(this).remove();
			});
		};
		
		//----------------------------------------------------------------------
		// user-item-assingment-common-for-test
		//----------------------------------------------------------------------
		$('input[name=right]').on('click', function() {
			$('select[name=iihlms-select-mutiple-left] option:selected').each(function() {
				delete dataListLeft[$(this).val()];
				dataListRight[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftOrg) {
					$('#iihlms-select-items-change').append($(this).text()+'【購入済の講座に追加】<br>');
					$('#iihlms-select-items-change-code-add-wrap').append('<input type="hidden" name="iihlms-select-items-change-code-add[]" value="' + $(this).val() + '">');
				} else {
					let txt = $('#iihlms-select-items-change').html();
					$('#iihlms-select-items-change').html(txt.replace( $(this).text()+'【購入済の講座から削除】<br>' , ''));
					$('#iihlms-select-items-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-items-change-code-del[]" value="' + $(this).val() + '">' );
				}
			});
			move('iihlms-select-mutiple-left', 'iihlms-select-mutiple-right');
		});
		
		$('input[name=left]').on('click', function() {
			$('select[name=iihlms-select-mutiple-right] option:selected').each(function() {
				delete dataListRight[$(this).val()];
				dataListLeft[$(this).val()] = $(this).text();
				
				if ($(this).val() in dataListRightOrg) {
					$('#iihlms-select-items-change').append($(this).text()+'【購入済の講座から削除】<br>');
					$('#iihlms-select-items-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-items-change-code-del[]" value="' + $(this).val() + '">' );
				} else {
					let txt = $('#iihlms-select-items-change').html();
					$('#iihlms-select-items-change').html(txt.replace( $(this).text()+'【購入済の講座に追加】<br>' , ''));
					$('#iihlms-select-items-change-code-add-wrap').append( '<input type="hidden" name="iihlms-select-items-change-code-add[]" value="' + $(this).val() + '">' );
				}
			});
			move('iihlms-select-mutiple-right', 'iihlms-select-mutiple-left');
		});
		
		//----------------------------------------------------------------------
		// user-course-assingment
		//----------------------------------------------------------------------
		// 対象のセレクトボックスの内容一覧を取得
		var dataListLeftCourseAssingment = {};
		var dataListLeftCourseAssingmentOrg = {};
		selectObject = $('#target-options-course-manual-assignment-left').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListLeftCourseAssingment[targetObject.val()] = targetObject.text();
			dataListLeftCourseAssingmentOrg[targetObject.val()] = targetObject.text();
		}
		
		var dataListRightCourseAssingment = {};
		var dataListRightCourseAssingmentOrg = {};
		selectObject = $('#target-options-course-manual-assignment-right').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListRightCourseAssingment[targetObject.val()] = targetObject.text();
			dataListRightCourseAssingmentOrg[targetObject.val()] = targetObject.text();
		}
		
		// テキストエリアが変更された時
		$('#target-text-left').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-course-manual-assignment-left > option').remove();
				
				for ( var key in dataListLeftCourseAssingment ) {
					text = dataListLeftCourseAssingment[key];
					$('#target-options-course-manual-assignment-left').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-course-manual-assignment-left > option').remove();
				for ( var key in dataListLeftCourseAssingment ) {
					text = dataListLeftCourseAssingment[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-course-manual-assignment-left').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('#target-text-right').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-course-manual-assignment-right > option').remove();
				for ( var key in dataListRightCourseAssingment ) {
					text = dataListRightCourseAssingment[key];
					$('#target-options-course-manual-assignment-right').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-course-manual-assignment-right > option').remove();
				for ( var key in dataListRightCourseAssingment ) {
					text = dataListRightCourseAssingment[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-course-manual-assignment-right').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('input[name=right-course-assingment]').on('click', function() {
			$('select[name=target-options-course-manual-assignment-left] option:selected').each(function() {
				delete dataListLeftCourseAssingment[$(this).val()];
				dataListRightCourseAssingment[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftCourseAssingmentOrg) {
					$('#iihlms-select-course-manual-assignment-change').append($(this).text()+'【アクセス可能なコースに追加】<br>');
					$('#iihlms-select-course-manual-assignment-change-code-add-wrap').append('<input type="hidden" name="iihlms-select-course-manual-assignment-change-code-add[]" value="' + $(this).val() + '">');
				} else {
					let txt = $('#iihlms-select-course-manual-assignment-change').html();
					$('#iihlms-select-course-manual-assignment-change').html(txt.replace( $(this).text()+'【アクセス可能なコースから削除】<br>' , ''));
					$('#iihlms-select-course-manual-assignment-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-course-manual-assignment-change-code-del[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-course-manual-assignment-left', 'target-options-course-manual-assignment-right');
		});
		
		$('input[name=left-course-assingment').on('click', function() {
			$('select[name=target-options-course-manual-assignment-right] option:selected').each(function() {
				delete dataListRightCourseAssingment[$(this).val()];
				dataListLeftCourseAssingment[$(this).val()] = $(this).text();
				if ($(this).val() in dataListRightCourseAssingmentOrg) {
					$('#iihlms-select-course-manual-assignment-change').append($(this).text()+'【アクセス可能なコースから削除】<br>');
					$('#iihlms-select-course-manual-assignment-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-course-manual-assignment-change-code-del[]" value="' + $(this).val() + '">' );
				} else {
					let txt = $('#iihlms-select-course-manual-assignment-change').html();
					$('#iihlms-select-course-manual-assignment-change').html(txt.replace( $(this).text()+'【アクセス可能なコースに追加】<br>' , ''));
					$('#iihlms-select-course-manual-assignment-change-code-add-wrap').append( '<input type="hidden" name="iihlms-select-course-manual-assignment-change-code-add[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-course-manual-assignment-right', 'target-options-course-manual-assignment-left');
		});
		
		//----------------------------------------------------------------------
		// item-course-common
		//----------------------------------------------------------------------
		
		$(function(){
			$( '#iihlms-course-sortable' ) . sortable();
			$( '#iihlms-course-sortable' ) . disableSelection();
		});

		$('input[name=rightitem]').on('click', function() {
			
			$('select[name=iihlms-select-mutiple-left] option:selected').each(function() {
				delete dataListLeft[$(this).val()];
				dataListRight[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftOrg) {
					$('#item-course-related-change').append($(this).text()+'【関連しているコースに追加】<br>');
				} else {
					var txt = $('#item-course-related-change').html();
					$('#item-course-related-change').html(txt.replace($(this).text()+'【関連しているコースから削除】<br>' , ''));
				}
				
				$('#iihlms-course-sortable').append( '<li class="ui-state-default" id= "' +  $(this).val() +  '">' + $(this).text() + '<input type="hidden" name="iihlms-course-sortable-data[]" value="' +  $(this).val() +  '"></li>' );
				$('#iihlms-course-sortable').sortable("refresh");
			});
			
			move('iihlms-select-mutiple-left', 'iihlms-select-mutiple-right');
		});
		
		$('input[name=leftitem]').on('click', function() {
			$('select[name=iihlms-select-mutiple-right] option:selected').each(function() {
				var txt;
				delete dataListRight[$(this).val()];
				dataListLeft[$(this).val()] = $(this).text();
				
				if ($(this).val() in dataListRightOrg) {
					$('#item-course-related-change').append($(this).text()+'【関連しているコースから削除】<br>');
					$('#item-course-related-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-items-change-code-del[]" value="' + $(this).val() + '">' );
				} else {
					txt = $('#item-course-related-change').html();
					$('#item-course-related-change').html(txt.replace($(this).text()+'【関連しているコースに追加】<br>' , ''));
					$('#item-course-related-change-code-add-wrap').append( '<input type="hidden" name="iihlms-select-items-change-code-add[]" value="' + $(this).val() + '">' );
				}
				
				$('#iihlms-course-sortable li:contains(' + $(this).text() + ')' ).remove();
				$('#iihlms-course-sortable').sortable("refresh");
			});
			
			move('iihlms-select-mutiple-right', 'iihlms-select-mutiple-left');
		});
		
		$('#iihlms-course-sortable').sortable({
			update: function(e, ui) {
			console.log($('#iihlms-course-sortable').sortable('toArray'));
			}
		});
		
		//----------------------------------------------------------------------
		// user-course-complete-precondition
		//----------------------------------------------------------------------
		// 対象のセレクトボックスの内容一覧を取得
		var dataListLeftCoursePrecondition = {};
		var dataListLeftCoursePreconditionOrg = {};
		
		selectObject = $('#target-options-item-course-complete-precondition-left').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListLeftCoursePrecondition[targetObject.val()] = targetObject.text();
			dataListLeftCoursePreconditionOrg[targetObject.val()] = targetObject.text();
		}
		
		var dataListRightCoursePrecondition = {};
		var dataListRightCoursePreconditionOrg = {};
		selectObject = $('#target-options-item-course-complete-precondition-right').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataListRightCoursePrecondition[targetObject.val()] = targetObject.text();
			dataListRightCoursePreconditionOrg[targetObject.val()] = targetObject.text();
		}
		
		// テキストエリアが変更された時
		$('#target-text-left').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-item-course-complete-precondition-left > option').remove();
				
				for ( var key in dataListLeftCoursePrecondition ) {
					text = dataListLeftCoursePrecondition[key];
					$('#target-options-item-course-complete-precondition-left').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-item-course-complete-precondition-left > option').remove();
				for ( var key in dataListLeftCoursePrecondition ) {
					text = dataListLeftCoursePrecondition[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-item-course-complete-precondition-left').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('#target-text-right').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-item-course-complete-precondition-right > option').remove();
				for ( var key in dataListRightCourseAssingment ) {
					text = dataListRightCourseAssingment[key];
					$('#target-options-item-course-complete-precondition-right').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-item-course-complete-precondition-right > option').remove();
				for ( var key in dataListRightCourseAssingment ) {
					text = dataListRightCourseAssingment[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-item-course-complete-precondition-right').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('input[name=right-item-course-complete-precondition]').on('click', function() {
			$('select[name=target-options-item-course-complete-precondition-left] option:selected').each(function() {
				delete dataListLeftCoursePrecondition[$(this).val()];
				dataListRightCoursePrecondition[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftCoursePreconditionOrg) {
					$('#iihlms-select-item-course-complete-precondition-change').append($(this).text()+'【前提条件に追加】<br>');
					$('#iihlms-select-item-course-complete-precondition-change-code-add-wrap').append('<input type="hidden" name="iihlms-select-item-course-complete-precondition-change-code-add[]" value="' + $(this).val() + '">');
				} else {
					let txt = $('#iihlms-select-item-course-complete-precondition-change').html();
					$('#iihlms-select-item-course-complete-precondition-change').html(txt.replace( $(this).text()+'【前提条件から削除】<br>' , ''));
					$('#iihlms-select-item-course-complete-precondition-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-item-course-complete-precondition-change-code-del[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-item-course-complete-precondition-left', 'target-options-item-course-complete-precondition-right');
		});
		
		$('input[name=left-item-course-complete-precondition').on('click', function() {
			$('select[name=target-options-item-course-complete-precondition-right] option:selected').each(function() {
				delete dataListRightCoursePrecondition[$(this).val()];
				dataListLeftCoursePrecondition[$(this).val()] = $(this).text();
				if ($(this).val() in dataListRightCoursePreconditionOrg) {
					$('#iihlms-select-item-course-complete-precondition-change').append($(this).text()+'【前提条件から削除】<br>');
					$('#iihlms-select-item-course-complete-precondition-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-item-course-complete-precondition-change-code-del[]" value="' + $(this).val() + '">' );
				} else {
					let txt = $('#iihlms-select-item-course-complete-precondition-change').html();
					$('#iihlms-select-item-course-complete-precondition-change').html(txt.replace( $(this).text()+'【前提条件に追加】<br>' , ''));
					$('#iihlms-select-item-course-complete-precondition-change-code-add-wrap').append( '<input type="hidden" name="iihlms-select-item-course-complete-precondition-change-code-add[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-item-course-complete-precondition-right', 'target-options-item-course-complete-precondition-left');
		});
		
		//----------------------------------------------------------------------
		// course-lesson-common
		//----------------------------------------------------------------------
		
		$(function(){
			$( '#iihlms-lesson-sortable' ) . sortable();
			$( '#iihlms-lesson-sortable' ) . disableSelection();
		});
		
		$('input[name=rightlesson]').on('click', function() {
			
			$('select[name=iihlms-select-mutiple-left] option:selected').each(function() {
				delete dataListLeft[$(this).val()];
				dataListRight[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftOrg) {
					$('#course-lesson-related-change').append($(this).text()+'【関連しているレッスンに追加】<br>');
				} else {
					let txt = $('#course-lesson-related-change').html();
					$('#course-lesson-related-change').html(txt.replace($(this).text()+'【関連しているレッスンから削除】<br>' , ''));
				}
				
				$('#iihlms-lesson-sortable').append( '<li class="ui-state-default" id= "' +  $(this).val() +  '">' + $(this).text() + '<input type="hidden" name="iihlms-lesson-sortable-data[]" value="' +  $(this).val() +  '"></li>' );
				$('#iihlms-lesson-sortable').sortable("refresh");
			});
			
			move('iihlms-select-mutiple-left', 'iihlms-select-mutiple-right');
		});
		
		$('input[name=leftlesson]').on('click', function() {
			$('select[name=iihlms-select-mutiple-right] option:selected').each(function() {
				delete dataListRight[$(this).val()];
				dataListLeft[$(this).val()] = $(this).text();
				
				if ($(this).val() in dataListRightOrg) {
					$('#course-lesson-related-change').append($(this).text()+'【関連しているレッスンから削除】<br>');
				} else {
					let txt = $('#course-lesson-related-change').html();
					$('#course-lesson-related-change').html(txt.replace($(this).text()+'【関連しているレッスンに追加】<br>' , ''));
				}
				
				$('#iihlms-lesson-sortable li:contains(' + $(this).text() + ')' ).remove();
				$('#iihlms-lesson-sortable').sortable("refresh");
			});
			
			move('iihlms-select-mutiple-right', 'iihlms-select-mutiple-left');
		});
		
		$('#iihlms-lesson-sortable').sortable({
			update: function(e, ui) {
				console.log($('#iihlms-lesson-sortable').sortable('toArray'));
			}
		});
		
		//----------------------------------------------------------------------
		// item-test-pass-precondition
		//----------------------------------------------------------------------
		// 対象のセレクトボックスの内容一覧を取得
		var dataListLeftItemTestPassPrecondition = {};
		var dataListLeftItemTestPassPreconditionOrg = {};

		selectItemTestPassObject = $('#target-options-item-test-pass-precondition-left').children();
		for(i = 0; i < selectItemTestPassObject.length; i++) {
			targetObject = selectItemTestPassObject.eq(i);
			dataListLeftItemTestPassPrecondition[targetObject.val()] = targetObject.text();
			dataListLeftItemTestPassPreconditionOrg[targetObject.val()] = targetObject.text();
		}
		
		var dataListRightItemTestPrecondition = {};
		var dataListRightItemTestPassPreconditionOrg = {};
		selectItemTestPassObject = $('#target-options-item-test-pass-precondition-right').children();
		for(i = 0; i < selectItemTestPassObject.length; i++) {
			targetObject = selectItemTestPassObject.eq(i);
			dataListRightItemTestPrecondition[targetObject.val()] = targetObject.text();
			dataListRightItemTestPassPreconditionOrg[targetObject.val()] = targetObject.text();
		}
		
		// テキストエリアが変更された時
		$('#target-text-left-item-test-pass-precondition').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-item-test-pass-precondition-left > option').remove();
				
				for ( var key in dataListLeftItemTestPassPrecondition ) {
					text = dataListLeftItemTestPassPrecondition[key];
					$('#target-options-item-test-pass-precondition-left').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-item-test-pass-precondition-left > option').remove();
				for ( var key in dataListLeftItemTestPassPrecondition ) {
					text = dataListLeftItemTestPassPrecondition[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-item-test-pass-precondition-left').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('#target-text-right-item-test-pass-precondition').keyup(function(){
			targetString = $(this).val();
			
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示
				$('#target-options-item-test-pass-precondition-right > option').remove();
				for ( var key in dataListRightItemTestPrecondition ) {
					text = dataListRightItemTestPrecondition[key];
					$('#target-options-item-test-pass-precondition-right').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示
				$('#target-options-item-test-pass-precondition-right > option').remove();
				for ( var key in dataListRightItemTestPrecondition ) {
					text = dataListRightItemTestPrecondition[key];
					if(text.indexOf(targetString) != -1) {
						$('#target-options-item-test-pass-precondition-right').append($('<option>').html(text).val(key));
					}
				}
			}
		});
		
		$('input[name=right-item-test-pass-precondition]').on('click', function() {
			$('select[name=target-options-item-test-pass-precondition-left] option:selected').each(function() {
				delete dataListLeftItemTestPassPrecondition[$(this).val()];
				dataListRightItemTestPrecondition[$(this).val()] = $(this).text();
				if ($(this).val() in dataListLeftItemTestPassPreconditionOrg) {
					$('#iihlms-select-item-test-pass-precondition-change').append($(this).text()+'【前提条件に追加】<br>');
					$('#iihlms-select-item-test-pass-precondition-change-code-add-wrap').append('<input type="hidden" name="iihlms-select-item-test-pass-precondition-change-code-add[]" value="' + $(this).val() + '">');
				} else {
					let txt = $('#iihlms-select-item-test-pass-precondition-change').html();
					$('#iihlms-select-item-test-pass-precondition-change').html(txt.replace( $(this).text()+'【前提条件から削除】<br>' , ''));
					$('#iihlms-select-item-test-pass-precondition-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-item-test-pass-precondition-change-code-del[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-item-test-pass-precondition-left', 'target-options-item-test-pass-precondition-right');
		});
		
		$('input[name=left-item-test-pass-precondition').on('click', function() {
			$('select[name=target-options-item-test-pass-precondition-right] option:selected').each(function() {
				delete dataListRightItemTestPrecondition[$(this).val()];
				dataListLeftItemTestPassPrecondition[$(this).val()] = $(this).text();
				if ($(this).val() in dataListRightItemTestPassPreconditionOrg) {
					$('#iihlms-select-item-test-pass-precondition-change').append($(this).text()+'【前提条件から削除】<br>');
					$('#iihlms-select-item-test-pass-precondition-change-code-del-wrap').append( '<input type="hidden" name="iihlms-select-item-test-pass-precondition-change-code-del[]" value="' + $(this).val() + '">' );
				} else {
					let txt = $('#iihlms-select-item-test-pass-precondition-change').html();
					$('#iihlms-select-item-test-pass-precondition-change').html(txt.replace( $(this).text()+'【前提条件に追加】<br>' , ''));
					$('#iihlms-select-item-test-pass-precondition-change-code-add-wrap').append( '<input type="hidden" name="iihlms-select-item-test-pass-precondition-change-code-add[]" value="' + $(this).val() + '">' );
				}
			});
			move('target-options-item-test-pass-precondition-right', 'target-options-item-test-pass-precondition-left');
		});
	});

	//----------------------------------------------------------------------
	// test
	//----------------------------------------------------------------------
	$(function(){
		var select = $('#iihlms-test-number-of-questions');
		var length = select.children().length;
		select.on('change', function(){
			var selectedOptionText = $(this).children(':selected').text();
			for(i = 1; i <= selectedOptionText ; i++) {
				$('#iihlms-test-wrap-'+i).css('display', 'block');
			}
			for(i = Number(selectedOptionText)+1; i <= length; i++) {
				$('#iihlms-test-wrap-'+i).css('display', 'none');
			}
		}).change();
	});
	
	//----------------------------------------------------------------------
	// item
	//----------------------------------------------------------------------
	$(function(){
		$('input[name="iihlms-payment-type"]').change(function () {
			var val = $(this).val();
			if(val == 'onetime') {
				$('#item_setting_1a').css('display', 'block');
				$('#item_setting_1b').css('display', 'none');
				$('#item_setting_1c').css('display', 'none');
			}else{
				$('#item_setting_1a').css('display', 'none');
				$('#item_setting_1b').css('display', 'block');
				$('#item_setting_1c').css('display', 'block');
			}
		});
	});
	
	//----------------------------------------------------------------------
	// payment_method_setting
	//----------------------------------------------------------------------
	$(function(){
		$('input[name="iihlms-payment-method-setting[]"]').change(function () {
			$('#iihlms-payment-method-paypal-wrap').css('display', 'none');
			$('#iihlms-paypal-clientid').prop('disabled', true);
			$('#iihlms-paypal-secretid').prop('disabled', true);
			$('input[name="iihlms-paypal-liveorsandbox"]').prop('disabled', true);

			$('#iihlms-payment-method-stripe-wrap').css('display', 'none');
			$('#iihlms-public-key-stripe').prop('disabled', true);
			$('#iihlms-secret-key-stripe').prop('disabled', true);
			$('#iihlms-webhook-secret-stripe').prop('disabled', true);

			$('input[name="iihlms-payment-method-setting[]"]:checked').each(function() {
				var val = $(this).val();
				if(val == 'paypal') {
					$('#iihlms-payment-method-paypal-wrap').css('display', 'block');
					$('#iihlms-paypal-clientid').prop('disabled', false);
					$('#iihlms-paypal-secretid').prop('disabled', false);
					$('input[name="iihlms-paypal-liveorsandbox"]').prop('disabled', false);
				}
				if(val == 'stripe') {
					$('#iihlms-payment-method-stripe-wrap').css('display', 'block');
					$('#iihlms-public-key-stripe').prop('disabled', false);
					$('#iihlms-secret-key-stripe').prop('disabled', false);
					$('#iihlms-webhook-secret-stripe').prop('disabled', false);
				}	
			});
		});

	});

})(jQuery);

$(function () {


	var $menu = $('#toc');
	$menu.sidebar({
		transition       : 'uncover',
		mobileTransition : 'uncover'
	});
	$('.launch.button, .view-ui, .launch.item').on('click', function(event) {
		$menu.sidebar('toggle');
		event.preventDefault();
	});

	$sidebarButton = $('.fixed.launch.button');


	$('.ui.dropdown[data-content]').popup({
		inline: true,
		hoverable: $(this).hasClass('hoverable'),
		delay: {
			show: 100,
			hide: 200
		}
	});

	$('.ui.accordion').accordion();
	$('.ui.checkbox').checkbox();
	$(".chosen-select").chosen({
		width: '100%',
		no_results_text: "No hay resultados",
		disable_search_threshold: 7,
		search_contains: true
	});
	$('.message .close').on('click', function() {
		$(this).closest('.message').transition('fade');
	});

	$('.ui.menu.dash.sticky').sticky({offset: 70});
	$('.ui.bcrumb.sticky').sticky({offset: 69});
	$('.ui.menu.pointing .item').tab({
		context:'parent'
	});
	var $sortableTables = $('table.sortable');
	$sortableTables.tablesort();

	if( $sortableTables.length > 0 ) {
		$('select.sortable.filter[data-index]').each(function(pos, elem){
			var $select = $(elem);
			var $table = $($sortableTables[0]);
			var col = $select.data('index') + 1;
			var valuesIndex = {};
			var values = [];
			$table.find('tr td:nth-child(' + col + ')').each(function(pos,td){
				var $td = $(td);
				if( !valuesIndex[$td.text()] ) {
					valuesIndex[$td.text()] = true;
					values.push($td.text());
				}
			});
			values.sort( );
			for( var c = 0; c < values.length; c++ ) {
				$select.append('<option value="' + values[c] + '">' + values[c] + '</option>');
			}
			$select.change(function() {
				var value = $select.val();
				if ( value == '' ) {
					$table.find('tr[data-filter*=",' + col + ',"]').each(function(index,elem){
						var $tr = $(elem);
						$tr.attr('data-filter', ( $tr.attr('data-filter') || '' ).replace(',' + col + ',', '') );
						if(!$tr.attr('data-filter'))
							$tr.removeClass('hidden');
					});
				}
				else {
					$table.find('tr td:nth-child(' + col + ')').each(function(pos,td){
						var $td = $(td);
						var $tr = $td.closest('tr');
						$tr.attr('data-filter', ( $tr.attr('data-filter') || '' ).replace(',' + col + ',', '') );
						if( $td.text() == value ) {
							if(!$tr.attr('data-filter'))
								$tr.removeClass('hidden');
						}
						else {
							$tr.addClass('hidden');
							$tr.attr('data-filter', ( $tr.attr('data-filter') || '' ) + ',' + col + ',' );
						}
					});
				}
			});
		});
	}

	$('a.ui.black.button.mini .file.excel.outline.icon').parent().click(function(event){
		event.preventDefault();

		var data = {
			title: '',
			headers: [],
			data: []
		};

		$('.ui.celled.striped.table.small.filters:first select.sortable.filter[data-index]').each(function( index, elem){
			var val = $(elem).val();
			if( val )
				data.title += ' - ' + val;
		});
		if( !data.title )
			data.title = document.title;
		else
			data.title = 'CATA' + data.title;

		var $table = $('.ui.celled.striped.table.small:not(.filters):first');
		var headerJump = 0;

		$table.find('thead:first tr:first th').each(function(index, elem){
			var text = $(elem).text();
			if( data.headers.length == 0 && ( !text || text == '#' ) ) {
				headerJump ++;
			}
			else {
				data.headers.push( text );
			}
		});

		$table.find('tbody:first tr:not(.hidden)').each(function(tindex, tr){
			var row = {};
			$(tr).find('td').each(function(index, td){
				if( index >= headerJump ) {
					row[data.headers[index - headerJump]] = $(td).text( );
				}
			});
			data.data.push( row );
		});

		$('#excelform').remove( );
		var $form = $('<form />');
		$form.attr('id', 'excelform');
		$form.attr('action', $(this).attr('href'));
		$form.attr('method', 'post');
		var $hidden = $('<input />');
		$hidden.attr('type', 'hidden');
		$hidden.attr('name', 'data');
		$hidden.attr('value', JSON.stringify(data));
		$form.append( $hidden );

		$(document.body).append($form);
		$form.submit();

	});

});

$(function(){

	if( $('.ui.new-patient.modal').length == 0 )
		return;

	$('.ui.new-patient.modal').modal({
		blurring: true,
		autofocus: true,
		onDeny    : function(){

		},
		selector    : {
			close    : '.actions .button',
			approve  : '.actions .positive, .actions .approve, .actions .ok',
			deny     : '.actions .negative, .actions .deny, .actions .cancel'
		},
	});

	$('.consult-action').click(function(event){
		event.preventDefault();
		$('.ui.new-patient.modal').modal('show');
	});

	$('#select-new-patient').selectize({
		valueField: 'id',
		labelField: 'patient',
		searchField: ['first_name','last_name','PID','code'],
		render: {
			option: function(item, escape) {

				console.debug(item);

				return '' +
					'<div class="new-patient-option">' +
						'<div class="title">' +
							'<i class="ui icon user"></i><span class="name">' + escape( item.first_name + ' ' + item.last_name ) + '</span>' +
						'</div>' +
						'<div class="description">' +
							'<b>CÓD: </b>' + escape(item.code) + ' ' +
							'<b>PID: </b>' + escape(item.PID) + ' ' +
						'</div>' +
						'<div class="details">' +
							'<b>Visitas: </b>' + escape(item.consults) + ' ' +
							'<b>Última visita: </b>' + escape(item.last_session) + ' ' +
						'</div>' +
					'</div>';
			}
		},
		load: function(query, callback) {

			$('#consults').addClass('disabled').prop('disabled');

			if (!query.length)
				return callback();

			$.ajax({
				url: base_url + 'services/search/patient',
				type: 'GET',
				dataType: 'json',
				data: {
					q: query,
					page_limit: 10
				},
				error: function() {
					callback();
				},
				success: function(res) {
					if( res.data && res.data.length > 0 ) {
						$('#select-new-patient').popup('destroy');
						$.each(res.data,function(index, elem){
							elem.patient = elem.first_name + ' ' + elem.last_name + ' (' + elem.code + ')'
						});
						callback(res.data);
					}
					else {
						$('#select-new-patient').popup(
							{
								position : 'top center',
								content  : 'No se encontraron resultados',
								inline   : true,
								className   : {
									loading     : 'loading',
									popup       : 'ui inverted red popup',
									position    : 'top left',
									visible     : 'visible'
								},
								onHidden: function(){
									$('#select-new-patient').popup('destroy');
								}
							}
						).popup('show');
					}
				}
			});
		},
		onChange: function(value) {
			$('#consults').removeClass('disabled').removeProp('disabled');
			$('#consults').unbind('click');
			$('#consults').click(function(){
				document.location.href = base_url + 'history/' + value;
			});

		}
	});

	$('#new-patient').click(function(){
		$('#search-patient').hide();
		$('#create-patient').removeClass('hidden').show();
		$('#patient_first_name').focus();
		$('#new-patient-create').removeClass('disabled').removeProp('disabled');
		$('#new-patient-form')[0].reset();
		$('#new-patient-form input, #new-patient-create, #new-patient-code').popup('destroy');
		$('#new-patient-code').click();
	});
	$('#new-patient-cancel').click(function(){
		$('#create-patient').hide();
		$('#search-patient').show();

		var selectize = $("#select-new-patient")[0].selectize;
		selectize.clear();
		selectize.clearOptions();

		$('#select-new-patient').click();
	});

	$('#new-patient-create').click(function(){

		$('#new-patient-code').addClass('disabled').prop('disabled','disabled');
		$('#new-patient-create').addClass('disabled').prop('disabled','disabled');

		var $newPatient = {
			'first_name': $('#patient_first_name').val(),
			'last_name': $('#patient_last_name').val(),
			'code': $('#patient_code').val(),
			'PID': $('#patient_PID').val(),
		};

		$.ajax({
			type: "POST",
			url: base_url + 'services/patient/create',
			data: $newPatient,
			success: function( result ){
				if( result.error ) {
					$('#patient_' + result.error.scope).popup(
						{
							position : 'top center',
							title    : 'Error',
							content  : result.error.msg,
							inline   : true,
							variation: 'wide small',
							className   : {
								loading     : 'loading',
								popup       : 'ui inverted red popup',
								position    : 'top center',
								visible     : 'visible'
							},
							onHidden: function(){
								$('#patient_' + result.error.scope).popup('destroy');
							}
						}
					).popup('show');
					setTimeout(function(){
						$('#new-patient-create').removeClass('disabled').removeProp('disabled');
					},1000);
				}
				else {
					document.location.href = base_url + 'history/' + result.data.id;
				}
			},
			error: function(){
				$('#new-patient-create').popup(
					{
						position : 'top center',
						title    : 'Error',
						content  : 'Error al crear el paciente.',
						inline   : true,
						variation: 'wide small',
						className   : {
							loading     : 'loading',
							popup       : 'ui inverted red popup',
							position    : 'top center',
							visible     : 'visible'
						}
						,
						onHidden: function(){
							$('#new-patient-create').popup('destroy');
						}
					}
				).popup('show');

				$('#new-patient-code').removeClass('disabled').removeProp('disabled');
				$('#new-patient-create').removeClass('disabled').removeProp('disabled');
			},
			dataType: 'json'
		});

	});

	$('#new-patient-code').click(function(){

		$('#new-patient-code').addClass('disabled').prop('disabled','disabled');
		$('#new-patient-create').addClass('disabled').prop('disabled','disabled');

		$.ajax({
			type: "POST",
			url: base_url + 'services/patient/code',
			success: function( result ){
				if( result.error ) {
					$('#patient_' + result.error.scope).popup(
						{
							position : 'top center',
							title    : 'Error',
							content  : result.error.msg,
							inline   : true,
							variation: 'wide small',
							className   : {
								loading     : 'loading',
								popup       : 'ui inverted red popup',
								position    : 'top center',
								visible     : 'visible'
							},
							onHidden: function(){
								$('#new-patient-code').popup('destroy');
							}
						}
					).popup('show');
				}
				else {
					$('#patient_code').val(result.data.code);
				}

				$('#new-patient-code').removeClass('disabled').removeProp('disabled');
				$('#new-patient-create').removeClass('disabled').removeProp('disabled');
				setTimeout(function(){
					$('#new-patient-code').removeClass('disabled').removeProp('disabled');
				},1000);
			},
			error: function(){
				$('#new-patient-code').popup(
					{
						position : 'top center',
						title    : 'Error',
						content  : 'Error al buscar código.',
						inline   : true,
						variation: 'wide small',
						className   : {
							loading     : 'loading',
							popup       : 'ui inverted red popup',
							position    : 'top center',
							visible     : 'visible'
						}
						,
						onHidden: function(){
							$('#new-patient-code').popup('destroy');
						}
					}
				).popup('show');

				$('#new-patient-code').removeClass('disabled').removeProp('disabled');
				$('#new-patient-create').removeClass('disabled').removeProp('disabled');

				setTimeout(function(){
					$('#new-patient-code').removeClass('disabled').removeProp('disabled');
				},1000);

			},
			dataType: 'json'
		});

	});

});


function flow( ) {

	/*
	var $flow = $('#flowHolder');
	if( $flow.length > 0 ) {
		var $flowProgress = $('#flowProgress').progress().fadeOut(0);
	}
	*/

	this.createPage = function( ) {
		console.debug('Nueva');
		var $page = {};

		/*

		 <div class="page">
		 <div class="holder">
		 <h1>Facebook - Resultados del mes</h1>

		 <div class="options">
		 <a href="#"><i
		 class="content small grey icon"></i></a><a
		 href="#"><i class="remove small icon"></i></a>
		 </div>
		 <div class="widgets">

		 </div>
		 </div>
		 </div>
		 */

	};

}

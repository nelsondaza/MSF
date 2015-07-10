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
		disable_search_threshold: 5,
		search_contains: true
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

	$('.visit-action').click(function(event){
		event.preventDefault();
		$('.ui.new-patient.modal').modal('show');
	});

	$('#select-new-patient').selectize({
		valueField: 'id',
		labelField: 'patient',
		searchField: ['search_text'],
		options: [],
		create: false,
		render: {
			option: function(item, escape) {
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
							'<b>Visitas: </b>' + escape(1) + ' ' +
							'<b>Última visita: </b>' + escape('2015-02-02') + ' ' +
						'</div>' +
					'</div>';
			}
		},
		load: function(query, callback) {

			$('#new-visit').addClass('disabled');

			if (!query.length) return callback();
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
					if( res.data ) {
						$.each(res.data,function(index, elem){
							elem.patient = elem.first_name + ' ' + elem.last_name + ' (' + elem.code + ')'
						});
						callback(res.data);
					}
					else {

					}
				}
			});
		},
		onChange: function(value) {
			$('#new-visit').removeClass('disabled');
			$('#new-visit').unbind('click');
			$('#new-visit').click(function(){
				document.location.href = base_url + 'visits/visits';
			});

		}
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


$(function(){

	var $form = $('#first_visit_form');

	if( $form.length == 0 )
		return;

	$form.unbind('submit');
	$form.submit(function(){
		return false;
	});

	$form.find('button.submit').click(function(event){
		var $self = $(this);

		event.preventDefault();

		$self.addClass('disabled').prop('disabled','disabled');

		var fields = [
			'id_localization',
			'id_origin_place',
			'id_expert',
			'id_patient',
			'gender',
			'id_education',
			'age',
			'id_reference'
		];

		var $newPatient = {};

		$.each( fields, function( index, key ){
			$newPatient[key] = $form.form('get value','history_field_' + key);
		});
		$newPatient['id_reference'] = [];
		$form.find('#history_field_id_reference input:checked').each(function(index,elem){
			$newPatient['id_reference'].push($(elem).val());
		});


		$.ajax({
			type: "POST",
			url: base_url + 'services/history/first_visit',
			data: $newPatient,
			success: function( result ){
				if( result.error ) {
					$('#history_field_' + result.error.scope).popup(
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
								$('#history_field_' + result.error.scope).popup('destroy');
							}
						}
					).popup('show');
					setTimeout(function(){
						$self.removeClass('disabled').removeProp('disabled');
					},1000);
				}
				else {
					document.location.href = document.location.href;
				}
			},
			error: function(){
				$self.popup(
					{
						position : 'top center',
						title    : 'Error',
						content  : 'Error al guardar la información.',
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
							$self.popup('destroy');
						}
					}
				).popup('show');

				$self.removeClass('disabled').removeProp('disabled');
			},
			dataType: 'json'
		});


		return false;
	});

});

$(function(){

	var $forms = $('form.form.consultation');

	if( $forms.length == 0 )
		return;

	$forms.unbind('submit');
	$forms.submit(function(){
		return false;
	});

	$forms.find('button.submit').click(function(event){
		var $self = $(this);
		var $form = $self.closest('form.form.consultation');
		var id = $form.data('index');

		event.preventDefault();

		$self.addClass('disabled').prop('disabled','disabled');

		var fields = [
			'id_consult',
			'id_patient',
			'id_consults_type',
			'id_interventions_type',
			'date',
			'symptoms_severity',
			'operation_reduction',
			'id_referenced_to',
			'referenced_date',
			'psychotropics_date',
			'comments',
		];

		var $newPatient = {};
		$.each( fields, function( index, key ){
			$newPatient[key] = $('#history_field_' + key + '_' + id).val();
		});

		var multiFields = [
			'id_symptom',
			'id_diagnostic',
			'id_risk',
		];

		$.each( multiFields, function( index, key ){
			$newPatient[key] = [];
			$form.find('#history_field_' + key + '_' + id + ' input:checked').each(function(index,elem){
				$newPatient[key].push($(elem).val());
			});
		});

		$newPatient['psychotropics'] = $('#history_field_psychotropics_' + id + ':checked').length == 1;

		$.ajax({
			type: "POST",
			url: base_url + 'services/history/consult',
			data: $newPatient,
			success: function( result ){
				if( result.error ) {
					$('#history_field_' + result.error.scope + '_' + id).popup(
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
								$('#history_field_' + result.error.scope + '_' + id).popup('destroy');
							}
						}
					).popup('show');
					setTimeout(function(){
						$self.removeClass('disabled').removeProp('disabled');
					},1000);
				}
				else {
					document.location.href = document.location.href;
				}
			},
			error: function(){
				$self.popup(
					{
						position : 'top center',
						title    : 'Error',
						content  : 'Error al guardar la información.',
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
							$self.popup('destroy');
						}
					}
				).popup('show');

				$self.removeClass('disabled').removeProp('disabled');
			},
			dataType: 'json'
		});


		return false;
	});

});


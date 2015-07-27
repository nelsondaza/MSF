    <div class="ui vertical black footer segment">
        <div class="container">
            <div class="copy">
                All rights reserved <sup>&copy;</sup> 2015 | Colombia
                <div class="ui top right attached black label"><i class="wait icon"></i> <small><?= sprintf(lang('website_page_rendered_in_x_seconds'), $this->benchmark->elapsed_time()); ?></small></div>
            </div>
        </div>
    </div>
</div>
<div class="ui small modal new-patient">
    <div class="ui top attached header">Buscar Paciente / Iniciar</div>
    <div class="content">
		<form action="" class="ui small fluid form " method="post" accept-charset="utf-8" id="new-patient-form">
			<div class="two fields" id="search-patient">
				<div class="field ">
					<label class="control-label" for="select-new-patient">Buscar Paciente:</label>
					<select name="select-new-patient" id="select-new-patient"></select>
				</div>
				<div class="field">
					<div class="three fields">
						<div class="field ">
							<label class="control-label" for="consults">Consultas</label>
							<div class="ui mini button blue disabled" id="consults">Historial</div>
						</div>
						<div class="field ">
							<label class="control-label" for="consults"><br></label>
							<div class="ui mini button blue disabled" id="resumes">Resumen</div>
						</div>
						<div class="field ">
							<label class="control-label" for="new-patient">Paciente</label>
							<div class="ui mini button positive" id="new-patient">Nuevo</div>
						</div>
					</div>
				</div>
			</div>
			<div class="two fields hidden" id="create-patient">
				<div class="field ">
					<label class="control-label" for="patient_first_name">Nombres:</label>
					<input type="text" name="patient_first_name" id="patient_first_name">
				</div>
				<div class="field ">
					<label class="control-label" for="patient_last_name">Apellidos:</label>
					<input type="text" name="patient_last_name" id="patient_last_name">
				</div>
				<div class="field ">
					<label class="control-label" for="patient_code">* Consecutivo:</label>
					<div class="ui icon input">
						<input type="text" name="patient_code" id="patient_code">
						<i class="inverted circular search link icon" id="new-patient-code"></i>
					</div>
				</div>
				<div class="field ">
					<label class="control-label" for="patient_PID">Identificación:</label>
					<input type="text" name="patient_PID" id="patient_PID">
				</div>
				<div class="two fields">
					<div class="ui mini button negative" id="new-patient-cancel">Cancelar</div>
					<div class="ui mini button positive" id="new-patient-create">Crear e Iniciar Consulta</div>
				</div>
			</div>
		</form>
    </div>
    <div class="ui bottom attached header actions">
        <div class="ui mini button negative">Cancelar</div>
    </div>
</div>
<?php
/*
<script>
    (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
    ga('create','UA-XXXXX-X');ga('send','pageview');
</script>
*/
?>

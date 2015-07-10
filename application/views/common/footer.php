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
    <div class="ui top attached header">Paciente / Visita</div>
    <div class="content">
		<form action="" class="ui small fluid form " method="post" accept-charset="utf-8">
			<div class="two fields">
				<div class="field ">
					<label class="control-label" for="select-new-patient">Buscar Paciente:</label>
					<select name="select-new-patient" id="select-new-patient"></select>
				</div>
				<div class="field">
					<div class="two fields">
						<div class="field ">
							<label class="control-label" for="">Iniciar Visita</label>
							<div class="ui mini button blue disabled" id="new-visit">Nueva</div>
						</div>
						<div class="field ">
							<label class="control-label" for="">Crear Paciente</label>
							<div class="ui mini button positive">Nuevo</div>
						</div>
					</div>
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

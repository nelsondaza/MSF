<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?= $this->load->view('common/head', array('title' => lang('website_home') ) ) ?>
</head>
<body>
    <?= $this->load->view('common/header', array('current' => 'home')) ?>
    <div class="container content">
        <div class="sub-header">
            <i class="icon home"></i> <?= lang('website_home') ?>
        </div>
        <div class="section">
			<button class="ui black button consult-action">
				<i class="icon doctor"></i>
				Consulta
			</button>
			<br>
			<br>
		<div class="flow">
			<div class="page">
				<div class="holder" style="height: 704px !important;">
					<div class="widgets">
						<div class="gridster">
							<div class="widget teal" data-row="1" data-col="1" data-sizex="4" data-sizey="3">
								<div class="head">
                                    PACIENTES POR GÉNERO
								</div>
								<div class="body" id="upp"></div>
							</div>
							<div class="widget teal" data-row="1" data-col="5" data-sizex="4" data-sizey="3">
								<div class="head">
									PACIENTES POR GRUPO DE EDAD
								</div>
								<div class="body" id="upt"></div>
							</div>
							<div class="widget orange" data-row="5" data-col="1" data-sizex="8" data-sizey="3">
								<div class="head">
                                    PACIENTES REGISTRADOS POR DÍA
								</div>
								<div class="body" id="uph"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<script type="text/javascript">
				$(function(){ //DOM Ready
					// x = 1160 ==> ( 135 + 5 + 5 ) * 8 ==> ( width + margin-left + margin-right ) * columns = 968
					// y = 653 ==> ( 100 + 4 + 4 ) * 6 ==> ( height + margin-top + margin-bottom ) * rows = 648
					$(".flow .page .holder .widgets .gridster").gridster({
						widget_base_dimensions: [135, 100],
						widget_margins: [5, 4],
						widget_selector: 'div',
						max_cols: 8,
						min_rows: 6,
						max_rows: 6,
						autogrow_cols: false,
						resize: {
							enabled: false
						},
						draggable: {
							start: function (event, ui) {
								event.preventDefault();
								return false;
							}
						}
					});
				});
			</script>
<?php
	//
	$this->db->select('msf_patients.gender, COUNT(*) AS total');
	$this->db->where('gender IS NOT NULL');
	$this->db->group_by('gender');
	$rows = $this->db->get('msf_patients')->result_array( );

	$user_types = array(
        'M' => 0,
        'F' => 0,
        'I' => 0,
    );
	foreach( $rows as $row ) {
		$user_types[$row['gender']] = $row['total'];
	}

	$pie = array(
		'name' => 'Pacientes por género',
		'data' => $user_types
	);
?>
<script type="text/javascript">
	$(function () {
		// Build chart 1
		$('#upp').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: null,
			tooltip: {
				pointFormat: '<b>{point.y}</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: <?= ( count( $pie['data']) > 6 ? 'false' : 'true' ) ?>
				}
			},
			series: [{
				type: 'pie',
				name: ('<?= ( $pie['name'] ) ?>'),
				data: [
<?php
		foreach( $pie['data'] as $key => $value ) {
?>
					['<?= ( $key == 'F' ? 'FEMENINO' : ( $key == 'M' ? 'MASCULINO' : 'INDEFINIDO' ) ) ?>', <?= (float)( $value ) ?> ],
<?php
		}
?>
				]
			}]
		});
	});
</script>
<?php
	// Users type
	$this->db->select("
		( IF( msf_patients.age IS NULL OR msf_patients.age <= 5, '≤ 5', IF( msf_patients.age >= 19, '≥ 19', '6-18' ) ) ) AS age_group, COUNT(*) AS total
		", false
	);
	$this->db->where('msf_patients.gender IS NOT NULL');
	$this->db->group_by('age_group');
	$this->db->order_by('age');
	$rows = $this->db->get('msf_patients')->result_array( );

	$user_types = array( );
	foreach( $rows as $row ) {
		$user_types[$row['age_group']] = $row['total'];
	}

	$pie = array(
		'name' => 'Pacientes por Grupo de Edad',
		'data' => $user_types
	);
?>
<script type="text/javascript">
	$(function () {
		// Build chart 1
		$('#upt').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: null,
			tooltip: {
				pointFormat: '<b>{point.y}</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					},
					showInLegend: <?= ( count( $pie['data']) > 6 ? 'false' : 'true' ) ?>
				}
			},
			series: [{
				type: 'pie',
				name: ('<?= ( $pie['name'] ) ?>'),
				data: [
<?php
		foreach( $pie['data'] as $key => $value ) {
?>
					[('<?= ( $key ) ?>'), <?= (float)( $value ) ?> ],
<?php
		}
?>
				]
			}]
		});
	});
</script>
<?php
	$rows = array();

	// Users type
	$query = $this->db->query ( "" . "
		SELECT
		LEFT(creation, 10) AS fecha, COUNT(*) AS total
		FROM msf_patients
		WHERE creation > DATE_ADD(NOW(), INTERVAL -20 DAY)
		AND gender IS NOT NULL
		GROUP BY
		LEFT(creation, 10)
		ORDER BY fecha ASC
		" );
	$rows = $query->result_array();

	$user_types = array( );
	foreach( $rows as $row ) {
		$user_types[$row['fecha']] = $row['total'];
	}

	$pie = array(
		'name' => 'Pacientes registrados por día',
		'data' => $user_types
	);
?>
<script type="text/javascript">
	$(function () {
		// Build chart 1
		$('#uph').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: null,
			tooltip: {
				pointFormat: '<b>{point.y}</b>'
			},
            xAxis: {
                type: 'datetime',
                minRange: 20 * 24 * 3600000 // 20 days
            },
			plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
			},
			series: [{
				type: 'area',
                pointInterval: 24 * 3600 * 1000,
                pointStart: Date.UTC(<?= date("Y", strtotime("INTERVAL -20 DAY")) ?>, <?= date("m", strtotime("INTERVAL -20 DAY")) - 1 ?>, <?= date("d", strtotime("INTERVAL -20 DAY")) ?>),
				name: ('<?= ( $pie['name'] ) ?>'),
				data: [
<?php
		foreach( $pie['data'] as $key => $value ) {
				$key = explode('-',$key);
				$key[1]--;
?>
					[Date.UTC(<?= implode(',', $key) ?>), <?= (float)( $value ) ?> ],
<?php
		}
?>
				]
			}]
		});
	});
</script>
</div>
        </div>
    </div>
    <?= $this->load->view('common/footer') ?>
</body>
</html>

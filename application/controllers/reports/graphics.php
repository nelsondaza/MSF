<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	class Graphics extends GeneralController {
		protected $scope = 'reports';

		function __construct() {
			parent::__construct();

			$this->load->model( array(
					'manage/consults_model',
			) );
		}

		function index() {

			$data = $this->auth( '', array(
				'generate_reports' => 'account/account_profile',
				'view_reports' => 'account/account_profile'
			) );

			$start = $this->input->post('start');
			if( !$start )
				$start = date('Y-m-d', strtotime( "-1 MONTH" ) );
			$start = strtotime( $start );

			$end = $this->input->post('end');
			if( !$end )
				$end = date('Y-m-d' );
			$end = strtotime( $end );

			$measure = $this->input->post('measure');

			$measures = array(
				'consultas_vs_psicologo' => 'Consultas Vs. Psicólogo',
				'educacion_vs_diagnostico' => 'Educación Vs. Diagnóstico',
			);

			$groupby = $this->input->post('groupby');
			$groupingby = array(
				'total' => 'Total',
				'monthly' => 'Mensual',
				'weekly' => 'Semanal',
				'daily' => 'Diario',
			);

			$data['start'] = date("Y-m-d", $start);
			$data['end'] = date("Y-m-d", $end);
			$data['measure'] = $measure;
			$data['measures'] = $measures;
			$data['groupby'] = $groupby;
			$data['groupingby'] = $groupingby;
			$data['title'] = ( $measure ? $measures[$measure] : '' );
			$data['subtitle'] = $data['start'] . ' a ' . $data['end'];
			$data['chart'] = null;
			$data['table'] = null;

			if( $measure ) {
				if (isset($measures[$measure]) && method_exists($this, 'reporte_' . $measure)) {

					$chart = json_decode("" . '{
						"chart": {
							"type": "column",
							"plotBackgroundColor": null,
							"plotBorderWidth": null,
							"plotShadow": false
						},
						"credits": {
	                        "enabled": false
	                    },
						"title": null,
						"subtitle": null,
						"tooltip": {
							"formatter": "function"
						},
						"tooltipTable": {
				            "headerFormat": "<span style=\"font-size:11px;font-weight:bold\">{point.key}</span><table>",
				            "pointFormat": "<tr><td style=\"font-size:10px;color:{series.color};padding:0\">{series.name}: </td><td style=\"font-size:10px;padding:0\"><b>{point.y:.0f}</b></td></tr>",
				            "footerFormat": "</table>",
				            "shared": true,
				            "useHTML": true
				        },
						"legend": {
							"enabled": true
				        },
						"xAxis": {
							"categories": [
								"Total 1", "Total 2"
							],
							"crosshair": true,
							"labels": {
	                            "rotation": -45
				            }
						},
						"yAxis": {
							"min": 0,
							"title": {
								"text": "Número de consultas"
							},
							"stackLabels": {
				                "enabled": true,
				                "style": {
				                    "fontWeight": "bold",
				                    "color": "(Highcharts.theme && Highcharts.theme.textColor) || \'gray\'"
				                }
				            }
						},
						"plotOptions": {
							"column": {
								"pointPadding": 0.2,
								"borderWidth": 0,
								"stacking": "normal"
							}
						},
						"series": [
							{
								"name": "Serie 1",
								"data": [49.9,40],
								"stack": "male"
							},
							{
								"name": "Serie 2",
								"data": [83.6,80],
								"stack": "female"
							}
						],
						"exporting": {
	                        "enabled": true
	                    }
					}', TRUE);

					$chart['title']['text']    = $data['title'];
					$chart['subtitle']['text'] = $data['subtitle'];

					$table                        = array();
					$table['headers']             = array();
					$table['rows']                = array();
					$table['rows_options']        = array();
					$table['options']             = '';
					$chart["xAxis"]["categories"] = array();

					switch ($groupby) {
						case 'total':
							$table['headers'][] = $measures[$measure];
							$table['headers'][] = $groupingby[$groupby];

							$chart["xAxis"]["categories"][] = $groupingby[$groupby];

							break;
						case 'monthly':
							$table['headers'][] = $measures[$measure];

							for ($c = strtotime(date("Y-m", $start)); $c <= strtotime(date("Y-m", $end)); $c = strtotime("+1 MONTH", $c)) {
								$table['headers'][]             = date("Y-m", $c);
								$chart["xAxis"]["categories"][] = date("Y-m", $c);
							}

							break;
						case 'weekly':
							$table['headers'][] = $measures[$measure];

							for ($c = strtotime(date("Y-m-d", strtotime('-' . date('w', $start) . ' DAY', $start))); $c <= strtotime(date("Y-m-d", $end)); $c = strtotime("+1 WEEK", $c)) {
								$table['headers'][]             = date("Y-m-d", $c);
								$chart["xAxis"]["categories"][] = date("Y-m-d", $c);
							}

							break;
						case 'daily':
							$table['headers'][] = $measures[$measure];

							for ($c = strtotime(date("Y-m-d", $start)); $c <= strtotime(date("Y-m-d", $end)); $c = strtotime("+1 DAY", $c)) {
								$table['headers'][]             = date("Y-m-d", $c);
								$chart["xAxis"]["categories"][] = date("Y-m-d", $c);
							}

							break;
					}

					call_user_func_array(array($this, 'reporte_' . $measure),array(&$chart, &$table, $groupby, $start, strtotime("+1 DAY", $end)));

					$chart['xAxis']['labels']['rotation'] = (count($chart["xAxis"]["categories"]) < 10 ? 0 : (count($chart["xAxis"]["categories"]) < 30 ? -45 : -90));
					$data['chart']                        = $chart;
					$data['table']                        = $table;

				}
				else {
					user_error("NOT FOUND: reporte_" . $measure, E_USER_ERROR);
				}
			}

			$this->view( $data );
		}

		private function reporte_consultas_vs_psicologo (&$chart, &$table, $groupby, $start, $end) {

			$chart['series'] = array();

			$query = $this->db->query("
				SELECT a3m_account_details.fullname AS name, a3m_account_details.account_id As id
				FROM a3m_account_details, msf_consults
				WHERE a3m_account_details.account_id = msf_consults.id_creator
				AND msf_consults.creation >= ?
				AND msf_consults.creation < ?
				GROUP BY a3m_account_details.account_id
				ORDER BY a3m_account_details.fullname ASC
			", array( date('Y-m-d', $start ), date('Y-m-d', $end ) ) );

			$bars = $query->result_array();

			switch( $groupby ) {
				case 'total':
					$chart['plotOptions']['column']['stacking'] = null;
					foreach( $bars as $bar ) {
						$query = $this->db->query("
							SELECT COUNT(*) AS total
							FROM msf_consults
							WHERE msf_consults.id_creator = ?
							AND msf_consults.creation >= ?
							AND msf_consults.creation < ?
							", array( $bar['id'], date('Y-m-d', $start ), date('Y-m-d', $end ) )
						);

						$values = $query->row_array();
						$chart['series'][] = array(
							'name' => $bar['name'],
							'data' => array( (int)$values['total'] )
						);
						$table['rows'][] = array( $bar['name'], (int)$values['total'] );
					}
					break;
				case 'monthly':
					$chart['plotOptions']['column']['stacking'] = null;
					foreach( $bars as $bar ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							$query = $this->db->query("
								SELECT COUNT(*) AS total
								FROM msf_consults
								WHERE msf_consults.id_creator = ?
								AND msf_consults.creation >= ?
								AND msf_consults.creation < ?
								AND LEFT(msf_consults.creation, 7) = ?
								", array( $bar['id'], date('Y-m-d', $start ), date('Y-m-d', $end ), $category )
							);

							$values = $query->row_array();
							$data[] = (int)$values['total'];
						}

						$chart['series'][] = array(
							'name' => $bar['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $bar['name'] ), $data );
					}
					break;
				case 'weekly':
					$chart['tooltip'] = $chart['tooltipTable'];
					foreach( $bars as $bar ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							$query = $this->db->query("
								SELECT COUNT(*) AS total
								FROM msf_consults
								WHERE msf_consults.id_creator = ?
								AND msf_consults.creation >= ?
								AND msf_consults.creation < ?
								AND LEFT(DATE_ADD(msf_consults.creation, INTERVAL (MOD(DAYOFWEEK(msf_consults.creation)-1, 7)*-1) DAY), 10 ) = ?
								", array( $bar['id'], date('Y-m-d', $start ), date('Y-m-d', $end ), $category )
							);

							$values = $query->row_array();
							$data[] = (int)$values['total'];
						}

						$chart['series'][] = array(
							'name' => $bar['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $bar['name'] ), $data );
					}
					break;
				case 'daily':
					$chart['tooltip'] = $chart['tooltipTable'];
					foreach( $bars as $bar ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							$query = $this->db->query("
								SELECT COUNT(*) AS total
								FROM msf_consults
								WHERE msf_consults.id_creator = ?
								AND LEFT(msf_consults.creation, 10) = ?
								", array( $bar['id'], $category )
							);

							$values = $query->row_array();
							$data[] = (int)$values['total'];
						}

						$chart['series'][] = array(
							'name' => $bar['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $bar['name'] ), $data );
					}

					break;
			}

		}

		private function reporte_educacion_vs_diagnostico (&$chart, &$table, $groupby, $start, $end) {

			$chart['series'] = array();
			$newCategories = array();

			$query = $this->db->query("
				SELECT msf_educations.name AS name, msf_educations.id As id
				FROM msf_educations, msf_patients, msf_consults
				WHERE msf_patients.id_education = msf_educations.id
				AND msf_patients.id = msf_consults.id_patient
				AND msf_consults.creation >= ?
				AND msf_consults.creation < ?
				GROUP BY msf_educations.id
				ORDER BY msf_educations.name ASC
			", array( date('Y-m-d', $start ), date('Y-m-d', $end ) ) );
			$bars = $query->result_array();

			$table['headers'] = array( $table['headers'][0] );
			foreach( $chart["xAxis"]["categories"] as $category ) {
				foreach( $bars as $bar ) {
					$newCategories[] = $bar['name'] . ' - ' . $category;
					$table['headers'][] = $bar['name'] . ' - ' . $category;
				}
			}

			$query = $this->db->query("
				SELECT msf_diagnostics.name AS name, msf_diagnostics.id As id
				FROM msf_diagnostics, msf_consults
				WHERE msf_diagnostics.id = msf_consults.id_diagnostic
				AND msf_consults.creation >= ?
				AND msf_consults.creation < ?
				GROUP BY msf_diagnostics.id
				ORDER BY msf_diagnostics.name ASC
			", array( date('Y-m-d', $start ), date('Y-m-d', $end ) ) );
			$stacks = $query->result_array();

			switch( $groupby ) {
				case 'total':
					$chart['plotOptions']['column']['stacking'] = null;
					foreach( $stacks as $stack ) {
						$data = array( );
						foreach( $bars as $bar ) {
							$query = $this->db->query("
								SELECT COUNT(*) AS total
								FROM msf_patients, msf_consults
								WHERE msf_patients.id = msf_consults.id_patient
								AND msf_patients.id_education = ?
								AND msf_consults.id_diagnostic = ?
								AND msf_consults.creation >= ?
								AND msf_consults.creation < ?
								", array( $bar['id'], $stack['id'], date('Y-m-d', $start ), date('Y-m-d', $end ) )
							);

							$values = $query->row_array();
							$data[] = (int)$values['total'];
						}

						$chart['series'][] = array(
							'name' => $stack['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $stack['name'] ), $data );
					}
					break;
				case 'monthly':
					$chart['plotOptions']['column']['stacking'] = null;
					foreach( $stacks as $stack ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							foreach( $bars as $bar ) {
								$query = $this->db->query("
									SELECT COUNT(*) AS total
									FROM msf_patients, msf_consults
									WHERE msf_patients.id = msf_consults.id_patient
									AND msf_patients.id_education = ?
									AND msf_consults.id_diagnostic = ?
									AND msf_consults.creation >= ?
									AND msf_consults.creation < ?
									AND LEFT(msf_consults.creation, 7) = ?
									", array( $bar['id'], $stack['id'], date('Y-m-d', $start ), date('Y-m-d', $end ), $category )
								);

								$values = $query->row_array();
								$data[] = (int)$values['total'];
							}
						}

						$chart['series'][] = array(
							'name' => $stack['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $stack['name'] ), $data );
					}
					break;
				case 'weekly':
					$chart['tooltip'] = $chart['tooltipTable'];
					foreach( $stacks as $stack ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							foreach( $bars as $bar ) {
								$query = $this->db->query("
									SELECT COUNT(*) AS total
									FROM msf_patients, msf_consults
									WHERE msf_patients.id = msf_consults.id_patient
									AND msf_patients.id_education = ?
									AND msf_consults.id_diagnostic = ?
									AND msf_consults.creation >= ?
									AND msf_consults.creation < ?
									AND LEFT(DATE_ADD(msf_consults.creation, INTERVAL (MOD(DAYOFWEEK(msf_consults.creation)-1, 7)*-1) DAY), 10 ) = ?
									", array( $bar['id'], $stack['id'], date('Y-m-d', $start ), date('Y-m-d', $end ), $category )
								);

								$values = $query->row_array();
								$data[] = (int)$values['total'];
							}
						}

						$chart['series'][] = array(
							'name' => $stack['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $stack['name'] ), $data );
					}
					break;
				case 'daily':
					$chart['tooltip'] = $chart['tooltipTable'];
					foreach( $stacks as $stack ) {
						$data = array( );
						foreach( $chart["xAxis"]["categories"] as $category ) {
							foreach( $bars as $bar ) {
								$query = $this->db->query("
									SELECT COUNT(*) AS total
									FROM msf_patients, msf_consults
									WHERE msf_patients.id = msf_consults.id_patient
									AND msf_patients.id_education = ?
									AND msf_consults.id_diagnostic = ?
									AND LEFT(msf_consults.creation, 10) = ?
									", array( $bar['id'], $stack['id'], $category )
								);

								$values = $query->row_array();
								$data[] = (int)$values['total'];
							}
						}

						$chart['series'][] = array(
							'name' => $stack['name'],
							'data' => $data
						);

						$table['rows'][] = array_merge( array( $stack['name'] ), $data );
					}
					break;
			}

			$chart["xAxis"]["categories"] = $newCategories;

		}

		function index2() {

			$data = $this->auth( '', array(
				'generate_reports' => 'account/account_profile',
				'view_reports' => 'account/account_profile'
			) );

			$start = $this->input->post('start');
			if( !$start )
				$start = date('Y-m-d', strtotime( "-1 MONTH" ) );
			$start = strtotime( $start );

			$end = $this->input->post('end');
			if( !$end )
				$end = date('Y-m-d' );
			$end = strtotime( $end );

			$measure = $this->input->post('measure');

			$measures = array(
				'patients.age_group.id_patient' => 'Grupo de Edad',
			);

			$groupby = $this->input->post('groupby');
			$groupingby = array(
				'total' => 'Total',
				'monthly' => 'Mensual',
				'weekly' => 'Semanal',
				'daily' => 'Diario',
			);

			$data['start'] = date("Y-m-d", $start);
			$data['end'] = date("Y-m-d", $end);
			$data['measure'] = $measure;
			$data['measures'] = $measures;
			$data['groupby'] = $groupby;
			$data['groupingby'] = $groupingby;
			$data['title'] = ( $measure ? $measures[$measure] : '' );
			$data['subtitle'] = $data['start'] . ' a ' . $data['end'];
			$data['chart'] = null;
			$data['table'] = null;

			if( $measure ) {

				$chart = json_decode("" .
				'{
					"chart": {
						"type": "column",
						"plotBackgroundColor": null,
						"plotBorderWidth": null,
						"plotShadow": false
					},
					"credits": {
                        "enabled": false
                    },
					"title": {
						"text": "Monthly Average Rainfall"
					},
					"subtitle": {
						"text": "Source: WorldClimate.com"
					},
					"tooltip": {
						"formatter": "function(){}"
					},
					"legend": {
						"enabled": true
			        },
					"xAxis": {
						"categories": [
							"Total 1", "Total 2"
						],
						"crosshair": true,
						"labels": {
                            "rotation": -45
			            }
					},
					"yAxis": {
						"min": 0,
						"title": {
							"text": "Número de consultas"
						},
						"stackLabels": {
			                "enabled": true,
			                "style": {
			                    "fontWeight": "bold",
			                    "color": "(Highcharts.theme && Highcharts.theme.textColor) || \'gray\'"
			                }
			            }
					},
					"plotOptions": {
						"column": {
							"pointPadding": 0.2,
							"borderWidth": 0,
							"stacking": "normal"
						}
					},
					"series": [
						{
							"name": "Serie 1",
							"data": [49.9],
							"stack": "male"
						},
						{
							"name": "Serie 2",
							"data": [83.6],
							"stack": "female"
						}
					],
					"exporting": {
                        "enabled": true
                    }
				}', true);

				$chart['title']['text'] = $data['title'];
				$chart['subtitle']['text'] = $data['subtitle'];

				$table = array( );
				$table['headers'] = array();
				$table['rows'] = array( );
				$table['rows_options'] = array( );
				$table['options'] = '';
				$chart["xAxis"]["categories"] = array();


				$parts = explode('.', $measure);
				$filterName = $parts[0] . '_model';
				$filterField = $parts[1];
				$modelField = ( count($parts) > 2 ? $parts[2] : null );

				$this->load->model( array(
						'manage/' . $filterName
				) );

				/**
				 * @var $model Consults_model
				 */
				$model = $this->consults_model;
				/**
				 * @var $filterModel General_model
				 */
				$filterModel = $this->$filterName;

				$model->db->where( $model->getTableName() . '.creation >= ', date("Y-m-d", $start) );
				$model->db->where( $model->getTableName() . '.creation < ', date("Y-m-d", strtotime( "+1 DAY", $end )) );

				$select = array(
					'COUNT(*) AS value',
				);

				switch( $measure ) {
					case 'patients.age_group.id_patient';
						$select[] = "(
							IF( msf_patients.age IS NULL OR msf_patients.age < 5, '<5',
								IF( msf_patients.age <= 14, '5-14',
									IF( msf_patients.age <= 18, '5-18',
										'≥19'
									)
								)
							)
						) AS name";
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.' . $modelField . ' = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );
						break;
					case 'account_details.fullname.id_creator';
						$select[] = 'a3m_account_details.' . $filterField . ' AS name';
						$model->db->join( 'a3m_account_details', $model->getTableName() . '.' . $modelField . ' = ' . 'a3m_account_details.account_id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );
						break;
					case 'localizations.name.id_patient';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$model->db->join( 'msf_patients', $model->getTableName() . '.' . $modelField . ' = msf_patients.id' );
						$model->db->join( $filterModel->getTableName(), 'msf_patients.id_localization = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'references.name.id_patient';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$model->db->join( 'msf_patients_references', $model->getTableName() . '.' . $modelField . ' = msf_patients_references.id_patient' );
						$model->db->join( $filterModel->getTableName(), 'msf_patients_references.id_reference = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );
						break;
					case 'consults.id';
						$select[] = "'Sesiones' AS name";
						break;
					case 'patients.last_session.id_patient';
						$select[] = "DATEDIFF(" . $filterModel->getTableName() . ".last_session," . $filterModel->getTableName() . ".first_session) AS name";
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.' . $modelField . ' = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );
						break;
					case 'consults.id_closure';
						$select[] = "'Seguimientos' AS name";
						$model->db->where('id_closure IS NULL');
						$model->db->having('name >= 0');
						break;
					case 'patients.id_education.id_patient';
						$select[] = 'msf_educations.name AS name';
						$select[] = 'msf_diagnostics.name AS subname';
						//$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.' . $modelField . ' = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_diagnostics', $model->getTableName() . '.id_diagnostic = msf_diagnostics.id' );
						$model->db->join( 'msf_educations', $filterModel->getTableName() . '.' . $filterField . ' = ' . 'msf_educations.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'consults_types.name.operation_reduction';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$select[] = "CONCAT('Reducción '," . $model->getTableName() . '.' . $modelField . ') AS subname';

						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_consults_type = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'risks.name.id_patient';
						$select[] = $model->getTableName() . '.' . $modelField . ' AS name';
						$select[] = 'msf_risks.name AS subname';
						$model->db->join( 'msf_consults_risks', 'msf_consults_risks.id_consult = ' . $model->getTableName() . '.id' );
						$model->db->join( $filterModel->getTableName(), 'msf_consults_risks.id_risk = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						$model->db->having( 'value <= 3' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'patients.age_group.diagnostics';
						$select[] = "(
 							IF( msf_patients.age IS NULL OR msf_patients.age < 5, '<5',
								IF( msf_patients.age <= 14, '5-14',
									IF( msf_patients.age <= 18, '5-18',
										'≥19'
									)
								)
							)
						 ) AS name";
						$select[] = 'msf_diagnostics.name AS subname';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_patient = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_diagnostics', $model->getTableName() . '.id_diagnostic = msf_diagnostics.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						////$chart["legend"]["enabled"] = false;
						break;
					case 'patients.gender.diagnostics';
						$select[] = "msf_patients.gender AS name";
						$select[] = 'msf_diagnostics.name AS subname';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_patient = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_diagnostics', $model->getTableName() . '.id_diagnostic = msf_diagnostics.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						////$chart["legend"]["enabled"] = false;
						break;
					case 'patients.age_group.risks_categories';
						$select[] = "(
 							IF( msf_patients.age IS NULL OR msf_patients.age < 5, '<5',
								IF( msf_patients.age <= 14, '5-14',
									IF( msf_patients.age <= 18, '5-18',
										'≥19'
									)
								)
							)
						 ) AS name";
						$select[] = 'msf_risks_categories.name AS subname';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_patient = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_risks_categories', $model->getTableName() . '.id_risks_category = msf_risks_categories.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						////$chart["legend"]["enabled"] = false;
						break;
					case 'patients.gender.risks_categories';
						$select[] = "msf_patients.gender AS name";
						$select[] = 'msf_risks_categories.name AS subname';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_patient = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_risks_categories', $model->getTableName() . '.id_risks_category = msf_risks_categories.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						////$chart["legend"]["enabled"] = false;
						break;
					case 'diagnostics.name.risks_categories';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$select[] = 'msf_risks_categories.name AS subname';
						$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.id_diagnostic = ' . $filterModel->getTableName() . '.id' );

						$model->db->join( 'msf_risks_categories', 'msf_risks_categories.id = ' . $model->getTableName() . '.id_risks_category' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'localizations.name.gender';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$select[] = "msf_patients.gender AS subname";
						$model->db->join( 'msf_patients', $model->getTableName() . '.id_patient = msf_patients.id' );
						$model->db->join( $filterModel->getTableName(), 'msf_patients.id_localization = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'localizations.name.symptoms';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$select[] = "msf_symptoms.name AS subname";
						$model->db->join( 'msf_patients', $model->getTableName() . '.id_patient = msf_patients.id' );
						$model->db->join( $filterModel->getTableName(), 'msf_patients.id_localization = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_consults_symptoms', $model->getTableName() . '.id = msf_consults_symptoms.id_consult' );
						$model->db->join( 'msf_symptoms', 'msf_consults_symptoms.id_symptom = msf_symptoms.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					case 'localizations.name.risks_categories';
						$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						$select[] = 'msf_risks_categories.name AS subname';
						$model->db->join( 'msf_patients', $model->getTableName() . '.id_patient = msf_patients.id' );
						$model->db->join( $filterModel->getTableName(), 'msf_patients.id_localization = ' . $filterModel->getTableName() . '.id' );
						$model->db->join( 'msf_risks_categories', $model->getTableName() . '.id_risks_category = msf_risks_categories.id' );
						$model->db->group_by( 'name,subname' );
						$model->db->order_by( 'name,subname' );
						//$chart["legend"]["enabled"] = false;
						break;
					default:
						if( $modelField == '_boolean' )
							$select[] = "(CASE WHEN " . $filterModel->getTableName() . "." . $filterField . " <> 0 THEN 'SÍ' ELSE 'NO' END) AS name";
						else
							$select[] = $filterModel->getTableName() . '.' . $filterField . ' AS name';
						if( $filterModel != $model )
							$model->db->join( $filterModel->getTableName(), $model->getTableName() . '.' . $modelField . ' = ' . $filterModel->getTableName() . '.id' );
						$model->db->group_by( 'name' );
						$model->db->order_by( 'name' );

				}

				switch( $groupby ) {
					case 'total':
						$table['headers'][] = $measures[$measure];
						$table['headers'][] = $groupingby[$groupby];

						$chart["xAxis"]["categories"][] = $groupingby[$groupby];

						$select[] = "'" . $groupingby[$groupby] . "' AS grouping";
						break;
					case 'monthly':
						$table['headers'][] = $measures[$measure];

						for( $c = strtotime(date("Y-m", $start)); $c <= strtotime(date("Y-m", $end)); $c = strtotime( "+1 MONTH", $c ) ) {
							$table['headers'][] = date("Y-m", $c);
							$chart["xAxis"]["categories"][] = date("Y-m", $c);
						}

						$select[] = 'LEFT(' . $model->getTableName() . '.creation, 7) AS grouping';

						$model->db->group_by('grouping');
						$model->db->order_by('grouping');
						break;
					case 'weekly':
						$table['headers'][] = $measures[$measure];

						for( $c = strtotime(date("Y-m-d", strtotime('-' . date('w', $start) . ' DAY', $start))); $c <= strtotime(date("Y-m-d", $end)); $c = strtotime( "+1 WEEK", $c ) ) {
							$table['headers'][] = date("Y-m-d", $c);
							$chart["xAxis"]["categories"][] = date("Y-m-d", $c);
						}

						$select[] = 'LEFT(DATE_ADD(' . $model->getTableName() . '.creation, INTERVAL (MOD(DAYOFWEEK(' . $model->getTableName() . '.creation)-1, 7)*-1) DAY), 10 ) AS grouping';

						$model->db->group_by('grouping');
						$model->db->order_by('grouping');
						break;
					case 'daily':
						$table['headers'][] = $measures[$measure];

						for( $c = strtotime(date("Y-m-d", $start)); $c <= strtotime(date("Y-m-d", $end)); $c = strtotime( "+1 DAY", $c ) ) {
							$table['headers'][] = date("Y-m-d", $c);
							$chart["xAxis"]["categories"][] = date("Y-m-d", $c);
						}

						$select[] = 'LEFT(' . $model->getTableName() . '.creation, 10) AS grouping';

						$model->db->group_by('grouping');
						$model->db->order_by('grouping');
						break;
				}

				$model->db->select(implode(',', $select), FALSE);
				$results = $model->db->get($model->getTableName())->result_array( );
				//var_dump( $model->db->last_query() );
				//exit();
				$dbSeries = array();
				$series = array();

				foreach( $results as $result ) {
					if( !isset($dbSeries[$result['name']]) )
						$dbSeries[$result['name']] = array();
					$tserie = &$dbSeries[$result['name']];

					if( isset($result['subname']) ) {
						if( !isset($tserie[$result['subname']]) )
							$tserie[$result['subname']] = array();
						$tserie = &$tserie[$result['subname']];
					}

					$tserie[$result['grouping']] = (float)$result['value'];
				}

				foreach( $dbSeries as $rname => $rsubname ) {
					$temp = array_values( $rsubname );
					if( is_array( $temp[0] ) ) {
						foreach ($rsubname as $rstack => $rgroups) {
							$serie = array(
									'name' => $rstack,
									'data' => array(),
									'stack' => $rname
							);
							$row = array($rname . ' - ' . $rstack );

							foreach( $chart["xAxis"]["categories"] as $category ) {
								$serie['data'][] = ( isset( $rgroups[$category] ) ? $rgroups[$category] : 0.0 );
								$row[] = ( isset( $rgroups[$category] ) ? $rgroups[$category] : 0.0 );
							}

							$series[] = $serie;
							$table['rows'][] = $row;
						}

					}
					else {
						$rgroups = $rsubname;
						$serie = array(
							'name' => $rname,
							'data' => array()
						);
						$row = array($rname);

						foreach( $chart["xAxis"]["categories"] as $category ) {
							$serie['data'][] = ( isset( $rgroups[$category] ) ? $rgroups[$category] : 0.0 );
							$row[] = ( isset( $rgroups[$category] ) ? $rgroups[$category] : 0.0 );
						}

						$series[] = $serie;
						$table['rows'][] = $row;
					}
				}

				$chart['xAxis']['labels']['rotation'] = ( count( $chart["xAxis"]["categories"] ) < 10 ? 0 : ( count( $chart["xAxis"]["categories"] ) < 30 ? -45 : -90 ) );
				$chart['series'] = array_values( $series );
				$data['chart'] = $chart;
				$data['table'] = $table;

				//var_dump( $chart['series'] );
				//exit();
			}

			$this->view( $data );
		}
	}

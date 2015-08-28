<?php

	require_once( APPPATH . "controllers/general_controller.php" );

	/**
	 * Class Export
	 *
	 */
	class Excel extends GeneralController {

		function __construct() {
			parent::__construct();

			// Load the necessary stuff...
			$this->load->library( array(
			    'PHPExcel'
			) );
			$this->load->model( array(
			    'manage/patients_model',
			    'manage/patients_references_model',
			    'manage/consults_model',
			    'manage/consults_symptoms_model',
			    'manage/consults_risks_model',
			) );
		}

		function index() { }

		function DB () {

			$data = $this->auth( '', array(
				'generate_reports' => 'account/account_profile'
			) );

			$props = array(
				'title' => 'MSF - DB - CallCenter',
				'headers' => array('Serial'),
				'data' => array(array('Serial' => 10)),
				'start' => $this->input->get_post('start',true),
				'end' => $this->input->get_post('end',true)
			);

			$this->exportExcel( $props );

		}

		protected function exportExcel( $props ) {

			$props = array_merge( array(
				'title' => '', // Título
				'subtitle' => '', // Subtitulo que se pegará al título
				'headers' => null,
				'data' => null,
			), $props );

			if( $props['subtitle'] )
				$props['title'] .= ' - ' . $props['subtitle'];

			PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
			$logoPath = 'resources/img/logo.png';

			$props['doc'] = array_merge(
				array(
					'title' => 'MSF',
					'subject' => 'Reporte',
					'description' => 'Reporte de sistema',
					'keywords' => 'Reporte',
					'category' => 'Reporte',
				)
				,( isset( $props['doc'] ) && is_array( $props['doc'] ) ? $props['doc'] : array( ) )
			);

			$title = preg_replace( '/([^a-z0-9áéíóúàèìòùâêîôûñ\-_ ]+)/i', '', ( isset( $props['title'] ) ? $props['title'] : $props['doc']['title'] ) );
			if( strlen( $title ) > 30 )
				$title = substr( $title, 0, strripos( $title, " ", 30 - strlen( $title ) ) );

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setCreator("MSF System")
			            ->setLastModifiedBy("MSF System")
			            ->setTitle( $props['doc']['title'] )
			            ->setSubject( $props['doc']['subject'] )
			            ->setDescription( $props['doc']['description'] )
			            ->setKeywords( $props['doc']['keywords'] )
			            ->setCategory( $props['doc']['category'] );

			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getDefaultStyle()->getFont()
			            ->setName('Verdana')
			            ->setSize(8)
			            ->setColor( new PHPExcel_Style_Color( 'FF333333' ) );

			$sheet = $objPHPExcel->getActiveSheet( );

			// Set page orientation, size, Print Area and Fit To Pages
			$objPageSetup = new PHPExcel_Worksheet_PageSetup();
			$objPageSetup->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
			$objPageSetup->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			$objPageSetup->setFitToWidth(1);

			$sheet->setPageSetup($objPageSetup);
			$sheet->setTitle( $title );

			$sheet->getDefaultStyle()->applyFromArray(array(
				'alignment' => array(
					'wrap' => false
				)
			));

			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setWorksheet($sheet);
			$objDrawing->setName("logo");
			$objDrawing->setDescription("MSF");

			// LOGO
			$objDrawing->setPath($logoPath);
			$objDrawing->setCoordinates('A1');
			$objDrawing->setWidth(104);
			$objDrawing->setHeight(45);


			$styleHeader = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FF222222'),
					'size'  => 10,
					'name'  => 'Verdana'
				),
				'alignment' => array(
					'wrap' => false,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$styleHeaderTable = array(
				'font'  => array(
					'bold'  => FALSE,
					'color' => array('rgb' => 'FF222222'),
					'size'  => 10,
					'name'  => 'Verdana'
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color'		=> array( 'argb' => 'FF333333' )
					)
				),
				'alignment' => array(
					'wrap' => false,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$styleTitle = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'FF333333'),
					'size'  => 8,
					'name'  => 'Verdana'
				),
				'fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array( 'argb' => 'FFCCCCCC' )
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color'		=> array( 'argb' => 'FF666666' )
					)
				),
				'alignment' => array(
					'wrap' => false,
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
			);

			$styleText = array(
				'alignment' => array(
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
				),
			);


			$col = 2;
			$fil = 1;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'MSF OCBA Mental Health DATABASE' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );

			$styleHeader['font']['bold'] = false;

			$col += 4;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Proyecto:' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'País:' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Intervalo:' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );

			$col += 3;
			$fil = 1;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Cauca - Pacífico' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Colombia' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, $props['start'] .  ' - ' . $props['end'] );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );

			$col = 0;
			$fil = 5;
			$colMax = 0;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '1ra Visita' );
			$styleTitle['fill']['color'] = array( 'argb' => 'FFFDCB01' );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleTitle);
			$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
			$fil++;


			$patients = $this->patients_model->getAllBetween( $props['start'], $props['end'] );

			/*
			$this->load->model( array(
				'manage/cities_model'
			) );
			$objects = $this->cities_model->get( );

			echo "<pre>" . print_r($objects,true) . "</pre>";

			echo "<pre>" . print_r($this->patients_model->db->last_query(),true) . "</pre>";
			echo "<pre>" . print_r($patients,true) . "</pre>";
			*/

			$headers = array(
				'first_session' => 'Fecha de 1ª sesión',
				'reopen_date' => 'Fecha de reapertura',
				'region' => 'Region del Proyecto',
				'localization' => 'Localización de la intervención',
				'code' => 'Código del paciente',
				'expert' => 'Nombre del consejero / psicólogo / psiquiatra',
				'gender' => 'Género',
				'age' => 'Edad',
				'age_group' => 'Grupo de Edad',
				'education' => 'Educación',
				'origin_place' => 'Lugar de Origen',
			);

			$col = 0;
			$sheet->setCellValueByColumnAndRow( $col, $fil, 'Serial No.' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
			$col ++;
			foreach( $headers as $key => $value ) {
				$sheet->setCellValueByColumnAndRow( $col, $fil, $value );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
				$col ++;
			}
			// References
			$totalReferences = $this->patients_model->getMaxReferences( $props['start'], $props['end'], 1 );
			for( $c = 0; $c < $totalReferences; $c ++  ) {
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Referido DESDE ' . ($c + 1 ) );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
				$col ++;
			}
			$sheet->mergeCellsByColumnAndRow( 0, $fil - 1, $col - 1, $fil - 1 );

			// Consults (Not closures)
			$totalConsults = $this->patients_model->getMaxConsultsOpened( $props['start'], $props['end'] );
			$totalConsultsSymptoms = $this->patients_model->getMaxConsultsSymptoms( $props['start'], $props['end'] ) + 8;
			$totalConsultsRisks = $this->patients_model->getMaxConsultsRisks( $props['start'], $props['end'] );

			$fil --;
			for( $c = 0; $c < $totalConsults; $c ++  ) {
				if( $c == 0 ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, '1ra. Consulta ' . $totalConsultsSymptoms . '-' . $totalConsultsRisks );
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + $totalConsultsSymptoms + $totalConsultsRisks - 1, $fil + 1 );
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFDCB01' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);

					$sheet->setCellValueByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks, $fil, 'Seguimientos' );
					$sheet->mergeCellsByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks, $fil, $col + ( $totalConsults * ( $totalConsultsSymptoms + $totalConsultsRisks ) ) - 1, $fil );
					$styleTitle['fill']['color'] = array( 'argb' => 'FF3366FE' );
					$sheet->getStyleByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks, $fil )->applyFromArray($styleTitle);
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
					$fil ++;
				}
				else {
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Seguimiento ' . $c );
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + $totalConsultsSymptoms + $totalConsultsRisks - 1, $fil );
					$styleTitle['fill']['color'] = array( 'argb' => 'FF' . ( $c % 2 > 0 ? 'BFBFBF' : '98CBFE' ) );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
				}
				$fil ++;
				for( $d = 0; $d < $totalConsultsSymptoms - 7; $d ++  ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, ( $d == 0 ? 'Categoría de los síntomas' : 'Síntoma ' . $d ) );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
					$col ++;
				}
				for( $d = 0; $d < $totalConsultsRisks; $d ++  ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, ( $d == 0 ? 'Categoría de los Eventos' : 'Evento ' . $d ) );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
					$col ++;
				}

				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Tipo de Consulta' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Tipo de Intervención' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Fecha' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Severidad de los Sintomas' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
					$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Direfencia' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$fil --;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Reducción de Funcionamiento' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
					$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Direfencia' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$fil --;

				$fil --;
			}

			$fil += 2;
			$colMax = max($colMax, $col);



			$fil ++;
			foreach( $patients as $pos => $row ) {
				$col = 0;
				$sheet->setCellValueByColumnAndRow( $col, $fil, $pos + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleText);
				$col ++;
				foreach( $headers as $key => $value ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, $row[$key] );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleText);
					$col ++;
				}
				// References
				$this->patients_references_model->db->where('msf_references.id_category',1);
				$references = $this->patients_references_model->get_by_id_patient( $row['id'] );
				foreach( $references as $c => $reference ) {
					$sheet->setCellValueByColumnAndRow( $col + $c, $fil, $reference['reference'] );
					$sheet->getStyleByColumnAndRow( $col + $c, $fil )->applyFromArray($styleText);
				}
				$col += $totalReferences;

				$consults = $this->consults_model->getOpenedFor( $row['id'] );

				foreach( $consults as $consultIndex => $consult ) {

					$colSymptoms = $col;
					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['symptoms_category'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$symptoms = $this->consults_symptoms_model->get_by_id_consult( $consult['id'] );
					foreach( $symptoms as $symptom ) {
						$sheet->setCellValueByColumnAndRow($colSymptoms, $fil, $symptom['symptom'] );
						$sheet->getStyleByColumnAndRow($colSymptoms, $fil )->applyFromArray($styleText);
						$colSymptoms ++;
					}

					$colSymptoms = $col + $totalConsultsSymptoms - 7;
					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['risks_category'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$risks = $this->consults_risks_model->get_by_id_consult( $consult['id'] );
					foreach( $risks as $risk ) {
						$sheet->setCellValueByColumnAndRow($colSymptoms, $fil, $risk['risk'] );
						$sheet->getStyleByColumnAndRow($colSymptoms, $fil )->applyFromArray($styleText);
						$colSymptoms ++;
					}


					$colSymptoms = $col + $totalConsultsSymptoms + $totalConsultsRisks - 7;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['consults_type'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['interventions_type'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['creation'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['symptoms_severity'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( $consultIndex > 0 ? $consults[$consultIndex - 1]['symptoms_severity'] : '' ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['operation_reduction'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( $consultIndex > 0 ? $consults[$consultIndex - 1]['operation_reduction'] : '' ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;


					$col += $totalConsultsSymptoms + $totalConsultsRisks;
				}

				$fil ++;
			}
			$colMax = max($colMax, $col);


			//$sheet->mergeCellsByColumnAndRow( 0, 1, $colMax - 1, 1 );
			//$sheet->mergeCellsByColumnAndRow( 0, 3, $colMax - 1, 3 );

			for( $c = 0; $c < $colMax; $c ++ ) {
				//$sheet->getColumnDimensionByColumn($c)->setAutoSize(true);

				$calculatedWidth = $sheet->getColumnDimensionByColumn($c)->getWidth();
				$sheet->getColumnDimensionByColumn($c)->setAutoSize(false);
				$sheet->getColumnDimensionByColumn($c)->setWidth( max( (int) $calculatedWidth * 1.05, 12 ) );
			}
			$sheet->calculateColumnWidths();

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			//header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			//header('Cache-Control: max-age=1');

			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
			$objWriter->save('php://output');
			
			
			exit( );
		}

	}

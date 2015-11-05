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
				'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => 'FF333333'),
					'size'  => 8,
					'name'  => 'Verdana'
				),
				'alignment' => array(
					'wrap' => true,
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
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'País:' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Intervalo:' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 2, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeader);

			$col += 3;
			$fil = 1;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Cauca - Pacífico' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, 'Colombia' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);
			$fil++;
			$sheet->setCellValueByColumnAndRow($col, $fil, $props['start'] .  ' - ' . $props['end'] );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 3, $fil );
			$sheet->getStyleByColumnAndRow($col, $fil )->applyFromArray($styleHeaderTable);

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
				'city' => 'Localización de la intervención',
				'localization' => 'Lugar de Origen',
				'code' => 'Código del paciente',
				'expert' => 'Nombre del consejero / psicólogo / psiquiatra',
				'gender' => 'Género',
				'age' => 'Edad',
				'age_group' => 'Grupo de Edad',
				'education' => 'Educación',
				'origin_place' => 'Lugar de Origen',
			);

			$col = 0;
			$fil += 2;
			$sheet->setCellValueByColumnAndRow( $col, $fil, 'Serial No.' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$col ++;
			foreach( $headers as $key => $value ) {
				$sheet->setCellValueByColumnAndRow( $col, $fil, $value );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
			}
			// References
			$totalReferences = $this->patients_model->getMaxReferences( $props['start'], $props['end'], 1 );
			for( $c = 0; $c < $totalReferences; $c ++  ) {
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Referido DESDE ' . ($c + 1 ) );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 2 );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
			}
			$fil -= 2;
			$sheet->mergeCellsByColumnAndRow( 0, $fil - 1, $col - 1, $fil + 1 );

			// Consults (Not closures)
			$totalConsults = $this->patients_model->getMaxConsultsOpened( $props['start'], $props['end'] );
			$totalConsultsSymptoms = 0;//$this->patients_model->getMaxConsultsSymptoms( $props['start'], $props['end'] );
			$totalConsultsRisks = 0;//$this->patients_model->getMaxConsultsRisks( $props['start'], $props['end'] );
			$totalCamposExtra = 7;

			$fil --;
			for( $c = 0; $c < $totalConsults; $c ++  ) {
				if( $c == 0 ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, "1ra. Consulta " );
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra - 1, $fil + 1 );
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFDCB01' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);

					$sheet->setCellValueByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra, $fil, 'Seguimientos' );
					$sheet->mergeCellsByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra, $fil, $col + ( $totalConsults * ( $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra ) ) - 1, $fil );
					$styleTitle['fill']['color'] = array( 'argb' => 'FF3366FE' );
					$sheet->getStyleByColumnAndRow( $col + $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra, $fil )->applyFromArray($styleTitle);
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
					$fil ++;
				}
				else {
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Seguimiento ' . $c );
					$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra - 1, $fil );
					$styleTitle['fill']['color'] = array( 'argb' => 'FF' . ( $c % 2 > 0 ? 'BFBFBF' : '98CBFE' ) );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
				}
				$fil ++;

				/*
				var_dump($totalConsultsSymptoms);
				for( $d = 0; $d < $totalConsultsSymptoms + 1; $d ++  ) {
					var_dump(1);
					$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$sheet->setCellValueByColumnAndRow( $col, $fil + 1, ( $d == 0 ? 'Categoría de los síntomas' : 'Síntoma ' . $d ) );
					//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
					$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
					$col ++;
				}
				*/
				/*
				for( $d = 0; $d < $totalConsultsRisks + 1; $d ++  ) {
					$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$sheet->setCellValueByColumnAndRow( $col, $fil + 1, ( $d == 0 ? 'Categoría de los Eventos' : 'Evento ' . $d ) );
					//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
					$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
					$col ++;
				}
				*/

				/*
				$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Diagnóstico' );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
				$col ++;
				*/

				$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Tipo de Consulta' );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
				$col ++;

				$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Tipo de Intervención' );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
				$col ++;

				$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Fecha' );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Severidad de los Sintomas' );
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					if( $c > 0 ) {
						$sheet->setCellValueByColumnAndRow( $col, $fil, 'Diferencia' );
						$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					}
					else {
						$sheet->mergeCellsByColumnAndRow( $col - 1, $fil, $col, $fil );
					}
					$col ++;
					$fil --;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Reducción de Funcionamiento' );
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					if( $c > 0 ) {
						$sheet->setCellValueByColumnAndRow($col, $fil, 'Diferencia');
						$sheet->getStyleByColumnAndRow($col, $fil)->applyFromArray($styleTitle);
					}
					else {
						$sheet->mergeCellsByColumnAndRow( $col - 1, $fil, $col, $fil );
					}
					$col++;
					$fil --;

				/*
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Referido' );
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Hacia' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Fecha' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$fil --;

				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Psicotrópicos' );
				$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$fil ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Uso' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$sheet->setCellValueByColumnAndRow( $col, $fil, 'Fecha' );
					$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
					$col ++;
					$fil --;

				$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Observaciones' );
				//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
				$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
				$col ++;
				*/

				$fil --;
			}
			$fil --;

			$sheet->setCellValueByColumnAndRow( $col, $fil, "Cierre" );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 11 - 1, $fil + 1 );
			$styleTitle['fill']['color'] = array( 'argb' => 'FF33aE66' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$styleTitle['fill']['color'] = array( 'argb' => 'FFFFFFFF' );
			$fil += 2;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Fecha de Cierre' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Tipo de Cierre' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Condición del Paciente a la salida' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Tipo de Consulta' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, 'Severidad de los Sintomas' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$fil ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Diferencia' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
				$fil --;
			$sheet->setCellValueByColumnAndRow( $col, $fil, 'Reducción de Funcionamiento' );
			$sheet->mergeCellsByColumnAndRow( $col, $fil, $col + 1, $fil );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$fil ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Ratio' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
				$sheet->setCellValueByColumnAndRow( $col, $fil, 'Diferencia' );
				$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
				$col ++;
				$fil --;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Total Sesiones' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Duración de la intervención' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$sheet->setCellValueByColumnAndRow( $col, $fil, '' );
			$sheet->getStyleByColumnAndRow( $col, $fil )->applyFromArray($styleTitle);
			$sheet->setCellValueByColumnAndRow( $col, $fil + 1, 'Comentarios' );
			//$sheet->mergeCellsByColumnAndRow( $col, $fil, $col, $fil + 1 );
			$sheet->getStyleByColumnAndRow( $col, $fil + 1 )->applyFromArray($styleTitle);
			$col ++;

			$fil ++;
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
				$colTmp = $col;

				$consults = $this->consults_model->getOpenedFor( $row['id'] );

				foreach( $consults as $consultIndex => $consult ) {

					$colSymptoms = $col;
					/*
					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['symptoms_category'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$symptoms = $this->consults_symptoms_model->get_by_id_consult( $consult['id'] );
					foreach( $symptoms as $symptom ) {
						$sheet->setCellValueByColumnAndRow($colSymptoms, $fil, $symptom['symptom'] );
						$sheet->getStyleByColumnAndRow($colSymptoms, $fil )->applyFromArray($styleText);
						$colSymptoms ++;
					}

					$colSymptoms = $col + $totalConsultsSymptoms + 1;
					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['risks_category'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$risks = $this->consults_risks_model->get_by_id_consult( $consult['id'] );
					foreach( $risks as $risk ) {
						$sheet->setCellValueByColumnAndRow($colSymptoms, $fil, $risk['risk'] );
						$sheet->getStyleByColumnAndRow($colSymptoms, $fil )->applyFromArray($styleText);
						$colSymptoms ++;
					}


					$colSymptoms = $col + $totalConsultsSymptoms + 1 + $totalConsultsRisks + 1;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['diagnostic'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;
					*/

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

					if( $consultIndex > 0 ) {
						$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( $consultIndex > 0 ? $consults[$consultIndex - 1]['symptoms_severity'] - $consult['symptoms_severity'] : '' ) );
						$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					}
					else {
						$sheet->mergeCellsByColumnAndRow( $colSymptoms -1, $fil, $colSymptoms, $fil );
					}
					$colSymptoms ++;


					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['operation_reduction'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					if( $consultIndex > 0 ) {
						$sheet->setCellValueByColumnAndRow($colSymptoms, $fil, ($consultIndex > 0 ? $consults[$consultIndex - 1]['operation_reduction'] - $consult['operation_reduction'] : ''));
						$sheet->getStyleByColumnAndRow($colSymptoms, $fil)->applyFromArray($styleText);
					}
					else {
						$sheet->mergeCellsByColumnAndRow( $colSymptoms -1, $fil, $colSymptoms, $fil );
					}
					$colSymptoms++;

					/*
					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['referenced_to'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, str_replace( '0000-00-00', '', $consult['referenced_date'] ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( (int)$consult['psychotropics'] == 0 ? 'NO' : 'SÍ' ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, str_replace( '0000-00-00', '', $consult['psychotropics_date'] ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $consult['comments'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;
					*/


					$col += $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra;
				}

				if( $row['closed'] ) {
					$colSymptoms = $colTmp + ( $totalConsults * ( $totalConsultsSymptoms + $totalConsultsRisks + $totalCamposExtra ) );

					$closure = $this->consults_model->getLastClosedFor( $row['id'] );

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['creation'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['closure'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['closure_condition'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['consults_type'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['symptoms_severity'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( count($consults) > 0 ? $consults[0]['symptoms_severity'] - $closure['symptoms_severity'] : '' ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['operation_reduction'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, ( count($consults) > 0 ? $consults[0]['operation_reduction'] - $closure['operation_reduction'] : '' ) );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['total_sessions'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['duration'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$sheet->setCellValueByColumnAndRow( $colSymptoms, $fil, $closure['comments'] );
					$sheet->getStyleByColumnAndRow( $colSymptoms, $fil )->applyFromArray($styleText);
					$colSymptoms ++;

					$colMax = max($colMax, $colSymptoms);
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

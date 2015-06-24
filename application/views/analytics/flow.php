<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?= $this->load->view('common/head', array('title' => lang($class . '_page_name')) ) ?>
	<link rel="stylesheet" href="<?= base_url() ?>resources/css/vendor/jquery.gridster.css">
	<script src="<?= base_url() ?>resources/js/vendor/jquery.gridster.with-extras.min.js"></script>
	<script src="<?= base_url() ?>resources/js/vendor/angular.js"></script>
	<script src="<?= base_url() ?>resources/js/vendor/angular-animate.js"></script>
<?

	$jsFiles = array(
		'resources/js/services',
		'resources/js/controllers',
		'resources/js/directives'
	);

	foreach( $jsFiles as $jsDir ) {
		$dirFiles = array();
		if ($handle = opendir($jsDir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..")
					$dirFiles[] = $jsDir . '/' . $file;
			}
			closedir($handle);
		}
		sort($dirFiles);

		foreach( $dirFiles as $js ) {
?>
	<script src="<?= base_url() . $js ?>"></script>
<?
		}
	}


?>
	<script src="<?= base_url() ?>resources/js/flow.js"></script>
</head>
<body>
<?= $this->load->view('common/header', array('current' => 'manage_' . $class)) ?>
<div class="container content" ng-app="flow">
	<div class="sub-header"><i class="wizard icon"></i> <?= lang($class . "_page_name") ?></div>
	<div class="section">
<?
    echo $this->load->view( 'common/breadcrumb', array(
        'path' => array(
            lang( $class . '_page_name' ) => $scope . '/' . $class,
            lang( $class . "_page_name" ) => null
        ),
        'attach' => '' . '
			<div class="flow_options" ng-controller="FlowMenuController" ng-show="active">
				<a href="#"><i class="content small black icon"></i></a>
				<div class="ui popup">
					<div class="ui vertical text menu">
						<a class="item" ng-repeat="option in options" ng-click="option.action()"><i class="{{option.icon}} icon"></i> {{option.name}}</a>
					</div>
				</div>
			</div>
			<div ng-controller="FlowIndicatorController" class="ui active bottom attached indicating purple progress" data-percent="0" data-total="100">
				<div class="bar"></div>
			</div>
        '
    ));
?>
		<div class="flow" id="flowHolder" ng-controller="FlowController">
			<div id="firstTimeMsg" ng-hide="active">
				<div class="ui orange small message">
					<i class="close icon"></i>
					<div class="header">
						<i class="file outline icon"></i> <?= lang('flow_report_no_pages_title') ?>
					</div>
					<p><?= lang('flow_report_no_pages_content') ?></p>
				</div>
			</div>
			<div class="page" ng-repeat="page in pages" ng-controller="PageController" >
				<div class="holder">
					<input class="h1" ng-model="page.title" ng-change="setTitle( )" placeholder="Titúlo..."  />
					<div class="options" ng-controller="PageMenuController" ng-show="active">
						<a href="#" class="PageOps"><i class="content small grey icon"></i></a>
						<div class="ui popup">
							<div class="ui vertical text menu">
								<a class="item" ng-repeat="option in options" ng-click="option.action()"><i class="{{option.icon}} icon"></i> {{option.name}}</a>
							</div>
						</div>
						<a href="#" ng-show="remove" ng-click="removePage(page)"><i class="remove small icon"></i></a>
					</div>
					<div class="widgets">
						<div class="gridster"></div>
					</div>
				</div>
			</div>
<?
	if( $report['id'] == 1 ) {
?>
			<div class="page">
				<div class="holder">
					<h1>Facebook - Resultados del mes</h1>

					<div class="options">
						<a href="#"><i class="content small grey icon"></i></a><a href="#"><i class="remove small icon"></i></a>
					</div>
					<div class="widgets">
						<div class="gridster">
							<div class="widget blue" data-row="1" data-col="1" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									FANS TOTALES
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
							<div class="widget purple full" data-row="1" data-col="4" data-sizex="3" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget teal full" data-row="1" data-col="7" data-sizex="2" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget pink" data-row="2" data-col="4" data-sizex="5" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									COMPORTAMIENTO FANS
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget empty" data-row="3" data-col="1" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="remove inverted small icon"></i></a>
									</div>
								</div>
							</div>
							<div class="widget orange full" data-row="3" data-col="2" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget green full" data-row="3" data-col="3" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget purple full" data-row="4" data-col="1" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
							<div class="widget black full" data-row="4" data-col="4" data-sizex="2" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget white full" data-row="4" data-col="6" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ui horizontal disabled divider"><i class="ellipsis horizontal disabled icon"></i></div>
			<div class="page">
				<div class="holder">
					<h1>Otros - Resultados del mes</h1>

					<div class="options">
						<a href="#"><i class="content small grey icon"></i></a><a href="#"><i class="remove small icon"></i></a>
					</div>
					<div class="widgets">
						<div class="gridster">
							<div class="widget blue" data-row="1" data-col="1" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									FANS TOTALES
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
							<div class="widget purple full" data-row="1" data-col="4" data-sizex="3" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget teal full" data-row="1" data-col="7" data-sizex="2" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget pink" data-row="2" data-col="4" data-sizex="5" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									COMPORTAMIENTO FANS
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget empty" data-row="3" data-col="1" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="remove inverted small icon"></i></a>
									</div>
								</div>
							</div>
							<div class="widget orange full" data-row="3" data-col="2" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget green full" data-row="3" data-col="3" data-sizex="1" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ROI
								</div>
								<div class="body">
									...
								</div>
							</div>

							<div class="widget purple full" data-row="4" data-col="1" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
							<div class="widget black full" data-row="4" data-col="4" data-sizex="2" data-sizey="1">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
								</div>
							</div>
							<div class="widget white full" data-row="4" data-col="6" data-sizex="3" data-sizey="2">
								<div class="head">
									<div class="options">
										<a href="#"><i class="content small inverted icon"></i></a><a href="#"><i class="remove inverted small icon"></i></a>
									</div>
									ENGAGEMENT
								</div>
								<div class="body">
									...
									<div class="comments">
										<div class="title"><i class="comment small icon"></i>
											COMMENTS:
										</div>
										<div class="text">
											... cc ...
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?
	}
?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
	$(function(){ //DOM Ready

		// First Time Message
		$('#firstTimeMsg .message .close').on('click', function() {
			$('#firstTimeMsg').slideUp();
		});

		return;
		// x = 970 ==> ( 111 + 5 + 5 ) * 8 ==> ( width + margin-left + margin-right ) * columns = 968
		// y = 688 ==> ( 129 + 4 + 4 ) * 5 ==> ( height + margin-top + margin-bottom ) * rows = 685
		$(".flow .page .holder .widgets .gridster").gridster({
			widget_base_dimensions: [111, 129],
			widget_margins: [5, 4],
			widget_selector: 'div',
			max_cols: 8,
			min_rows: 5,
			max_rows: 5,
			autogrow_cols: false,
			resize: {
				enabled: true
			}
		});

		// Flow Menu
		$('.flow_options>a').click(function(event){
			event.preventDefault()
		}).popup({
			position: 'bottom right',
			inline: true,
			preserve: true,
			on    : 'click',
			delay: {
				show: 0,
				hide: 200
			},
			hoverable: true,
			closable: true,
			popup : $('.flow_options>.popup'),
			offset: 7,
			distanceAway: -7
		});

		$('#flowOptionsNewPage').click(function(event){
			event.preventDefault();
			createPage();
		});
	});
</script>
<script type="text/html" id="templatePage">
</script>
<?= $this->load->view('common/footer') ?>
</body>
</html>

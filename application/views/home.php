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
            <div class="flow">
                <div class="page">
                    <div class="holder" style="height: 704px !important;">
                        <div class="widgets">
                            <div class="gridster">
                                <div class="widget pink" data-row="1" data-col="1" data-sizex="4" data-sizey="3">
                                    <div class="head">
                                        EJEMPLO PA&Iacute;SES
                                    </div>
                                    <div class="body" id="upp"></div>
                                </div>
                                <div class="widget teal" data-row="1" data-col="5" data-sizex="4" data-sizey="3">
                                    <div class="head">
                                        EJEMPLO DE GRUPOS
                                    </div>
                                    <div class="body" id="upt"></div>
                                </div>
                                <div class="widget blue" data-row="5" data-col="1" data-sizex="4" data-sizey="3">
                                    <div class="head">
                                        EJEMPLO HOTEL
                                    </div>
                                    <div class="body" id="uph"></div>
                                </div>
                                <div class="widget orange" data-row="5" data-col="5" data-sizex="4" data-sizey="3">
                                    <div class="head">
                                        EJEMPLO <small>(TOP 5)</small>
                                    </div>
                                    <div class="body" id="vpv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <script type="text/javascript">
                    $(function(){ //DOM Ready
                        // x = 970 ==> ( 111 + 5 + 5 ) * 8 ==> ( width + margin-left + margin-right ) * columns = 968
                        // y = 653 ==> ( 100 + 4 + 4 ) * 6 ==> ( height + margin-top + margin-bottom ) * rows = 648
                        $(".flow .page .holder .widgets .gridster").gridster({
                            widget_base_dimensions: [111, 100],
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
                                    showInLegend: true				}
                            },
                            series: [{
                                type: 'pie',
                                name: ('Usuarios por Tipo'),
                                data: [
                                    [('Aerolínea'), 8 ],
                                    [('ATA'), 8 ],
                                    [('Conferencista'), 6 ],
                                    [('Operador'), 56 ],
                                    [('Representante'), 6 ],
                                ]
                            }]
                        });
                    });
                </script>
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
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: false
                                    },
                                    showInLegend: false				}
                            },
                            series: [{
                                type: 'pie',
                                name: ('Usuarios por Hotel'),
                                data: [
                                    [('Amsterdam Manor'), 0 ],
                                    [('Brickell Bay'), 4 ],
                                    [('Bucuti/Tara Beach Resorts'), 0 ],
                                    [('Divi Phoenix'), 0 ],
                                    [('Divi Village'), 2 ],
                                    [('Divi/Tamarijn All Inclusive'), 0 ],
                                    [('Gold Coast'), 0 ],
                                    [('Holiday Inn Aruba'), 7 ],
                                    [('Hyatt Aruba'), 0 ],
                                    [('Manchebo Beach Resort'), 0 ],
                                    [('Marriott Aruba'), 10 ],
                                    [('Occidental Aruba'), 16 ],
                                    [('Radisson Aruba'), 9 ],
                                    [('Renaissance Aruba'), 11 ],
                                    [('Ritz Carlton Aruba'), 5 ],
                                    [('Riu Antillas'), 3 ],
                                    [('Riu Aruba'), 0 ],
                                    [('Talk of the Town'), 3 ],
                                    [('The Mill Resort'), 4 ],
                                    [('Tierra del Sol'), 0 ],
                                    [('Tropicana Aruba'), 10 ],
                                ]
                            }]
                        });
                    });
                </script>
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
                                    showInLegend: false				}
                            },
                            series: [{
                                type: 'pie',
                                name: ('Usuarios por Pa�s'),
                                data: [
                                    [('1'), 1 ],
                                    [('Argentina'), 16 ],
                                    [('Aruba'), 1 ],
                                    [('Bolivia'), 1 ],
                                    [('Brasil'), 13 ],
                                    [('Brazil'), 2 ],
                                    [('Chile'), 6 ],
                                    [('Colombia'), 19 ],
                                    [('Costa Rica'), 2 ],
                                    [('Ecuador'), 1 ],
                                    [('Panama'), 3 ],
                                    [('Paraguay'), 1 ],
                                    [('Peru'), 5 ],
                                    [('Republica Dominicana'), 1 ],
                                    [('Usa'), 2 ],
                                    [('Venezuela'), 17 ],
                                ]
                            }]
                        });
                    });
                </script>
                <script type="text/javascript">
                    $(function () {
                        // Build chart 1
                        $('#vpv').highcharts({
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
                                    showInLegend: true				}
                            },
                            series: [{
                                type: 'pie',
                                name: ('Usuarios por Pa�s'),
                                data: [
                                    [('Holiday Inn Resort Aruba'), 30 ],
                                    [('Radisson Aruba Resort & Casino'), 30 ],
                                    [('Hyatt Regency Aruba Resort, Spa & Casino'), 30 ],
                                    [('De Palm Tours N.v.'), 30 ],
                                    [('Divi Aruba Phoenix Resort'), 30 ],
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

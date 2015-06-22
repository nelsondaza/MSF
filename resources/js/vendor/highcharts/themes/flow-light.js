/**
 * Grid-light theme for Highcharts JS
 * @author Torstein Honsi
 */

// Load the fonts

Highcharts.theme = {
	colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee","#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	chart: {
		backgroundColor: 'transparent',
		plotBackgroundColor: 'transparent',
		borderWidth: 0,
		plotBorderWidth: 0,
		style: {
			fontFamily: "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif",
			fontSize: '10px'
		},
		margin: [10,10,30,50]
	},
	credits: {
		enabled: false
	},
	legend: {
		align: 'right',
		verticalAlign: 'top',
		backgroundColor: 'rgba(255,255,255,0.8)',
		floating: true,
		itemStyle: {
			fontWeight: 'normal',
			fontSize: '8px'
		}
	},
	subtitle: {
		floating: true,
		align: 'center',
		style: {
			fontSize: '12px',
			fontWeight: 'normal',
			color: '#3F3F3F'
		}
	},
	title: {
		floating: true,
		align: 'center',
		style: {
			fontSize: '12px',
			fontWeight: 'bold',
			textTransform: 'uppercase',
			color: '#3F3F3F'
		},
		text: null
	},
	tooltip: {
		borderWidth: 0,
		backgroundColor: 'rgba(216,216,216,0.6)',
		shadow: false
	},
	xAxis: {
		gridLineWidth: 0,
		minorTickInterval: 0,
		title: {
			style: {
				textTransform: 'uppercase',
				fontSize: '10px',
				color: '#3F3F3F'
			},
			text: null
		},
		labels: {
			style: {
				fontSize: '10px',
				color: '#3F3F3F'
			}
		},
		tickColor: '#DEDCDD',
		tickLength: 10,
		tickWidth: 1,
		lineColor: '#DEDCDD',
		lineWidth: 1
	},
	yAxis: {
		gridLineWidth: 0,
		minorTickInterval: 0,
		title: {
			style: {
				textTransform: 'uppercase',
				fontSize: '10px',
				color: '#3F3F3F'
			},
			text: null
		},
		labels: {
			style: {
				fontSize: '10px',
				color: '#3F3F3F'
			}
		},
		tickColor: '#DEDCDD',
		tickLength: 10,
		tickWidth: 1,
		lineColor: '#DEDCDD',
		lineWidth: 1
	},
	plotOptions: {
		candlestick: {
			lineColor: '#404048'
		},
		area: {
			marker: {
				enabled: false,
				symbol: 'circle',
				radius: 2,
				states: {
					hover: {
						enabled: true
					}
				}
			}
		}
	},


	// General
	background2: 'transparent'

};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

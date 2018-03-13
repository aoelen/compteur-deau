<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" >
	<link rel="stylesheet" href="/css/app.css" >
	
	<title>Compteur d'eau dashboard</title>
</head>
<body>
	<div class="container">
		<div class="row title-bar">
			<div class="col">
				<div class="box-style clearfix">
					<h1 class="logo float-left">Compteur d'eau</h1>
					
					<div class="form-group float-right select-device">
						<select class="form-control">
							@foreach($devices as $device)
								<option>{{ $device->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
		
		<div id="live-view" class="row box-style">
			<!--<div class="live">Live data</div>-->
			<div class="live">Latest</div>
			
			<div class="col">
				<div class="number text-center">{{ $latestData[0]->ph }}</div> <!-- orange -->
				<div class="label text-center">pH</div>
			</div>
			<div class="col">
				<div class="number text-center">{{ $latestData[0]->turbidity }}</div>
				<div class="label text-center">Turbidity</div>
			</div>
			<div class="col">
				<div class="number text-center">{{ $latestData[0]->tds }}</div><!-- red -->
				<div class="label text-center">TDS</div>
			</div>
			<div class="col">
				<div class="number text-center">{{ $latestData[0]->temp }}</div>
				<div class="label text-center">Temperature</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="box-style">
					<div id="map"></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="box-style  settings-bar">
					<!--<div class="form-group select-location float-left">
						<select class="form-control">
							<option>All locations</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
						</select>
					</div>-->
					<div class="form-group">
						<div class="input-daterange input-group float-right">
							<input type="text" class="input-sm form-control" name="start" value="{{ $weekAgo }}" id="startDate"/>
							<!--<span class="input-group-addon">to</span>-->
							<div class="input-group-addon">
								<span class="input-group-text">to</span>
							</div>
							<input type="text" class="input-sm form-control" name="end" value="{{ $today }}" id="endDate" />
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="box-style data-box">
					<h2>pH</h2>
					<div id="chart-ph"></div>
				</div>
			</div>
			<div class="col">
				<div class="box-style data-box">
					<h2>Turbidity</h2>
					<div id="chart-turbidity"></div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col">
				<div class="box-style data-box">
					<h2>Total Dissolved Solids</h2>
					<div id="chart-tds"></div>
				</div>
			</div>
			<div class="col">
				<div class="box-style data-box">
					<h2>Temperature</h2>
					<div id="chart-temp"></div>
				</div>
			</div>
		</div>		
	</div>
	
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="{{ mix('js/app.js') }}"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js" ></script>
	<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script>
	//MAP
	var map, markers = [], markerCluster;
	function initMap() {
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 5,
			center: {lat: 1, lng: 1}
		});
		
	}
	//INIT DATEPICKER
	$(document).ready(function() {
		$('.input-daterange').datepicker({
			format: "dd-mm-yyyy",
			todayBtn: "linked",
			orientation: "auto",
			autoclose: true
		}).on('changeDate', function(e) {
			loadChartData();
		});
	});
	//LINE CHART
	google.charts.load('current', {packages: ['corechart', 'line']});
	google.charts.setOnLoadCallback(loadChartData);
	
	function loadChartData() {
		
		var types = ['ph', 'temp', 'tds', 'turbidity'];
		var start = $('#startDate').val(),
			end = $('#endDate').val();
		
		types.forEach(function(type, index) {
			$.ajax({
				url: "/data/" + type +"?start=" + start + "&end=" + end,
				success: function(loadedData) {
					var rows = [['Time', capitalizeFirstLetter(type) + ' value']];
					var dataLength = loadedData.length;
					
					if (dataLength == 0) {
						$('#chart-' + type).css('visibility', 'hidden');
						return;
					}
					
					loadedData.forEach(function(data) {
						rows.push([data.created_at, parseFloat(data[type])]);
					});
					
					$('#chart-' + type).css('visibility', 'visible');
					
					var data = google.visualization.arrayToDataTable(rows);
					console.log(rows);
					var options = {
						hAxis: {
							title: 'Time',
							showTextEvery: Math.floor(dataLength / 3),
							titleTextStyle: {
								italic: false  
							}
						},
						vAxis: {
							title: capitalizeFirstLetter(type),
							titleTextStyle: {
								italic: false  
							}
						},
						legend: {position: 'none'},
					};
					
					var chart = new google.visualization.LineChart(document.getElementById('chart-' + type));
					chart.draw(data, options);
				}
			});
		});
		
		$.ajax({
			url: "/data/gps?start=" + start + "&end=" + end,
			success: function(loadedData) {
				clearOverlays();
				
				if (loadedData.length == 0) {
					return;
				}
				
				loadedData.forEach(function(data) {
					var marker = new google.maps.Marker({
						position: {lat: parseFloat(data.x), lng: parseFloat(data.y)},
						map: map
					});
					markers.push(marker); //for clustering
				});
				map.setCenter({lat: loadedData[0].x, lng: loadedData[0].y});
				
				makeClusters();
			}
		});
	}
	function capitalizeFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
	}
	function clearOverlays() {
		for (var i = 0; i < markers.length; i++ ) {
			markers[i].setMap(null);
		}
		if (typeof markerCluster != 'undefined') {
			markerCluster.clearMarkers();
		}
		markers.length = 0;
	}
	function makeClusters() {
		markerCluster = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
	}
	</script>
	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_KEY') }}&callback=initMap">
	</script>
</body>
</html>
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" >
	<link rel="stylesheet" href="/css/app.css" >
	
	<title>Compteur d'eau {{ __('dashboard') }}</title>
</head>
<body>
	<div id="app">
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
						
						<div class="form-group float-right select-language">
							<a href="/en"><img src="/img/en.png" /></a>
							<a href="/fr"><img src="/img/fr.png" /></a>
							<a href="/nl"><img src="/img/nl.png" /></a>
						</div>
					</div>
				</div>
			</div>
			
			<div id="live-view" class="row box-style">
				<div class="live">{{ __('Latest') }}</div>
				
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->ph }}</div> <!-- orange -->
					<div class="label text-center">{{ __('pH') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->turbidity }}</div>
					<div class="label text-center">{{ __('Turbidity') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->tds }}</div><!-- red -->
					<div class="label text-center">{{ __('TDS') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->temp }}</div>
					<div class="label text-center">{{ __('Temperature') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->orp }}</div>
					<div class="label text-center">{{ __('ORP') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">{{ $latestData[0]->{'battery-level'} }}</div>
					<div class="label text-center">{{ __('Battery level') }}</div>
				</div>
				<div class="col">
					<div class="number text-center">
						@if ($latestData[0]->{'battery-charging'} == 1)
							{{ __('Yes')}}
						@else
							{{ __('No')}}
						@endif
					</div>
					<div class="label text-center">{{ __('Charging') }}</div>
				</div>
			</div>
			
			<div class="row">
				<compteur-map :start-date="startDate" :end-date="endDate"></compteur-map>
			</div>
			
			<div class="row">
				<div class="col">
					<div class="box-style  settings-bar">
						<div class="form-group">
							<jquery-datepicker @update-date="updateDates"></jquery-datepicker>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<compteur-chart title="{{ __('pH') }}" param="ph" :start-date="startDate" :end-date="endDate"></compteur-chart>
				<compteur-chart title="{{ __('Turbidity') }}" param="turbidity" :start-date="startDate" :end-date="endDate"></compteur-chart>
			</div>
			
			<div class="row">
				<compteur-chart title="{{ __('Total Dissolved Solids') }}" param="tds" :start-date="startDate" :end-date="endDate"></compteur-chart>
				<compteur-chart title="{{ __('Temperature') }}" param="temp" :start-date="startDate" :end-date="endDate"></compteur-chart>
			</div>		
			
			<div class="row">
				<compteur-chart title="{{ __('Oxidation Reduction Potential') }}" param="orp" :start-date="startDate" :end-date="endDate"></compteur-chart>
				<compteur-chart title="{{ __('Battery level') }}" param="battery-level" :start-date="startDate" :end-date="endDate"></compteur-chart>
			</div>
		</div>
	</div>
	
	<script src="{{ mix('js/app.js') }}"></script>
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
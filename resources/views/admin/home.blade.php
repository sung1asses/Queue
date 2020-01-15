@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Home</h1>
@endsection

@section('content')
<!-- <div class="section">
	<div class="row">
		<h2 class="col-12 text-center">Активные очереди</h2>
        @foreach($queue_list_active as $queue)
		<div class="col-md-6">
			<history-stat-chart-component queue="{{ $queue }}"></history-stat-chart-component>
		</div>
        @endforeach
	</div>
</div>
<div class="section">
	<div class="row">
		<h2 class="col-12 text-center">Оконченные очереди</h2>
        @foreach($queue_list_ended as $queue)
		<div class="col-md-6">
			<history-stat-chart-component queue="{{ $queue }}"></history-stat-chart-component>
		</div>
        @endforeach
	</div>
</div> -->
@endsection
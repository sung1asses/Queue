@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>{{ $operator->name }}</h1>
@endsection

@section('content')
<div class="row justify-content-center">
  
  <!-- <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Статистика</h3>
            </div>
            <div class="card-body">
              <history-stat-chart-component user_id="$id"></history-stat-chart-component>
            </div>
        </div>
    </div> -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Список активных очередей</h3>
            </div>
            <div class="card-body">
            @foreach($queue_list as $queue)
              <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.queue.show',['id'=> $queue->id]) }}">
                  {{ $queue->name }}
                </a>
              </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@stop
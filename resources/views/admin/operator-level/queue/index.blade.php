@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Работа с операторами</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Ваши очереди</h3>
            </div>
            <div class="card-body">
            @foreach($queue_list as $queue)
              <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('operator.queue.show',['id'=> $queue->id]) }}">
                  {{ $queue->name }}
                </a>
              </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Управление очередью</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
      <big-window-component first_queues="{{ $queue }}" id="{{ $id }}" queue_name_json="{{ $queue_name }}"></big-window-component>
    </div>
    <div class="col-md-3">
      <set-operator-component id="{{ $id }}" operators_json="{{ $operators }}" active_operators_json="{{ $active_operators }}">
    </div>
</div>
@stop
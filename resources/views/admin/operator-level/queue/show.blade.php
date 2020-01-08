@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Управление очередью</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-3">
     <admin-buttons-component id="{{ $id }}"></admin-buttons-component>
    </div>
    <div class="col-md-7">
      <big-window-component first_queues="{{ $queue }}" id="{{ $id }}" queue_name_json="{{ $queue_name }}"></big-window-component>
    </div>
</div>
@stop
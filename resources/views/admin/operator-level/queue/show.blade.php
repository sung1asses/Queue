@extends('adminlte::page')

@section('title', 'Eq aues')

@section('content_header')
    <h1>Управление очередью</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
     <admin-buttons-component id="{{ $id }}" operator_status="{{ $status }}"></admin-buttons-component>
    </div>
    <div class="col-md-8">
      <big-window-component queues_json="{{ $queue }}" operators_json="{{ $operators }}" id="{{ $id }}" queue_name_json="{{ $queue_name }}"></big-window-component>
    </div>
</div>
@stop
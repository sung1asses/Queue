@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col">
          <set-queue-component queues_json="{{ $queue }}" operators_json="{{ $operators }}" cookie_queue="{{ $cookie_queue }}" id="{{ $id }}"></set-queue-component>
        </div>
    </div>
</div>
@endsection
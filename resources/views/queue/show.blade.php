@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
          <set-queue-component first_queues="{{ $queue }}" cookie_queue="{{ $cookie_queue }}" id="{{ $id }}"></set-queue-component>
        </div>
    </div>
</div>
@endsection
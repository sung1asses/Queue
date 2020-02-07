@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Активные очереди:</div>

                <div class="card-body">
                  <ol>
                    @foreach($queue_list as $queue)
                      <li>
                        <a href="{{ route('queue.show',['id'=> $queue->id]) }}">
                          {{ $queue->name }}
                        </a>
                      </li>
                    @endforeach
                  </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

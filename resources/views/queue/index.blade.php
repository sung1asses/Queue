@extends('layouts.app')

@section('content')
<!--список-->
<ol type="1" class="queue">
    <h1>Онлайн очередь</h1>

    @foreach($queue_list as $queue)
      <li>
        <a href="{{ route('queue.show',['id'=> $queue->id]) }}">
          {{ $queue->name }} <button>Перейти</button>
        </a>
      </li>
    @endforeach
</ol>
@endsection

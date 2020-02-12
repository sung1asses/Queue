@extends('layouts.app')

@section('content')
<!--список-->
<div class="container">
	<div class="row">
		<div class="col queue">
		    <h1>Онлайн очередь</h1>
			@foreach($queue_list as $queue)
	        <a class="p-2 w-100 d-flex justify-content-between" style="border-bottom: 1px solid #ccc;" href="{{ route('queue.show',['id'=> $queue->id]) }}">
	          <span>{{ $queue->name }}</span> <button class="p-2">Перейти</button>
	        </a>
		    @endforeach
		</div>
	</div>
</div>

    
</ol>
@endsection

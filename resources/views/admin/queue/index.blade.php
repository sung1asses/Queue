@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <h1>Работа с очередями</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header with-border">
            <h3 class="card-title">Создание очереди</h3>
          </div>
          <form method="POST" enctype="multipart/form-data" action="{{ route('admin.queue.create') }}">
              <div class="card-body">
                {{ csrf_field() }}
                    <div class="form-group">
                       <label for="name" class="col-md-5 col-form-label text-md-right">{{ __('Название очереди') }}</label>
                       <input type="text" min="0" class="form-control" value="{{ old('name') }}" name="name" required="">
                    </div>
                    <div class="form-group">
                       <label for="fromDate" class="col-md-5 col-form-label text-md-right">{{ __('Дата открытия очереди') }}</label>
                       <input type="date" min="0" class="form-control" value="{{ old('fromDate') }}" name="fromDate" required="">
                    </div>
                    <div class="form-group">
                       <label for="toDate" class="col-md-5 col-form-label text-md-right">{{ __('Дата открытия очереди') }}</label>
                       <input type="date" min="0" class="form-control" value="{{ old('toDate') }}" name="toDate" required="">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Активные очереди</h3>
            </div>
            <div class="card-body">
            @foreach($queue_list_active as $queue)
              <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.queue.show',['id'=> $queue->id]) }}">
                  {{ $queue->name }}
                </a>
                <a href="{{ route('admin.queue.delete',['id'=> $queue->id]) }}">
                  {{ __('Удалить') }}
                </a>
              </div>
            @endforeach
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Не начатые очереди</h3>
            </div>
            <div class="card-body">
            @foreach($queue_list_potencially as $queue)
              <div class="d-flex justify-content-between align-items-center">
                <p>{{ $queue->name }}</p>
                <p>{{ $queue->fromDate }}</p>
                <a href="{{ route('admin.queue.delete',['id'=> $queue->id]) }}">
                  {{ __('Удалить') }}
                </a>
              </div>
            @endforeach
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header with-border">
              <h3 class="card-title">Закончившиеся очереди</h3>
            </div>
            <div class="card-body">
            @foreach($queue_list_ended as $queue)
              <div class="d-flex justify-content-between align-items-center">
                <p>{{ $queue->name }}</p>
                <p>{{ $queue->toDate }}</p>
                <a href="{{ route('admin.queue.delete',['id'=> $queue->id]) }}">
                  {{ __('Удалить') }}
                </a>
              </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@extends('adminlte::page')

@section('title', 'Eq aues')

@section('content_header')
    <h1>Работа с операторами</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header with-border">
            <h3 class="card-title">Создание оператора</h3>
          </div>
          <form method="POST" enctype="multipart/form-data" action="{{ route('admin.operator.create') }}">
              <div class="card-body">
                {{ csrf_field() }}
                    <div class="form-group">
                       <label for="sname" class="col-md-5 col-form-label text-md-right">{{ __('Фамилия оператора') }}</label>
                       <input id="sname" type="text" min="0" class="form-control" value="{{ old('sname') }}" name="sname" required="">
                    </div>
                    <div class="form-group">
                       <label for="fname" class="col-md-5 col-form-label text-md-right">{{ __('Имя оператора') }}</label>
                       <input id="fname" type="text" min="0" class="form-control" value="{{ old('fname') }}" name="fname" required="">
                    </div>
                    <div class="form-group">
                       <label for="email" class="col-md-5 col-form-label text-md-right">{{ __('Почта оператора') }}</label>
                       <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                       <label for="password" class="col-md-5 col-form-label text-md-right">{{ __('Введите пароль') }}</label>
                       <input id="password" type="password" min="8" class="form-control" value="{{ old('pass') }}" name="password" required="">
                    </div>
                    <div class="form-group">
                       <label for="password_confirmation" class="col-md-5 col-form-label text-md-right">{{ __('Повторите пароль') }}</label>
                       <input id="password_confirmation" type="password" min="8" class="form-control" value="{{ old('password_confirmation') }}" name="password_confirmation" required="">
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
              <h3 class="card-title">Список операторов</h3>
            </div>
            <div class="card-body">
            @foreach($operators as $operator)
              <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.operator.show',['id'=> $operator->id]) }}">
                  {{ $operator->name }}
                </a>
                <a href="{{ route('admin.operator.delete',['id'=> $operator->id]) }}">
                  {{ __('Удалить') }}
                </a>
              </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

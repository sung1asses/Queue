@component('mail::message')
# Вы - оператор!

Здравствуйте {{ ucfirst($data->name) }}, для вас создали аккаунт оператора. <br>

Данные для авторизации: <br>
E-mail:	{{ $data->email }}<br>
пароль: {{ $data->password }}<br>

Перейдите по ссылке, чтоб узнать больше: <a href="{{ route('admin.home') }}">eq.aues.kz/admin</a>

{{ config('app.name') }}
@endcomponent
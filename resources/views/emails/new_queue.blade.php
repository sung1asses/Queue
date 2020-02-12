@component('mail::message')
# Вы всталь в очередь: {{ $queue_list->name }}

Фамилия Имя, которое вы указали: {{ $request->name }} .<br>
<h3>Ваш номерок: <b>{{ $request->key }}</b></h3><br>
<h3><a href="{{ route('queue.resetCookie',['id'=> $queue_list->id, 'key' => $encrypted_key]) }}">Отследить очередь</a></h3>
<small>Это сообщение сгенерированно автоматически, на него не нужно отвечать</small><br>
{{ config('app.name') }}
@endcomponent
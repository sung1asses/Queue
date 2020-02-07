@component('mail::message')
# До вас осталось 3 человека. Очередь {{ $queue_name }}

Фамилия Имя, которое вы указали: {{ $request->secondName }} {{ $request->name }} .<br>
<h3>Ваш номерок: <b>{{ $request->key }}</b></h3><br>
<small>Это сообщение сгенерированно автоматически, на него не нужно отвечать</small><br>
{{ config('app.name') }}
@endcomponent
@component('mail::message')
# Вы пропустили свою очередь: {{ $queue_name }}

Если вы не согласны с администрированием, напишите свой отзыв к нам на почту. <br>
<small>Это сообщение сгенерированно автоматически, на него не нужно отвечать</small><br>
{{ config('app.name') }}
@endcomponent
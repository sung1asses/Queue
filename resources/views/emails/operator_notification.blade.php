@component('mail::message')
# Вас назначили оперировать очередью: {{ $queue_list->name }}

<h3>Очередь действует с {{ $queue_list->fromDate }} по {{ $queue_list->toDate }}.</h3><br>
<h4>Не забудьте про неё :)</h4><br><br><br>
{{ config('app.name') }}
@endcomponent
<template>
<div class="card my-4">
  <div class="card-header text-center">Очередь: {{ queue_name.name }}</div>
  <div class="card-body p-0">
    <div class="row">
      <div class="col">
        
        <table class="table table-striped mb-0">
          <thead>
            <tr>
              <th scope="col">Имя</th>
              <th scope="col">Фамилия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="queue in queues">
              <td>{{ queue.name }}</td>
              <td>{{ queue.secondName }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</template>

<script>
	export default {
        props: ['first_queues','id', 'queue_name_json'],
        data:function(){
            return{
                queues: JSON.parse(this.first_queues),
                queue_name: JSON.parse(this.queue_name_json),
            }
        },
        mounted() {
          this.listen();
        },
        methods: {
          listen() {
            Echo.channel('queue.'+this.id)
              .listen('QueueStatus', (response) => {
                console.log(response)
                this.queues = response.queues;
              })
          }
        }
    }
</script>
<template>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card my-4">
      <div class="card-header text-center">Очередь: {{ queue_name }}</div>
      <div class="card-body p-0">
        <div class="row">
          <div class="col">
            
            <table class="table table-striped mb-0">
              <thead>
                <tr class="text-center">
                  <th scope="col">Ключ</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="queue in queues">
                  <td>{{ queue.key }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card my-4">
      <div class="card-header text-center">Операторы</div>
      <div class="card-body p-0">
        <div class="row">
          <div class="col">
            
            <table class="table table-striped mb-0">
              <thead>
                <tr class="text-center">
                  <th scope="col">Оператор</th>
                  <th scope="col">Ключ</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="operator in operators">
                    <td v-if="operator.queue_id!=null">{{ operator.name }}</td>
                    <td v-else class="bg-success">{{ operator.name }}</td>
                    <td v-if="operator.queue_id!=null">{{ getNameFromId(operator.queue_id) }}</td>
                    <td v-else class="bg-success">{{ getNameFromId(operator.queue_id) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
	export default {
        props: ['queues_json','operators_json','id', 'queue_name'],
        data:function(){
            return{
                operator_queues: JSON.parse(this.queues_json).splice(0,JSON.parse(this.operators_json).length),
                queues: JSON.parse(this.queues_json).splice(JSON.parse(this.operators_json).length),
                operators: JSON.parse(this.operators_json),
            }
        },
        mounted() {
          this.listen();
        },
        methods: {
          getNameFromId(id){
            for(let i=0; i<this.operator_queues.length; i++){
              if(this.operator_queues[i].id==id){
                return this.operator_queues[i].key;
              }
            }
          },
          listen() {
            Echo.channel('queue.'+this.id)
              .listen('QueueStatus', (response) => {
                console.log(response)
                this.operator_queues = response.queues.splice(0,response.operators.length);//удаляем обслуживающиеся очереди с обьекта!! В response.queues их уже нет!
                this.queues = response.queues//По этому тут мы присваиваем оставшиеся
                this.operators = response.operators;//обновляем очередь
              })
          }
        }
    }
</script>
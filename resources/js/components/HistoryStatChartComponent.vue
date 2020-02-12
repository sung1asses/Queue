<template>
<div class="row">
    <div class="col-12">
        <h3>Статистика пользователя</h3><hr>   
        <h4 v-if="date == ''">Статистика за сегодня</h4>
        <h4 v-else>Статистика за {{ date }}</h4>
        <h4>Выберете дату: </h4>
        <input v-model="date" @change="getData" type="date">
        <div class="accordion mt-3" id="accordion">

          <div v-for="data in datacollection" class="card">
            <div class="card-header" id="headingOne">
              <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" :data-target="'#target_'+data.id" aria-expanded="true" :aria-controls="'target_'+data.id">
                  Очередь: {{ data.queue_name }}. Обслужено: {{ data.queues.length }}. <br>
                  {{ data.started_at }}  -  {{ data.ended_at }} 
                </button>
              </h2>
            </div>

            <div :id="'target_'+data.id" class="collapse" :aria-labelledby="'target_'+data.id" data-parent="#accordion">
              <div class="card-body p-0">
                <table class="table table-striped">
                  <thead>
                    <tr>
                        <th scope="col">Имя</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Почта</th>
                        <th scope="col">Ключ</th>
                        <th scope="col">Время</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="queue in data.queues">
                        <td>{{ queue.name }}</td>
                        <td>{{ queue.status }}</td>
                        <td>{{ queue.email }}</td>
                        <td>{{ queue.key }}</td>
                        <td>{{ queue.created_at }}</td>
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
    import LineChart from './LineChart.js'

    export default {
        props:['user_id'],
        // components: {
        //     LineChart
        // },
        data:function(){
            return{
                errors: [],
                datacollection: {},
                date: '',
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData: function() {
                axios.post('/admin/stat', {
                    date: this.date,
                    user_id: this.user_id,
                })
                .then((response) => {
                  this.datacollection= response.data;
                  console.log(this.datacollection)
                })
                .catch((error) => {
                  console.log(error);
                })
            },
        }
    }
</script>

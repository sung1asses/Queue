<template>
<div class="card my-4">
    <div class="card-header d-flex justify-content-between"><span>История</span><span v-if="current_key != null">Текущий ключ: <b>{{ current_key }}</b></span></div>
    <div class="card-body p-0">
      <div class="row">
        <div class="col">

          <table class="table table-striped mb-0">
            <thead>
              <tr>
                <th scope="col">Имя</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Статус</th>
                <th scope="col">Время</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="history in histories">
                <td>{{ history.name }}</td>
                <td>{{ history.secondName }}</td>
                <td class="text-danger" v-if="history.status == 'Пропустил'">{{ history.status }}</td>
                <td class="text-success" v-else-if="history.status == 'Посетил'">{{ history.status }}</td>
                <td v-else>{{ history.status }}</td>
                <td>{{ history.created_at }}</td>
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
        props: ['first_histories','id'],
        data:function(){
            return{
                histories: JSON.parse(this.first_histories),
                current_key: null,
            }
        },
        mounted() {
          this.getKey(this.histories);
          this.listen();
            },
        methods: {
          getKey(histories) {
            if(histories.length>0 && histories[0].status == 'Неопределен'){
              this.current_key = histories[0].key;
            }else{
              this.current_key = null;
            }
          },
          listen() {
            Echo.channel('queue.'+this.id)
              .listen('HistoryStatus', (response) => {
                console.log(response)
                this.histories = response.histories;
                this.getKey(response.histories);
              })
          }
        }
    }
</script>
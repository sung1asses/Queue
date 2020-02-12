<template>

<div class="container">
  <div class="inline row">
    <div v-if="cookie==0" class="col-md-4">
      <button type="button" class="btn butt btn-primary w-100 py-4" data-toggle="modal" data-target="#modalCallForm">Встать в очередь</button>
    </div>
    <div v-else class="col-md-4">
      <h3 v-if="!is_with_operator" class="text-dark">Вы <b>{{ getIndexFromId }}</b> в списке</h3>
      <h3 v-else class="text-dark">Наступила ваша очередь</h3>
      <h3 class="text-dark">Ваш ключ: <b>{{ cookie.key }}</b></h3>
      <button @click="deleteQueue" type="button" class="btn butt btn-primary w-100 py-4">Выйти с очереди</button>
    </div>
  
    <div class="col-md-4">
      <table class="kezek">
          <tr>
              <th colspan="3" class="kezek_btn">Список очередей</th>
          </tr>
          <tr>
              <th>Очередь</th>
              <th><i class="fas fa-angle-right"></i></th>
              <th>Талон</th>
          </tr>
          <tr v-for="(queue, key) in queues">
            <td v-if="queue.id==cookie.id" class="bg-warning">{{ key+1 }}</td>
            <td v-else>{{ key+1 }}</td>
                <td v-if="queue.id==cookie.id" class="bg-warning"><i class="fas fa-angle-right text-dark"></i></td>
                <td v-else><i class="fas fa-angle-right"></i></td>
            <td v-if="queue.id==cookie.id" class="bg-warning">{{ queue.key }}</td>
            <td v-else>{{ queue.key }}</td>
          </tr>
        
      </table>
    </div>
    <div class="col-md-4">
      <table class="kezek">
          <tr>
              <th colspan="3" class="kezek_btn">Список опеаторов</th>
          </tr>
          <tr>
              <th>Операторы</th>
              <th><i class="fas fa-angle-right"></i></th>
              <th>Талон</th>
          </tr>
          <tr v-for="operator in operators">
              <td v-if="operator.queue_id==null" class="bg-success text-white">{{ operator.name }}</td>
              <td v-else-if="operator.queue_id==cookie.id" class="bg-warning">{{ operator.name }}
              <td v-else>{{ operator.name }}</td></td>
      
                <td v-if="operator.queue_id==null" class="bg-success"><i class="fas fa-angle-right text-white"></i></td>
                <td v-else-if="operator.queue_id==cookie.id" class="bg-warning"><i class="fas fa-angle-right text-dark"></i>
                <td v-else><i class="fas fa-angle-right"></i></td></td>
      
              <td v-if="operator.queue_id==null" class="bg-success text-white">Свободен</td>
              <td v-else-if="operator.queue_id==cookie.id" class="bg-warning">{{ getNameFromId(operator.queue_id) }}
              <td v-else>{{ getNameFromId(operator.queue_id) }}</td></td>
          </tr>
      </table>
    </div>
        <!-- Modal -->
  </div>
  <div class="modal fade" id="modalCallForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Встать в очередь</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="form">
                      <div class="modal_input">
                          <label for="Mname">Фамилия Имя: </label><input id="Mname" type="text" placeholder="Введите Имя" v-model="name" name="name" required="">
                      </div>
                      <div class="modal_input">
                          <label for="Memail">E-MAIL: </label><input id="Memail" type="text" placeholder="Введите E-mail" v-model="email" name="email" required="">
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <div class="spinner-grow text-danger" v-if="is_loader" role="status">
                  <span class="sr-only">Загрузка...</span>
                </div>
                <button type="button" class="btn butt" data-dismiss="modal" style="width:30%">Отмена</button>
                <button  :disabled="is_loader" @click="modalCallForm" type="button" class="btn butt" style="width:30%">В очередь</button>
              </div>
              <div>
                <h5 v-if="is_confirm" class="text-center border-bottom border-success m-0 p-0">Вы встали в очередь</h5>
                <div v-if="is_error" v-for="err in is_error_arr">
                  <h5 class="text-center border-bottom border-danger m-1 p-1">{{ err[0] }}</h5>
                </div>
              </div>
          </div>
      </div>
  </div>

  <div id="alert" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header text-center">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <h4>Наступила <strong>Ваша</strong> очередь.</h4>
        </div>
      </div>
    </div>  
  </div>
</div>

</template>

<script>
    export default {
        props:['queues_json','operators_json','cookie_queue','id'],
        data:function(){
            return{
                name: '',
                email: '',

                is_loader: false,
                is_confirm: false,
                is_error: false,
                is_with_operator: false,

                is_error_arr: [],

                cookie: JSON.parse(this.cookie_queue),
                cookie_flag: false,

                operator_queues: JSON.parse(this.queues_json).splice(0,JSON.parse(this.operators_json).length),
                queues: JSON.parse(this.queues_json).splice(JSON.parse(this.operators_json).length),
                operators: JSON.parse(this.operators_json),
            }
        },
        mounted() {
          this.listen();
        },
        computed: {
          getIndexFromId(){
            if(this.cookie){
              for(let i=0; i<this.queues.length; i++){
                if(this.queues[i].id==this.cookie.id){
                  return ++i;
                }
              }
              for(let i=0; i<this.operator_queues.length; i++){
                if(this.operator_queues[i].id==this.cookie.id){
                  this.is_with_operator = true;
                  return false;
                }
              }
              return "30+"
            }
          }
        },
        methods: {
            getNameFromId(id){
              for(let i=0; i<this.operator_queues.length; i++){
                if(this.operator_queues[i].id==id){
                  return this.operator_queues[i].key;
                }
              }
            },
            deleteQueue() {
              axios.delete('/'+this.id+'/queue', {
              })
              .then((response) => {
                console.log(response);
                this.cookie = 0;//удаляем куки
              })
              .catch((error) => {
                console.log(error);
              })
            },
            deleteCookie(){
              axios.delete('/'+this.id+'/cookie', {
              })
              .then((response) => {
                console.log(response.data);
                this.cookie = 0;//удаляем куки
              })
              .catch((error) => {
                console.log(error);
              })
            },
            modalCallForm() {
                this.is_loader = true;//показываем прелоадер

                axios.post('/'+this.id, {
                  name: this.name,
                  email: this.email,
                })
                .then((response) => {
                  console.log(response.data.validation);
                  if(!response.data.validation){
                    this.cookie = response.data;//назначаем куки
                    this.is_loader=false;//убираем прелоадер
                    this.is_confirm=true;//показать сообщние об успехе
                    setTimeout(() => this.is_confirm = false, 2000);//скрыть сообщние об успехе
                    setTimeout(() => $('#modalCallForm').modal('hide'), 2000);//скрыть модальное окно
                  }
                  else{
                    this.is_loader=false;//показать лоадер
                    this.is_error=true;//показать сообщние об провале
                    this.is_error_arr=response.data.validation;
                    setTimeout(() => this.is_error = false, 3000);//скрыть сообщние об провале
                  }
                })
                .catch((error) => {
                  console.log(error);
                })
            },
            listen() {
              Echo.channel('queue.'+this.id)
                .listen('QueueStatus', (response) => {
                  console.log(response)
                  if(this.cookie != 0){
                    let flag = 0;
                    for(let i=0; i<response.queues.length; i++){
                      if(response.queues[i].id==this.cookie.id){
                        flag = 1;
                      }
                    }
                    if(flag == 0){
                        this.cookie_flag = false;
                        this.is_with_operator = false;
                        this.deleteCookie();
                    }
                  }

                  this.operator_queues = response.queues.splice(0,response.operators.length);//удаляем обслуживающиеся очереди с обьекта!! В response.queues их уже нет!
                  this.queues = response.queues//По этому тут мы присваиваем оставшиеся
                  this.operators = response.operators;//обновляем очередь

                  if(this.cookie_flag == false){
                    for(let i=0; i<this.operator_queues.length; i++){
                      if(this.cookie != 0 && this.operator_queues[i].id == this.cookie.id){//Если обслуживаемые очереди совпадает с куки
                        $('#alert').modal("show");//показываем уведомление о наступлении очереди
                        this.cookie_flag = true;
                      }
                    }
                  }
                  else{
                    let flag = 0
                    for(let i=0; i<this.operator_queues.length; i++){
                      if(this.cookie != 0 && this.operator_queues[i].id == this.cookie.id){//Если обслуживаемые очереди совпадает с куки
                        flag = 1;
                      }
                    }
                    if(flag == 0){
                      this.cookie_flag = false;
                    }
                  }
                })
            }
        }
    }
</script>

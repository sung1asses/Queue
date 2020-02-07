<template>
<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="card my-4">
        <div class="card-header">Управление</div>
        <div class="card-body">
          <div>

            <button v-if="cookie==0" type="button" class="btn btn-primary w-100 p-3" data-toggle="modal" data-target="#modalCallForm">
              Встать в очередь
            </button>
            
            <div v-else>
              <h3>Ваш ключ: <b>{{ cookie.key }}</b></h3>
              <h3><button @click="deleteQueue" type="button" class="btn btn-danger w-100 p-3">Выйти с очереди</button></h3>
            </div>

          </div>
        </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card my-4">
      <div class="card-header text-center">Очередь</div>
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
                  <td v-if="queue.id==cookie.id" class="bg-warning">{{ queue.key }}</td>
                  <td v-else>{{ queue.key }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
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
                    <td v-else class="text-white" style="background-color: #28a745">{{ operator.name }}</td>
                    <td v-if="operator.queue_id!=null">{{ getNameFromId(operator.queue_id) }}</td>
                    <td v-else class="text-white" style="background-color: #28a745">{{ getNameFromId(operator.queue_id) }}</td>
                </tr>
              </tbody>
            </table>
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

  <div class="modal fade p-relative" id="modalCallForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
              aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" id="needs-validation1">
        <div class="modal-header text-center">
          <h4 class="modal-title w-100 font-weight-bold">Встать в очередь</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body mx-3">
            <div class="form">
              <div class="form-group row">
                 <label for="name" class="col-md-5 col-form-label text-md-right">Ваше имя</label>
                                     
                 <div class="col-md-7">
                     <input type="text" min="0" class="form-control" placeholder="Ivan" v-model="name" name="name" required="">
                 </div>
              </div>
              <div class="form-group row">
                 <label for="secondName" class="col-md-5 col-form-label text-md-right">Ваша фамилия</label>
                                     
                 <div class="col-md-7">
                     <input type="text" min="0" class="form-control" v-model="secondName" placeholder="Ivanov" name="secondName" required="">
                 </div>
              </div>
              <div class="form-group row">
                 <label for="email" class="col-md-5 col-form-label text-md-right">Ваша почта</label>
                                     
                 <div class="col-md-7">
                     <input type="email" min="0" class="form-control" v-model="email" placeholder="Ivan777@gmail.ru" name="email" required="">
                 </div>
              </div>
            </div>
            
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <div class="spinner-grow text-primary" v-if="is_loader" role="status">
            <span class="sr-only">Загрузка...</span>
          </div>
          <h5 v-if="is_confirm" class="text-center border-bottom border-success m-0 p-0">Вы встали в очередь</h5>
          <h5 v-if="is_error" class="text-center border-bottom border-danger m-0 p-0">Вы уже зарегестрированны в очереди</h5>
          <button :disabled="is_loader" @click="modalCallForm" class="btn btn-primary ml-auto">Отправить</button>
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
                secondName: '',
                email: '',

                is_loader: false,
                is_confirm: false,
                is_error: false,
                
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
                  secondName: this.secondName,
                  email: this.email,
                })
                .then((response) => {
                  console.log(response.data);
                  this.cookie = response.data;//назначаем куки
                  this.is_loader=false;//убираем прелоадер
                  this.is_confirm=true;//показать сообщние об успехе
                  setTimeout(() => this.is_confirm = false, 2000);//скрыть сообщние об успехе
                  setTimeout(() => $('#modalCallForm').modal('hide'), 2000);//скрыть модальное окно
                })
                .catch((error) => {
                  console.log(error);
                  this.is_loader=false;//показать лоадер
                  this.is_error=true;//показать сообщние об провале
                  setTimeout(() => this.is_error = false, 3000);//скрыть сообщние об провале
                  setTimeout(() => $('#modalCallForm').modal('hide'), 3000);//скрыть модальное окно
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

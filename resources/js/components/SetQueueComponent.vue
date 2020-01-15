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
                    <td v-if="cookie && cookie.id==queue.id" class="bg-warning bold">{{ queue.key }}</td>
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
                    <td v-if="cookie && cookie.id==operator.queue_id" class="bg-warning bold">{{ operator.name }}</td>
                    <td v-else>{{ operator.name }}</td>
                    <td v-if="cookie && cookie.id==operator.queue_id" class="bg-warning bold">{{ operator.queue_id }}</td>
                    <td v-else>{{ operator.queue_id }}</td>
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
                     <input type="text" min="0" class="form-control" v-model="name" name="name" required="">
                 </div>
              </div>
              <div class="form-group row">
                 <label for="secondName" class="col-md-5 col-form-label text-md-right">Ваша фамилия</label>
                                     
                 <div class="col-md-7">
                     <input type="text" min="0" class="form-control" v-model="secondName" name="secondName" required="">
                 </div>
              </div>
              <div class="form-group row">
                 <label for="email" class="col-md-5 col-form-label text-md-right">Ваша почта</label>
                                     
                 <div class="col-md-7">
                     <input type="email" min="0" class="form-control" v-model="email" name="email" required="">
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
          <button @click="modalCallForm" class="btn btn-primary ml-auto">Отправить</button>
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
                name: 'Roman',
                secondName: 'Suvorov',
                email: 'suvorov_roman_01@mail.ru',

                is_loader: false,
                is_confirm: false,
                is_error: false,
                

                cookie: JSON.parse(this.cookie_queue),

                queues: JSON.parse(this.queues_json).splice(JSON.parse(this.operators_json).length),
                operators: JSON.parse(this.operators_json),
            }
        },
        mounted() {
          this.listen();
        },
        methods: {
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
                  if(this.queues.length>response.queues.length)
                  {
                    if(response.queues.length!=0 && response.queues[0].id == this.cookie.id){//если id обновленной очереди и куки совпадают
                      $('#alert').modal("show");//показываем уведомление о наступлении очереди
                    }
                    if(response.queues.length==0 || this.queues[0].id==this.cookie.id){//если id старой очереди и куки совпадают
                      this.deleteCookie();
                    }
                  }
                  this.queues = response.queues.splice(response.operators.length);//обновляем очередь
                  this.operators = response.operators;//обновляем очередь
                })
            }
        }
    }
</script>

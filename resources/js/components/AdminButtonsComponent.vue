<template>

<div class="card my-4">
    <div class="card-header">Управление</div>
    <div v-if="status==1" class="card-body">
        <div class="mb-3">
          <button :disabled="nextQueueButton == 0" @click="nextQueue('succ')" type="button" class="btn btn-primary w-100 p-3">
            Следующий
          </button>
        </div>

        <div class="mb-3">
          <button :disabled="nextQueueButton == 0" @click="nextQueue('err')" type="button" class="btn btn-danger w-100 p-3">
            Пропустить
          </button>
        </div>

        <div>
          <button :disabled="nextQueueButton == 0" @click="operatorStatus(0)" type="button" class="btn btn-warning w-100 p-3">
            Завершить работу
          </button>
        </div>
    </div>
    <div v-else class="card-body">
        <div class="mb-3">
          <button :disabled="nextQueueButton == 0" @click="operatorStatus(1)" type="button" class="btn btn-success w-100 p-3">
            Начать работу
          </button>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props:['id','operator_status'],
        data:function(){
            return{
                status: JSON.parse(this.operator_status),
                nextQueueButton: 1,
            }
        },
        mounted() {
        },
        methods: {
            nextQueue: function(stat) {
                this.nextQueueButton = 0;
                axios.post('/admin/operator-level/queue/'+this.id, {
                    status: stat,
                })
                .then((response) => {
                  console.log(response);
                  this.nextQueueButton = 1;
                })
                .catch((error) => {
                  console.log(error);
                  this.nextQueueButton = 1;
                })
            },
            operatorStatus: function(stat) {
                this.nextQueueButton = 0;
                axios.post('/admin/operator-level/queue/'+this.id+'/operatorStatus', {
                    status: stat,
                })
                .then((response) => {
                  console.log(response);
                  this.nextQueueButton = 1;
                  if(stat==1){
                    this.status = 1;
                  }
                  else{
                    this.status = 0; 
                  }
                  
                })
                .catch((error) => {
                  console.log(error);
                  this.nextQueueButton = 1;
                })
            }
        }
    }
</script>

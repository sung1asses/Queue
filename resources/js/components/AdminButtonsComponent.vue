<template>

<div class="card my-4">
    <div class="card-header">Управление</div>
    <div class="card-body">
        <div class="mb-3">
          <button @click="nextQueueSucc" type="button" class="btn btn-primary w-100 p-3">
            Следующий
          </button>
        </div>

        <div>
          <button @click="nextQueueErr" type="button" class="btn btn-danger w-100 p-3">
            Пропустить
          </button>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props:['id'],
        data:function(){
            return{
            }
        },
        mounted() {
        },
        methods: {
            nextQueueSucc: function() {
                axios.post('/admin/operator-level/queue/'+this.id, {
                    status: 'succ',
                })
                .then((response) => {
                  console.log(response);
                })
                .catch((error) => {
                  console.log(error);
                })
            },
            nextQueueErr: function() {
                axios.post('/admin/operator-level/queue/'+this.id, {
                    status: 'err',
                })
                .then((response) => {
                  console.log(response);
                })
                .catch((error) => {
                  console.log(error);
                })
            }
        }
    }
</script>

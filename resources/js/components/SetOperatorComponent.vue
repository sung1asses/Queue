<template>
<div class="card my-4">
    <div class="card-header">Управление</div>
    <div class="card-body">
      <div class="form">
        <label>Операторы</label>
          <div v-for="operator in operators" class="form-check">
            <input type="checkbox" v-bind:id="operator.id" class="form-check-input" v-on:change="setOperator(findId(operator.id), operator.id)" :checked="findId(operator.id)">
            <label class="form-check-label" v-bind:for="operator.id">{{ operator.name }}</label>
          </div>
      </div>
    </div>
</div>
</template>

<script>
    export default {
        props:['id', 'operators_json', 'active_operators_json'],
        data:function(){
            return{
                operators: JSON.parse(this.operators_json),
                active_operators: JSON.parse(this.active_operators_json),
            }
        },
        mounted() {

            console.log(this.active_operators);
        },
        methods: {
            findId: function(id){
                for(let i=0; i < this.active_operators.length; i++){
                  if(this.active_operators[i].id==id){
                    return true;
                  }
                }
                return false;
            },
            setOperator: function(checked, operator_id) {
                axios.put('/admin/queue/'+this.id, {
                  checked: checked,
                  operator_id: operator_id,
                })
                .then((response) => {
                  this.active_operators = response.data;
                  console.log(response.data);
                })
                .catch((error) => {
                  console.log(error);
                })
            }
        }
    }
</script>
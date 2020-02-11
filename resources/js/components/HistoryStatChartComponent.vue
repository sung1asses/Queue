<template>
<div class="row">
    <div class="col-12">
        <h3>Статистика пользователя</h3>
        <h4>Выберете дату: </h4>
        <input v-model="date" @change="getData" type="date">
        <!-- <line-chart :chart-data="datacollection" :height="200" :options="{responsive:true,maintainAspectRation:true}"></line-chart> -->
    </div>
</div>
</template>

<script>
    import LineChart from './LineChart.js'

    export default {
        props:['user_id'],
        components: {
            LineChart
        },
        data:function(){
            return{
                datacollection: {},
                date: null,
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            getData: function() {
                axios.post('/admin/stat', {
                    date: this.date,
                })
                .then((response) => {
                  console.log(response)
                  this.datacollection= response.data;
                })
                .catch((error) => {
                  console.log(error);
                })
            },
        }
    }
</script>

<template>
<div class="row">
    <div class="col-12">
        <h3>Очередь: {{ queue_parsed.name }}</h3>
        <h4>Выберете дату: <span>{{ dateString }}</span></h4>
        <input v-model="date" @change="getData" type="date">
        <line-chart :chart-data="datacollection" :height="200" :options="{responsive:true,maintainAspectRation:true}"></line-chart>
    </div>
</div>
</template>

<script>
    import LineChart from './LineChart.js'

    export default {
        components: {
            LineChart
        },
        props:['queue'],
        data:function(){
            return{
                datacollection: {},
                date: null,
                dateString: 'Сегодня',
                queue_parsed: JSON.parse(this.queue),
            }
        },
        mounted() {
            console.log(this.queue_parsed)
            this.getData();
        },
        methods: {
            getData: function() {
                if(this.date){
                  this.dateString = this.date;
                }
                axios.post('/admin/GetHistoryStat', {
                    queue_id: this.queue_parsed.id,
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

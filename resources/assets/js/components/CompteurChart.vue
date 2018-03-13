<template>
    <div class="col">
        <div class="box-style data-box">
            <h2>{{ title }}</h2>
            <vue-chart
                :chart-type="chartType"
                :columns="columns"
                :rows="rows"
                :options="options"
            ></vue-chart>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['title', 'param', 'startDate', 'endDate'],
        created() {
            this.loadData();
        },
        data() {
            return {
                chartType: 'LineChart',
                columns: [],
                rows: [],
                options: {
                    hAxis: {
                        title: 'Time',
                        showTextEvery: null,
                        titleTextStyle: {
                            italic: false  
                        }
                    },
                    vAxis: {
                        title: null,
                        titleTextStyle: {
                            italic: false  
                        }
                    },
                    legend: {position: 'none'},
                }
            }
        },
        watch: {
            startDate() {
                this.loadData();
            },
            endDate() {
                this.loadData();
            }
        },
        methods: {
            loadData() {
                var type = this.param,
                    typeCapital = this.capitalizeFirstLetter(type);
                    
                axios.get('/data/' + type + '?start=' + this.startDate + '&end=' + this.endDate)
                .then(response => {
                    var rows = [],
                        dataLength = response.data.length;
                    
                    response.data.forEach(function(data) {
                        rows.push([data.created_at, parseFloat(data[type])]);
                    });
                    this.rows = rows;
                    
                    this.columns = [{
                        'type': 'string',
                        'label': 'Time'
                    }, {
                        'type': 'number',
                        'label': typeCapital + ' value'
                    }];
                    this.options.hAxis.showTextEvery = Math.floor(dataLength / 3);
                    this.options.vAxis.title = typeCapital;
                })
                .catch(e => {
                    console.log(e);
                });
            },
            capitalizeFirstLetter(string) {
        	    return string.charAt(0).toUpperCase() + string.slice(1);
        	}
        }
    }
</script>

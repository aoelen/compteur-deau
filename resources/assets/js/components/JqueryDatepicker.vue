<template>
    <div class="input-daterange input-group float-right">
        <input type="text" class="input-sm form-control" name="start" v-model="startDate"/>
        
        <div class="input-group-addon">
            <span class="input-group-text">to</span>
        </div>
        
        <input type="text" class="input-sm form-control" name="end" v-model="endDate" />
    </div>
</template>

<script>
    require('./bootstrap-datepicker.min.js');
    export default {
        mounted() {
            var self = this;
            $('.input-daterange').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                orientation: "auto",
                autoclose: true
            }).on('changeDate', function(e, e2) {
                var start = $(this).find('[name="start"]').val();
                var end = $(this).find('[name="end"]').val();
                self.$emit('update-date', start, end);
            });
        },
        data() {
            return {
                startDate: '12-02-2018',
                endDate: '12-03-2018'
            }
        }
    }
</script>

<template>
    <div class="col">
        <div class="box-style">
            <gmap-map
            :center="center"
            :zoom="4"
            map-type-id="terrain"
            class="map">
                <gmap-cluster>
                    <gmap-marker
                        :key="index"
                        v-for="(m, index) in markers"
                        :position="m.position"
                        :clickable="true"
                        :draggable="false"
                        @click="center=m.position"
                    ></gmap-marker>
                </gmap-cluster>
            </gmap-map>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['startDate', 'endDate'],
        created() {
            this.loadData();
        },
        watch: {
            startDate() {
                this.loadData();
            },
            endDate() {
                this.loadData();
            }
        },
        data() {
            return {
                center: {lat: 10.0, lng: 10.0},
                markers: []
            }
        },
        methods: {
            loadData() {
                axios.get('/data/gps?start=' + this.startDate + '&end=' + this.endDate)
                .then(response => {
                    this.markers = [];
                    
                    if (response.data.length == 0) {
                        return;
                    }
                    
                    response.data.forEach(data => {
    					var marker = {position: {lat: parseFloat(data.x), lng: parseFloat(data.y) }};
                        
    					this.markers.push(marker); 
    				});

                    this.center = { lat: this.markers[0].position.lat, lng: this.markers[0].position.lng };
                })
                .catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>

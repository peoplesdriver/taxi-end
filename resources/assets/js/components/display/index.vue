<template>
  <div class="row no-gutters">
    <DriverModal
      v-bind:id="{[driverId]: true}"
    ></DriverModal>
    <Taxi
      v-for="taxi in taxis"
      v-bind:key="taxi.id"
      v-bind:taxi="taxi"
      v-b-modal.modal1
      v-on:click="updateDriverId(taxi.id)"
    ></Taxi>
  </div>
</template>

<script>
  import Taxi from './taxi.vue';
  import DriverModal from './DriverModal.vue';
  const axios = require('axios');

  export default {
    data() {
      return {
        taxis: [],
        interval: null,
        centerName: '',
        driver: '',
        driverId: '',
      }
    },
    
    components: {
      Taxi,
      DriverModal
    },

    mounted() {
      this.getCenterName();
      this.loadData();

      // change the interval from 2 hours to 10 seconds in production
      this.interval = setInterval(function () {
        this.loadData();
      }.bind(this), 120000);      
    },

    methods: {
      getCenterName: function () {
        var pathArray = window.location.pathname.split( '/' );
        console.log(pathArray[2]);
        this.centerName = pathArray[2];
      },

      loadData: function () {
        axios.get('/api/v2/display/taxis/' + this.centerName)
        .then((response) => {      
          this.taxis = response.data;
          // console.log(response);
        });
      },

      updateDriverId: function(id) {
        this.driverId = id;
      },
    },

    beforeDestroy: function(){
      clearInterval(this.interval);
    },
  }
</script>
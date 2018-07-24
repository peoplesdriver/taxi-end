<template>
  <b-modal id="modal1" title="Driver Info" style="color: black">
    <div>
      <div class="row">
          <div class="col-md-3">
              <img id="driverPhoto" class="img-fluid img-thumbnail" src="https://profile.actionsprout.com/default.jpeg">
          </div>
          <div class="col">
              <h4>Name: {{ driver.driverName }}</h4>
              <h5>Driver Temp. Address: </h5>
              <h5>Driver Perm. Address: </h5>
              <h5>Id card: </h5>
              <h5>Driver License No. </h5>
              <h5>Driver Phone: </h5>
              <h5>Driver Email: </h5>
          </div>
      </div>
      <hr>
      <div class="row">
          <div class="col-md-5">
              <h3>BIA </h3>
          </div>
          <div class="col-md-7">
              <h5 id="paymentStatus"></h5>
              <h5>Anual Fee expiry: </h5>
              <h5>Road Worthiness expiry: </h5>
              <h5>Insurance expiry: </h5>
              <h5>Driver License expiry: </h5>
              <h5>Driver Permit expiry: </h5>
          </div>
      </div>
      <hr>
      <div class="row">
          <div class="col-md-6">
              <h5>License Card</h5>
              <img id="licenceFront" class="img-fluid img-thumbnail" src="http://graphics8.nytimes.com/packages/images/multimedia/bundles/projects/2013/Licenses/2008back.jpg" alt="">
          </div>
          <div class="col-md-6">
              <h5>Taxi Permit</h5>
              <img id="licenceBack" class="img-fluid img-thumbnail" src="http://graphics8.nytimes.com/packages/images/multimedia/bundles/projects/2013/Licenses/2008back.jpg" alt="">
          </div>
      </div>
      <hr>
      <div class="row">
          <div class="col-md-6">
              <h5>Taxi Front</h5>
              <img id="taxiFront" class="img-fluid img-thumbnail" src="http://graphics8.nytimes.com/packages/images/multimedia/bundles/projects/2013/Licenses/2008back.jpg" alt="">
          </div>
          <div class="col-md-6">
              <h5>Taxi Back</h5>
              <img id="taxiBack" class="img-fluid img-thumbnail" src="http://graphics8.nytimes.com/packages/images/multimedia/bundles/projects/2013/Licenses/2008back.jpg" alt="">
          </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
  const axios = require('axios');
  import BootstrapVue from 'bootstrap-vue';
  Vue.use(BootstrapVue);
  
  export default {
    props: ['id'],
    data() {
      return {
        driver: ''
      }
    },

    methods: {
      getDriverInfo: function(id) {
        axios.get('/api/v2/display/driver/' + id)
        .then((response) => {
          this.driver = response.data;
          // console.log(response);
        });
      }
    },
  }
</script>
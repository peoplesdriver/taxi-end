<template>
  <div class="col-lg-1 col-md-2 col-sm-6 col-xs-6">
    <div class="box" v-bind:class="{[taxi.color]: true}">
      <div class="callCode circle" v-bind:class="{[taxi.color + '-color']: true}">
        {{ taxi.callCode }}
      </div>
      <div class="taxiNo">
        {{ taxi.taxiNo }}
      </div>
      <div class="phoneNumber">
        {{ taxi.phoneNumber }}
      </div>
    </div>
  </div>
</template>

<script>
const axios = require('axios');

export default {
  props: ['taxi'],
}
</script>



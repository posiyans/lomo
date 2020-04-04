<template>
  <div class="app-container">
    <h1>В разработке!!!</h1>
    <div>
      <img :src="emptyGif" class="emptyGif">
    </div>
  </div>
</template>

<script>

export default {
  name: 'Clear',
  components: {},
  directives: {},
  filters: {
  },
  data() {
    return {
      emptyGif: 'https://wpimg.wallstcn.com/0e03b7da-db9e-4819-ba10-9016ddfdaed3'
    }
  },
  created() {

  },
  methods: {
    }
}
</script>

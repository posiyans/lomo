<template>
  <div class="q-pa-md">
    <div class="text-weight-bolder q-pa-sm">{{ item.name }}</div>
    <div>
      <q-img
          :src="cameraUrlFilter"
          spinner-color="white"
          style="min-width: 300px; min-height: 200px; max-width: 60vw"
      >
        <template v-slot:error>
          <div class="absolute-full flex flex-center bg-grey-2 text-black">
            Не удается загрузить изображение
          </div>
        </template>
      </q-img>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object
  },
  data () {
    return {
      list: [],
      time: new Date().getTime()
    }
  },
  computed: {
    cameraUrlFilter () {
      return process.env.BASE_API + '/api/v1/all/camera/get-image/' + this.item.id + '?s=' + this.time + '.jpg'
    }
  }

}
</script>

<style scoped>
 .image-slot {
   min-width: 300px;
   min-height: 200px;
   padding-top: 100px;
   text-align: center;
   border: 1px solid #a4a4a4;
 }
</style>

<template>
  <div :key="key" class="q-pa-md">
    <AddCamera @reload="getList" />
    <table>
      <tr v-for="item in list" :key="item.id" class="">
        <td class="relative-position q-px-md">
          <div class="absolute-top-right row text-grey">
            <AddCamera :camera="item" @reload="getList">
              <q-icon name="settings" size="1.5em" class="cursor-pointer" />
            </AddCamera>
            <div class="cursor-pointer" @click="refresh(item.id)">
              <q-icon name="refresh" size="1.5em" />
            </div>
          </div>
          <div class="">
            <strong>Название:</strong>
            {{ item.name }}
          </div>
          <div class="">
            <strong>RTSP:</strong>
            <em>
              {{ item.url }}
            </em>
          </div>
          <div class="">
            <strong>ttl:</strong>
            {{ item.ttl }}
          </div>
        </td>
        <td style="min-width: 300px;">
          <ShowCamera :key="key" :item="item" />
        </td>
      </tr>
    </table>
  </div>
</template>

<script>

import ShowCamera from 'src/Modules/Camera/components/ShowCamera/index.vue'
import AddCamera from 'src/Modules/Camera/components/AddCamera/index.vue'
import { getCameraList, refreshCamera } from 'src/Modules/Camera/api/camera'

export default {
  components: { ShowCamera, AddCamera },
  data() {
    return {
      key: 1,
      showAddForm: false,
      list: [],
      edit: false,
      activeItem: {}
    }
  },
  mounted() {
    this.getList()
  },
  methods: {
    refresh(id) {
      refreshCamera(id)
        .then(response => {
          this.getList()
        })
    },
    getList() {
      getCameraList({ full: true })
        .then(response => {
          this.list = response.data.data
          this.key++
        })
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div class="q-gutter-lg">
    <div>
      <q-input v-model="newMenu.label" outlined label="Название меню" />
    </div>
    <div>
      <MenuSiteSelect v-model="newMenu.parent" outlined />
    </div>
    <div>
      <q-input v-model="newMenu.path" outlined label="Адрес страницы">
        <template v-slot:append>
          <FindRoutePath @select="setData" />
        </template>
      </q-input>
    </div>
    <div>
      <q-btn label="Добавить" color="primary" @click="saveData" />
    </div>

  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import MenuSiteSelect from 'src/Modules/SiteMenu/components/MenuSiteSelect/index.vue'
import { addSiteMenu } from 'src/Modules/SiteMenu/api/menu'
import FindRoutePath from 'src/Modules/SiteMenu/components/FindRoutePath/index.vue'

export default defineComponent({
  components: {
    MenuSiteSelect,
    FindRoutePath
  },
  props: {},
  setup(props, { emit }) {
    const newMenu = ref({
      label: '',
      parent: '',
      path: ''
    })
    const router = useRouter()
    const route = useRoute()
    const saveData = () => {
      addSiteMenu(newMenu.value)
        .then(res => {
          emit('success')
        })

    }
    const setData = (val) => {
      newMenu.value.path = val.path
      if (newMenu.value.label == '') {
        newMenu.value.label = val.label
      }
    }
    onMounted(() => {

    })
    return {
      newMenu,
      setData,
      saveData
    }
  }
})
</script>

<style scoped>

</style>

<template>
  <div class="q-gutter-lg">
    <div>
      <q-input v-model="menu.label" outlined label="Название меню" />
    </div>
    <div>
      <MenuSiteSelect v-model="menu.parent" outlined :optionDisable="[menu.id]" />
    </div>
    <div>
      <q-input v-model="menu.path" outlined label="Адрес страницы">
        <template v-slot:append>
          <FindRoutePath @select="setData" />
        </template>
      </q-input>
    </div>
    <div>
      <q-btn label="Удалить" color="negative" flat @click="deleteItem" />
      <q-btn label="Сохранить" color="primary" @click="saveData" />
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import MenuSiteSelect from 'src/Modules/SiteMenu/components/MenuSiteSelect/index.vue'
import { deleteSiteMenu, updateSiteMenu } from 'src/Modules/SiteMenu/api/menu'
import FindRoutePath from 'src/Modules/SiteMenu/components/FindRoutePath/index.vue'

export default defineComponent({
  components: {
    MenuSiteSelect,
    FindRoutePath
  },
  props: {
    modelValue: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const dialogVisible = ref(false)
    const menu = ref({})
    const deleteItem = () => {
      deleteSiteMenu(props.modelValue.id)
        .then(() => {
          emit('success')
        })
    }
    const saveData = () => {
      updateSiteMenu(props.modelValue.id, menu.value)
        .then(res => {
          emit('success')
        })

    }
    onMounted(() => {
      menu.value = Object.assign({}, props.modelValue)
    })
    const setData = (val) => {
      menu.value.path = val.path
      if (menu.value.label == '') {
        menu.value.label = val.label
      }
    }
    return {
      menu,
      setData,
      dialogVisible,
      saveData,
      deleteItem
    }
  }
})
</script>

<style scoped>

</style>

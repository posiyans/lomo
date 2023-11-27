<template>
  <div class="q-pa-md">
    <div>
      <q-btn color="primary" label="Добавить меню" @click="showAdd=true" />
    </div>
    <q-dialog v-model="showAdd">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Добавить категорию</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>

        <q-card-section>
          <AddSiteMenuForm @success="reload" />
        </q-card-section>
      </q-card>
    </q-dialog>
    <div>
      <q-tree
        :nodes="showMenu"
        default-expand-all
        label-key="name"
        node-key="id"
        :key="key"
      >
        <template v-slot:default-header="prop">
          <ItemMenu :item="prop.node" @success="siteMenuStore.getData()" />
        </template>
      </q-tree>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, onMounted, ref, watch } from 'vue'
import AddSiteMenuForm from 'src/Modules/SiteMenu/components/AddSiteMenuForm/index.vue'
import { useSiteMenuStore } from 'src/Modules/SiteMenu/store/useSiteMenuStore'
import EditSiteMenuForm from 'src/Modules/SiteMenu/components/EditSiteMenuForm/Btn.vue'
import FindRoutePath from 'src/Modules/SiteMenu/components/FindRoutePath/index.vue'
import ItemMenu from './components/ItemMenu/index.vue'

export default defineComponent({
  components: {
    AddSiteMenuForm,
    EditSiteMenuForm,
    FindRoutePath,
    ItemMenu
  },
  props: {},
  setup(props, { emit }) {
    const showAdd = ref(false)
    const key = ref(1)
    const showMenu = computed(() => {
      return [
        {
          label: 'Меню сайта',
          id: 'root',
          readonly: true,
          children: siteMenuStore.menu.filter(item => {
            return !item.readOnly
          })
        }
      ]
    })
    const data = ref(null)
    const siteMenuStore = useSiteMenuStore()
    watch(
      () => siteMenuStore.loading,
      () => {
        key.value++
      }
    )

    const reload = () => {
      siteMenuStore.getData()
      showAdd.value = false
    }
    onMounted(() => {
      siteMenuStore.getData()
    })

    return {
      showAdd,
      showMenu,
      siteMenuStore,
      reload,
      key
    }
  }
})
</script>

<style scoped>

</style>

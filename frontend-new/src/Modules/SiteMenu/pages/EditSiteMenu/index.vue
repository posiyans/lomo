<template>
  <div class="q-pa-md">
    <div>
      <q-btn color="primary" label="Добавить меню" @click="showAdd=true" />
    </div>
    <FindRoutePath />
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
          <div class="row items-center">
            <q-icon :name="prop.node.icon || 'menu'" color="primary" size="28px" class="q-mr-sm" />
            <div>
              <div class="text-primary">{{ prop.node.label }}</div>
              <div class="text-grey text-small-80">
                {{ prop.node.path }}
              </div>
            </div>
            <div>
              <EditSiteMenuForm v-model="prop.node" @success="siteMenuStore.getData()" :key="prop.node.id" />
            </div>
          </div>
        </template>
      </q-tree>
      <el-button type="primary">Сохранить</el-button>
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

export default defineComponent({
  components: {
    AddSiteMenuForm,
    EditSiteMenuForm,
    FindRoutePath
  },
  props: {},
  setup(props, { emit }) {
    const showAdd = ref(false)
    const key = ref(1)
    const showMenu = computed(() => {
      return [
        {
          name: 'Меню сайта',
          id: 'root',
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

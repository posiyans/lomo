<template>
  <div>
    <q-icon name="search" class="cursor-pointer">
      <q-popup-proxy transition-show="flip-up" transition-hide="flip-down">
        <q-banner class="">
          <q-input
            v-model="listQuery.find"
            label="Найти"
            outlined
            :loading="loading"
            dense
            @update:model-value="findOwner"
          />
          <div v-if="!loading && list.length === 0" class="text-small-85 q-pa-xs text-grey">
            Ничего не найдено
          </div>
          <div v-for="owner in list" :key="owner.uid" class="row items-center q-col-gutter">
            <div>
              {{ owner.fullName }}
            </div>
            <div class="row justify-center">
              <div v-for="item in owner.steads" :key="item.stead_id" class="cursor-pointer" @click="emitStead(item)">
                <q-chip outline square color="primary" text-color="white">
                  {{ item.number }}
                </q-chip>
              </div>
            </div>
          </div>
          <div v-if="!loading && total > 3" class="text-small-85 q-pa-xs text-grey">
            3/{{ total }}
          </div>
        </q-banner>
      </q-popup-proxy>
    </q-icon>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, onMounted, ref } from 'vue'
import { fetchOwnerUserList } from 'src/Modules/Owner/api/ownerUserApi'

export default defineComponent({
  components: {},
  props: {
    ownerName: {
      type: String,
      default: ''
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const total = ref(0)
    const loading = ref(false)
    const list = ref([])
    const findOwner = () => {
      list.value = []
      if (listQuery.value.find) {
        loading.value = true
        fetchOwnerUserList(listQuery.value)
          .then(res => {
            list.value = res.data.data
            total.value = res.data.meta.total
          })
          .finally(() => {
            loading.value = false
          })
      }
    }
    const listQuery = ref(
      {
        page: 1,
        limit: 3,
        find: ''
      }
    )
    const emitStead = (val) => {
      emit('setStead', val)
      listQuery.value.find = ''
    }
    onMounted(() => {
      listQuery.value.find = props.ownerName
      findOwner()
    })
    return {
      data,
      list,
      loading,
      emitStead,
      total,
      findOwner,
      listQuery
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

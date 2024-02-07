<template>
  <div>
    <q-table
      flat bordered
      :rows="list"
      :columns="columns"
      hide-bottom
      :pagination="{ rowsPerPage: 0 }"
      wrap-cells
      separator="cell"
      row-key="id"
    >
      <template v-slot:body-cell-number="props">
        <q-td :props="props" auto-width>
          <div class="row items-center no-wrap q-col-gutter-sm">
            <div class="text-small-85">
              {{ props.row.id }}
            </div>
            <ShowPublicTime :time="props.row.updated_at" class="text-wrap text-small-65" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-status="props">
        <q-td :props="props" class="text-grey-7">
          <StatusShow v-model="props.row.status" color class="ellipsis" />
        </q-td>
      </template>
      <template v-slot:body-cell-title="props">
        <q-td :props="props">
          <router-link :to="'/admin/article/edit/'+props.row.id" class="link-type ellipsis-2-lines">
            <span>{{ props.row.title }}</span>
          </router-link>
        </q-td>
      </template>
      <template v-slot:body-cell-user="props">
        <q-td :props="props">
          <div class="text-small-85">
            {{ props.row.user?.last_name }} {{ props.row.user?.name }}
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-category="props">
        <q-td :props="props" auto-width>
          <CategoryShow v-model="props.row.category_id" class="text-no-wrap" />
        </q-td>
      </template>
      <template v-slot:body-cell-comment="props">
        <q-td :props="props">
          <div>
            <ShowCountComments type="article" :object-uid="props.row.uid" hide-zero class="absolute-top-right">
              <template #default="{ label }">
                <q-avatar color="grey-3" text-color="black" class="absolute-top-right" size="20px">
                  {{ label }}
                </q-avatar>
              </template>
            </ShowCountComments>
            <CommentEnableStatus v-model="props.row.allow_comments" color class="ellipsis" />
          </div>
        </q-td>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <div class="q-gutter-sm text-grey-7">
            <q-btn color="primary" flat icon="edit" :to="'/admin/article/edit/'+props.row.id" />
          </div>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent } from 'vue'
import StatusShow from 'src/Modules/Article/Article/components/StatusShow'
import CategoryShow from 'src/Modules/Article/Category/components/CategoryShow'
import ShowPublicTime from 'src/components/ShowTime/index.vue'
import CommentEnableStatus from 'src/Modules/Comments/components/CommentEnableStatus/index.vue'
import ShowCountComments from 'src/Modules/Comments/components/ShowCountComments/index.vue'


export default defineComponent({
  components: {
    StatusShow,
    CategoryShow,
    ShowPublicTime,
    ShowCountComments,
    CommentEnableStatus
  },
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  setup(props, { emit }) {
    const columns = [
      {
        name: 'number',
        align: 'center',
        label: '№',
      },

      {
        name: 'status',
        align: 'center',
        label: 'Статус',
      },
      {
        name: 'title',
        align: 'left',
        label: 'Заголовок',
      },
      {
        name: 'user',
        align: 'left',
        label: 'Автор',
      },
      {
        name: 'category',
        align: 'left',
        label: 'Раздел',
      },
      {
        name: 'comment',
        align: 'center',
        label: 'Комментарии',
      },
      {
        name: 'actions',
        align: 'center',
        label: '',
      }
    ]
    return {
      columns
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

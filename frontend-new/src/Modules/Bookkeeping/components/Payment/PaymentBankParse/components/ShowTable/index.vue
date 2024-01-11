<template>
  <div>
    <div>
      <q-markup-table
        separator="cell"
        flat
        bordered
      >
        <thead>
        <tr>
          <th v-for="h in name" :key="h">{{ h }}</th>
          <th v-if="edit">
            Участок
          </th>
          <th v-if="edit">
            Платеж
          </th>
          <th v-if="edit" />
        </tr>
        </thead>
        <tbody>
        <tr v-for="(line, j) in list" :key="'l'+ j">
          <td v-for="(n, i) in name" :key="i">
            <span v-if="i === 0">
              {{ ++j }}
            </span>
            <span v-if="i > 0">
              {{ line.raw[i - 1] }}
            </span>
          </td>
          <td v-if="edit">
            <div v-if="line.raw_data && line.raw_data[2] !== line.stead.number" class="dark-red" @click="editStead(line)">
              {{ line.raw_data[2] }} -> {{ line.stead.number }}
            </div>
          </td>
          <td v-if="edit">
            <div v-if="line.dubl">
              <el-tag type="danger" effect="dark">
                Повтор
              </el-tag>
            </div>
            <div v-else>
              <el-dropdown @click="dropClick(line)" @command="setType">
                <span class="el-dropdown-link">
                  <span v-if="typeName[line.type]">
                    {{ typeName[line.type].name }}
                  </span>
                  <span v-else class="dark-red">-------!</span>
                  <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu>
                  <el-dropdown-item
                    v-for="item in typeList"
                    :key="item.id"
                    :command="item.id"
                  >
                    {{ item.name }}
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </div>
          </td>
          <td v-if="edit">
            <span v-if="!line.dubl && line.error">
              <el-button type="success" @click="paymentOk(line)">Подтвердить</el-button>
            </span>
          </td>
        </tr>
        </tbody>
      </q-markup-table>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'

export default defineComponent({
  components: {},
  props: {
    list: {
      type: Array,
      required: true
    }
  },
  setup(props, { emit }) {
    const edit = ref(false)
    const data = ref(false)
    const name = [
      '№',
      'дата',
      'Сумма',
      'Участок',
      'ФИО',
      'Назначение'
    ]
    return {
      data,
      edit,
      name
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

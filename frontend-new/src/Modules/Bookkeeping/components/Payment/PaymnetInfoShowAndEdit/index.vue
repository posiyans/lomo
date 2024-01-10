<template>
  <div>
    <table class="do-not-carry full-width">
      <tr class="bg-black-05">
        <th>Поле</th>
        <th>
          <div>
            Значение
          </div>
          <div v-if="payment.payment_type === 2" class="text-small-85 text-red">
            Платеж в кассу
          </div>
        </th>
      </tr>
      <tr>
        <td>id</td>
        <td>{{ payment.id }}</td>
      </tr>
      <tr>
        <td>Дата</td>
        <td>
          <ShowTime :time="payment.payment_date" format="DD-MM-YYYY HH:mm" />
        </td>
      </tr>
      <tr class="black" :class="{'bg-red-2': steadError}">
        <td>Участок</td>
        <td>
          <div v-if="findSteadShow" class="row items-center">
            <SteadSelect
              :model-value="payment.stead.id"
              outlined
              dense
              @selectStead="setStead" />
            <q-space />

            <div>
              <q-btn label="Ok" color="primary" @click="findSteadShow = !findSteadShow" />
            </div>
          </div>
          <div v-else class="row items-center">
            <div>
              <span v-if="steadError">
                {{ payment.raw_data[2] }} -->
                {{ payment.stead.number }}
              </span>
              <span v-else @click="putStead(payment.stead.id)">
                {{ payment.stead.number }}
              </span>
            </div>
            <q-space />
            <div class="">
              <q-btn icon="edit" flat color="primary" dense size="sm" @click="findSteadShow = !findSteadShow" />
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <td>ФИО</td>
        <td>
          {{ payment.raw_data[3] }}
        </td>
      </tr>
      <tr>
        <td>Сумма</td>
        <td>{{ payment.price }}</td>

      </tr>
      <tr>
        <td>Назначение</td>
        <td>
          <q-expansion-item
            :label="payment.raw_data[4]"
          >
            <div class="bg-grey-2 q-pa-sm">
              <pre>{{ payment.raw_data }}           </pre>
            </div>
          </q-expansion-item>
        </td>

      </tr>
      <tr class="black" :class="{'bg-washed-red': type_error}">
        <td>Тип платежа</td>
        <td>
          <el-dropdown @command="setType">
              <span class="el-dropdown-link">
                {{ payment.type_name }}<i class="el-icon-arrow-down el-icon--right" />
              </span>
            <el-dropdown-menu>
              <el-dropdown-item
                v-for="item in receipType"
                :key="item.id"
                :command="item.id"
              >
                {{ item.name }}
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </td>
      </tr>
      <tr v-if="payment.type_depends">
        <td>Показания</td>
        <td>
          <div v-if="payment.depends.length > 0" class="flex">
            <div
              v-for="dep in payment.depends"
              :key="dep.id"
              class="flex br2 b--solid bw1 mh1 ph2 pv1"
              :class="{'b--dark-green': payment.instr_read['d' + dep.id] && payment.instr_read['d' + dep.id].value, 'b--dark-red': !payment.instr_read['d' + dep.id]}"
              @click="setMetering(dep)"
            >
              <div>
                <div>
                  {{ dep.name[1] }}:
                </div>
                <div class="f7 gray">
                  sn: {{ dep.serial_number }}
                </div>
              </div>
              <div v-if="payment.instr_read['d' + dep.id]">
                {{ payment.instr_read['d' + dep.id].value }}
              </div>
            </div>
          </div>
          <div v-else class="red"> Приборы не найдены</div>
        </td>
      </tr>
      <tr>
        <td>Примечание</td>
        <td>
          <div class="row items-center">

            <div v-html="payment.description" />
            <q-space />
            <div>
              <q-btn icon="edit" flat color="primary" dense size="sm" @click="findSteadShow = !findSteadShow" />
              <el-button type="primary" size="mini" plain icon="el-icon-edit" @click="editDescription" />
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
/* eslint-disable */
import { computed, defineComponent, ref } from 'vue'
import ShowTime from 'components/ShowTime/index.vue'
import SteadSelect from 'src/Modules/Stead/components/SteadSelect/index.vue'

export default defineComponent({
  components: {
    ShowTime,
    SteadSelect
  },
  props: {
    payment: {
      type: Object,
      required: true
    }
  },
  setup(props, { emit }) {
    const findSteadShow = ref(false)
    const data = ref(false)
    const steadError = computed(() => {
      return props.payment.stead.number !== props.payment.raw_data[2]
    })
    const setStead = () => {

    }
    return {
      data,
      setStead,
      steadError,
      findSteadShow
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

<template>
  <div>
    <div>
      <q-markup-table
        separator="cell"
        flat
        bordered
        style="width: 100%"
      >
        <thead>
        <tr>
          <th v-for="h in name" :key="h">{{ h }}</th>
          <th v-if="edit">

          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(line, j) in list" :key="'l'+ j" :class="{ 'text-red-4': line.uploadError, 'text-grey-6': line.duplicate }">
          <td>
            {{ ++j }}
          </td>
          <td>
            {{ line.raw_data[0] }}
          </td>
          <td>
            {{ line.raw_data[1] }}
          </td>
          <td>
            <div v-if="line.stead">
              <div v-if="line.stead.number !== line.raw_data[2]">
                {{ line.raw_data[2] }} ->
                <q-chip outline square color="primary" text-color="white">
                  {{ line.stead?.number }}
                </q-chip>
              </div>
              <div v-else>
                <q-chip outline square color="primary" text-color="white">
                  {{ line.stead?.number }}
                </q-chip>
              </div>
            </div>
            <div v-else>
              {{ line.raw_data[2] }}
            </div>
          </td>
          <td>
            <div class="overflow-hidden" style="">
              {{ line.raw_data[3] }}
            </div>
            <div class="row items-center q-col-gutter-xs">
              <div class="overflow-hidden" style="">
                {{ line.raw_data[4] }}
              </div>
              <div v-if="line.rate">
                <q-chip outline square color="primary" text-color="white">
                  {{ line.rate.name }}
                </q-chip>
              </div>
            </div>
          </td>
          <template v-if="edit">
            <template v-if="line.upload">
              <td style="min-width: 50px;">
                <q-linear-progress indeterminate />
              </td>
            </template>
            <template v-else>
              <template v-if="line.duplicate && edit">
                <td>
                  <PaymentInfoShowAndEdit :payment="line">
                    <q-btn label="Повтор" color="purple" outline />
                  </PaymentInfoShowAndEdit>
                </td>
              </template>
              <template v-else>
                <td v-if="line.uploadError && edit">
                  <div class="text-red text-center text-weight-bold">
                    Ошибка загрузки
                  </div>
                </td>
                <td v-if="line.done && !line.uploadError && edit">
                  <PaymentInfoShowAndEdit :payment="line" @reload="reload(line.uid)">
                    <q-btn label="Подробнее" color="primary" outline />
                  </PaymentInfoShowAndEdit>
                  <div v-if="line.error" class="text-red">
                    <q-btn label="Подтвердить данные" icon="done" color="secondary" @click="deleteError(line)" />
                    Требует проверки
                  </div>
                </td>
              </template>
            </template>
          </template>
        </tr>
        </tbody>
      </q-markup-table>
    </div>
  </div>
</template>

<script>
/* eslint-disable */
import { defineComponent, ref } from 'vue'
import PaymentInfoShowAndEdit from 'src/Modules/Bookkeeping/components/Payment/PaymentInfoShowAndEdit/Dialog.vue'
import { updatePayment } from 'src/Modules/Bookkeeping/api/paymentApi'
import { useQuasar } from 'quasar'

export default defineComponent({
  components: {
    PaymentInfoShowAndEdit
  },
  props: {
    list: {
      type: Array,
      required: true
    },
    edit: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { emit }) {
    const data = ref(false)
    const noMessage = ref(false)
    const $q = useQuasar()
    const reload = (uid) => {
      emit('reload', uid)
    }
    const deleteError = (payment) => {
      if (noMessage.value) {
        saveData(payment)
      } else {
        $q.dialog({
          title: 'Подтвердите',
          message: 'Установить что что все данные в платеже проверены?',
          options: {
            type: 'checkbox',
            model: [],
            items: [{
              label: 'Не спрашивать больше',
              value: 'hideDialog',
              color: 'secondary'
            }]
          },
          cancel: {
            noCaps: true,
            flat: true,
            label: 'Отмена',
            color: 'negative'
          },
          ok: {
            noCaps: true,
            outline: true,
            label: 'Сохранить',
            color: 'primary'
          },
          persistent: true
        }).onOk(val => {
          if (val.includes('hideDialog')) {
            console.log('hideDialog')
            noMessage.value = true
          }
          console.log(val)
          saveData(payment)
        })
      }
    }
    const saveData = (payment) => {
      const data = {
        data_verified: true
      }
      payment.upload = true
      updatePayment(payment.id, data)
        .then(() => {
          reload(payment.uid)
        })
        .finally(() => {
          // payment.upload = false
        })
    }

    const name = [
      '№',
      'дата',
      'Сумма',
      'Участок',
      'ФИО, Назначение'
    ]
    return {
      data,
      deleteError,
      reload,
      name
    }
  }
})
</script>

<style scoped lang='scss'>

</style>

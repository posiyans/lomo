<template>
  <div class="row justify-between date-row">
    <div class="date-block">
      <q-input
        :model-value="dateStart"
        :outlined="outlined"
        :dense="dense"
        :clearable="clearable"
        @clear="clearStart"
        label="Показать с даты"
      >
        <q-popup-proxy ref="refDateRangeDialog1" transition-show="scale" transition-hide="scale" :breakpoint="600">
          <q-date :model-value="modelValue[valueStart]" :mask="mask" @update:model-value="setStart">
            <div class="row items-center justify-end q-gutter-sm">
              <q-btn label="OK" color="primary" flat v-close-popup />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-input>
    </div>
    <div class="date-block">
      <q-input
        :model-value="dateStop"
        :outlined="outlined"
        :dense="dense"
        :clearable="clearable"
        label="Показать по дату"
        @clear="clearStop"
      >
        <q-popup-proxy ref="refDateRangeDialog2" transition-show="scale" transition-hide="scale" :breakpoint="600">
          <q-date :model-value="modelValue[valueStop]" :mask="mask" @update:model-value="setStop">
            <div class="row items-center justify-end q-gutter-sm">
              <q-btn label="OK" color="primary" flat v-close-popup />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-input>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    modelValue: Object,
    valueStart: {
      type: String,
      default: 'date_start'
    },
    valueStop: {
      type: String,
      default: 'date_end'
    },
    clearable: {
      type: Boolean,
      default: false
    },
    dense: {
      type: Boolean,
      default: false
    },
    outlined: {
      type: Boolean,
      default: false
    },
    autoClose: {
      type: Boolean,
      default: false
    },
    mask: {
      type: String,
      default: 'YYYY-MM-DD'
    }
  },
  computed: {
    dateStart() {
      if (this.modelValue[this.valueStart]) {
        const d = this.modelValue[this.valueStart].split('-')
        return d[2] + '-' + d[1] + '-' + d[0]
      }
      return ''
    },
    dateStop() {
      if (this.modelValue[this.valueStop]) {
        const d = this.modelValue[this.valueStop].split('-')
        return d[2] + '-' + d[1] + '-' + d[0]
      }
      return ''
    }
  },
  methods: {
    setStart(value) {
      this.setField(value, this.valueStart)
    },
    setStop(value) {
      this.setField(value, this.valueStop)
    },
    setField(value, field) {
      const data = Object.assign({}, this.modelValue)
      data[field] = value
      this.$emit('update:model-value', data)
      if (this.autoClose) {
        this.$refs.refDateRangeDialog1.hide()
        this.$refs.refDateRangeDialog2.hide()
      }
    },
    clearStop() {
      this.setStop('')
    },
    clearStart() {
      this.setStart('')
    }
  }
}
</script>

<style scoped lang="scss">
.date-row {
  width: 300px;
  @media only screen and (max-width: 420px) {
    width: 100%;
    padding-right: 5px;
  }
}

.date-block {
  width: 49%;
}
</style>

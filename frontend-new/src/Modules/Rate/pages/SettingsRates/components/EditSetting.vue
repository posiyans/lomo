<template>
  <!-- eslint-disable -->
  <div>
    <div>
      Название
      <el-input v-model="type.name" placeholder="Название платежа" />
    </div>
    <div>
      Расчитывается
      <el-select
        v-model="type.depends"
        placeholder="placeholder"
      >
        <el-option
          v-for="item in typeReceipt"
          :key="item.key"
          :label="item.label"
          :value="item.key"
        />
      </el-select>
    </div>
    <div v-if="type.depends == 2">
      Единица измерения
      <el-input v-model="type.options.unit_name" placeholder="например кВт/ч" />
    </div>
    <div>
      Выставлять счета автоматически
      <el-select
        v-model="type.auto_invoice"
        placeholder="Выставлять счета автоматически"
      >
        <el-option
          label="Да"
          :value="true"
        />
        <el-option
          label="Нет"
          :value="false"
        />
      </el-select>
    </div>
    <div>
      Период оплаты
      <el-select
        v-model="type.payment_period "
        placeholder="placeholder"
      >
        <el-option
          v-for="item in periodReceipt"
          :key="item.key"
          :label="item.label"
          :value="item.key"
        />
      </el-select>
    </div>
    <div v-if="type.auto_invoice">
      Дата выставления счета
      <el-select
        v-model="type.options.payment_date"
        placeholder="Дата"
      >
        <el-option
          v-for="item in 30"
          :key="item"
          :label="item"
          :value="item"
        />
      </el-select>
      <el-select
        v-if="type.payment_period > 1"
        v-model="type.options.payment_month"
        placeholder="Месяц с которого начинать отсчет"
      >
        <el-option
          v-for="item in month"
          :key="item.key"
          :label="item.label"
          :value="item.key"
        />
      </el-select>
    </div>
    <div>
      Фразы для поиска назначениях платежа
      <el-tag
        v-for="tag in type.options.tag"
        :key="tag"
        closable
        :disable-transitions="false"
        @close="handleClose(tag)"
      >
        {{ tag }}
      </el-tag>
      <el-input
        v-if="inputVisible"
        ref="saveTagInput"
        v-model="inputValue"
        class="input-new-tag"
        size="mini"
        @keyup.enter="handleInputConfirm"
        @blur="handleInputConfirm"
      />
      <el-button v-else class="button-new-tag" size="small" @click="showInput">+ добавить фразу</el-button>
    </div>
    <el-button type="primary" @click="saveReceiptType">Сохранить</el-button>
  </div>
</template>

<script>
/* eslint-disable */
import { updateReceiptType } from 'src/Modules/Receipt/api/receiptAdminApi.js'

export default {
  props: {
    type: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      typeReceipt: [
        { label: 'фиксированная сумма', key: 0 },
        { label: 'от площади участка', key: 1 },
        { label: 'по показаниям прибора учета', key: 2 }
      ],
      month: [
        { label: 'Январь', key: 1 },
        { label: 'Февраль', key: 2 },
        { label: 'Март', key: 3 },
        { label: 'Апрель', key: 4 },
        { label: 'Май', key: 5 },
        { label: 'Июнь', key: 6 }
      ],
      periodReceipt: [
        { label: 'ежемесячно', key: 1 },
        { label: 'ежеквартально', key: 3 },
        { label: 'раз в пол года', key: 6 },
        { label: 'ежегодно', key: 12 }
      ],
      inputVisible: false,
      inputValue: ''
    }
  },
  methods: {
    saveReceiptType() {
      updateReceiptType(this.type.id, this.type)
        .then(response => {
          if (response.data.status) {
            this.$message('Данные успешно сохранены')
            // this. = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
          }
        })
    },
    handleClose(tag) {
      this.type.options.tag.splice(this.type.options.tag.indexOf(tag), 1)
    },

    showInput() {
      this.inputVisible = true
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus()
      })
    },

    handleInputConfirm() {
      const inputValue = this.inputValue
      if (inputValue) {
        if (this.type.options.tag && Array.isArray(this.type.options.tag)) {
          this.type.options.tag.push(inputValue)
        } else {
          this.type.options.tag = [inputValue]
        }
      }
      this.inputVisible = false
      this.inputValue = ''
    }
  }
}
</script>


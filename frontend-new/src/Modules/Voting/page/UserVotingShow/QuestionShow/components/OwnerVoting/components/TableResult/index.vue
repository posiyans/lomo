<template>
  <div>
    <div class="q-pa-sm">
      <div class="row items-center q-mb-sm">
        <div class="text-teal-10 voting-result-cell"> {{ countAnwers }}</div>
        <div class="q-ml-md">
          - <DecOfNum :number="countAnwers" :titles="['участок проголосовал', 'участка проголосовало', 'участков проголосовало']" />
        </div>
      </div>
      <div class="row items-center q-mb-sm">
        <div class="text-red-10 voting-result-cell"> {{ countNotAnswer }} </div>
        <div  class="q-ml-md">
          - <DecOfNum :number="countNotAnswer" :titles="['участок не проголосовал', 'участка не проголосовало', 'участков не проголосовало']" />
        </div>
      </div>
    </div>
    <div class="row items-center">
      <div v-for="i in list" :key="i.id" class="voting-result-cell" :class="{ 'text-teal-10': steads[i.id] , 'text-red-10': !steads[i.id] }">
        {{i.number}}
      </div>
    </div>
  </div>
</template>

<script>
import { getSteadsList } from 'src/Modules/Stead/api/stead.js'
import DecOfNum from 'components/DecOfNum/index.vue'

export default {
  components: {
    DecOfNum
  },
  props: {
    steads: {
      type: Object,
      default: () => ({})
    }
  },
  data () {
    return {
      list: []
    }
  },
  computed: {
    countAnwers () {
      return Object.keys(this.steads).length
    },
    countNotAnswer () {
      return this.list.length - this.countAnwers
    }
  },
  mounted () {
    this.getSteads()
  },
  methods: {
    getSteads () {
      getSteadsList()
        .then(response => {
          this.list = response.data.data
        })
    }
  }

}
</script>

<style scoped>
</style>

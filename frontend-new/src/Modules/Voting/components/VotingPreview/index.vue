<template>
  <q-card>
    <q-card-section>
      <div class="row items-center justify-between q-px-md">
        <div>
          <div class="text-h6 text-weight-bold">
            {{ voting.title }}
          </div>
          <ShowVotingType :type="voting.type" is="div" color class="text-small-90"/>
        </div>
        <ShowVotingStatus :status="voting.status" color class="text-weight-bold"/>
      </div>
      <q-separator class="bg-red-4"/>
    </q-card-section>
    <q-card-section>
      <div v-html="resume" />
    </q-card-section>
    <q-card-actions>
      <div class="row items-center justify-between w-100">
        <div class="row items-center q-col-gutter-sm">
          <div>
            <q-btn no-caps no-wrap color="primary" flat label="Подробнее" @click="show"/>
          </div>
          <div>
            <q-badge outline color="primary" class="q-pa-sm">
              {{voting.countAnswer}}
            </q-badge>
          </div>
        </div>
        <div>
          <ShowTime class="text-right text-grey-7" :time="voting.updated_at" />
        </div>
      </div>
    </q-card-actions>
  </q-card>
</template>

<script>
import ShowTime from 'components/ShowTime/index.vue'
import ShowVotingStatus from 'src/Modules/Voting/components/ShowVotingStatus/index.vue'
import ShowVotingType from 'src/Modules/Voting/components/ShowVotingType/index.vue'

export default {
  components: {
    ShowTime,
    ShowVotingStatus,
    ShowVotingType
  },
  props: {
    voting: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    resume () {
      if (this.voting && this.voting.description) {
        const newsText = this.voting.description.split('</p>')
        if (newsText.length > 2) {
          newsText[0] = newsText[0] + '<span style="color: #6a6a6a"> ...</span>'
        }
        return newsText[0] + '</p>'
      }
      return ''
    }
  },
  methods: {
    show () {
      this.$router.push('/voting/show/' + this.voting.id)
    }
  }
}
</script>

<style scoped>
</style>

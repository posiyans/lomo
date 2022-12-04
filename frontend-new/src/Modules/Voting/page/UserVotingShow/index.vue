<template>
  <div>
    <q-card v-if="voting">
      <q-card-section>
        <div class="row q-col-gutter-md q-pa-sm">
          <ShowVotingType :type="voting.type" color/>
          <ShowVotingStatus :status="voting.status" color class="text-weight-bold"/>
        </div>
        <q-separator/>
      </q-card-section>
      <q-card-section>
        <div>
          <div class="text-h4">{{ voting.title }}</div>
          <div v-if="voting.type='owner'" class="text-blue row q-col-gutter-xs items-center q-mb-md">
            <div>
              Дата голосования с
            </div>
            <ShowTime :time="voting.date_start" format="DD-MM-YYYY" />
            <div>
              по
            </div>
            <ShowTime :time="voting.date_stop" format="DD-MM-YYYY" />
          </div>
          <div v-html="voting.description" />
        </div>
      </q-card-section>
      <q-card-section>
        <div v-if="voting.files && voting.files.length > 0">
          <div class="file-list-header">Файлы:</div>
        </div>
        <FilesListShow v-model="voting.files"/>
      </q-card-section>
      <q-card-section>
        <QuestionShow :voting="voting" @changeResult="fetchVoting" />
      </q-card-section>
      <q-card-actions>
        <div class="row items-center justify-between w-100 q-px-md">
          <div class="row">
            <Back />

          </div>
          <ShowTime :time="voting.date_publish" format="DD-MM-YYYY" class="text-grey-8"/>
        </div>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script>
import { fetchUserVoting } from 'src/Modules/Voting/api/voting.js'
import QuestionShow from './QuestionShow/index.vue'
import Back from 'src/components/Back/index.vue'
import ShowVotingStatus from 'src/Modules/Voting/components/ShowVotingStatus/index.vue'
import ShowTime from 'src/components/ShowTime/index.vue'
import ShowVotingType from 'src/Modules/Voting/components/ShowVotingType'
import FilesListShow from 'src/Modules/Files/components/FilesListShow'
export default {
  components: {
    QuestionShow,
    FilesListShow,
    Back,
    ShowVotingType,
    ShowVotingStatus,
    ShowTime
  },
  props: {
    id: {
      type: Number,
      default: 0
    }
  },
  data () {
    return {
      loading: true,
      voting: false,
      noFound: false
    }
  },
  computed: {
    VotingTypeText () {
      if (this.voting.type === 'public') {
        return 'Открытое голосование'
      }
      return 'Голосование собственников'
    }
  },
  mounted () {
    this.fetchVoting()
  },
  methods: {
    fetchVoting () {
      this.noFound = false
      fetchUserVoting(this.$route.params.id)
        .then(response => {
          if (response.data.status) {
            this.voting = response.data.data
          } else {
            if (response.data.data) {
              this.$message.error(response.data.data)
            }
            this.noFound = true
          }
          this.loading = false
        })
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div>
    <q-btn label="Регистрация" color="white" text-color="black" @click="showLoginForm = !showLoginForm" />
    <q-dialog
      :maximized="mobile"
      v-model="showLoginForm"
    >
      <q-card>
        <q-card-section class="bg-grey-9 text-white">
          <div class="text-h6 text-center relative-position">
            Регистрация на сайте
            <div class="absolute-right">
              <q-btn icon="open_in_new" flat :to="{name: 'UserRegister'}" />
            </div>
          </div>
        </q-card-section>
        <q-card-section>
          <RegisterForm style="width: 450px; max-width: 100%;" @success="showLoginForm = false" />
        </q-card-section>
        <q-card-section class="bg-grey-9 text-white">
          <div class="text-h6 text-center">
            <q-btn color="primary" label="Закрыть" @click="showLoginForm = false" />
          </div>
        </q-card-section>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import RegisterForm from 'src/Modules/Auth/components/RegisterForm/index.vue'

export default {
  components: {
    RegisterForm
  },
  data() {
    return {
      showLoginForm: false
    }
  },
  computed: {
    mobile() {
      return this.$q.platform.is.mobile
    }
  }
}
</script>

<style scoped>

</style>

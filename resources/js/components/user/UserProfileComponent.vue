6<template>
    <div class="row justify-content-center">
        <div class="col-md-10">
   <div class="card">
       <div class="card-header">Профиль</div>

       <div class="card-body">
       <el-form ref="form" :model="form" label-width="220px">
           <el-form-item label="E-mail">
               <el-input v-model="user.email" readonly></el-input>
           </el-form-item>
           <el-form-item label="Фамилия">
               <el-input v-model="user.last_name"></el-input>
           </el-form-item>
           <el-form-item label="Имя">
               <el-input v-model="user.name"></el-input>
           </el-form-item>
           <el-form-item label="Отчество">
               <el-input v-model="user.middle_name"></el-input>
           </el-form-item>
           <el-form-item label="Телефон">
               <el-input v-model="user.phone"></el-input>
           </el-form-item>
           <el-form-item label="Адрес регистрации/почтовый адрес">
               <el-input type="textarea" v-model="user.adres"></el-input>
           </el-form-item>
           <el-form-item label="Номер Участка">
               <el-select
                   v-model="user.steads_id"
                   multiple
                   filterable
                   remote
                   reserve-keyword
                   placeholder="Введите номер участка"
                   no-data-text="Данный номер не найден"
                   :remote-method="findStead"
                   :loading="loading">
                   <el-option
                       v-for="item in steadsList"
                       :key="item.id"
                       :label="item.number"
                       :value="item.id">
                   </el-option>
               </el-select>
           </el-form-item>
           <el-form-item label="">
               <el-checkbox v-model="user.consent_personal" label="Cогласен на обработку персональных данных и с условиями пользовательского соглашения" name="type"></el-checkbox>
               <el-checkbox v-model="user.consent_to_email" label="Согласен получать письма с новостями от правления" name="type"></el-checkbox>
           </el-form-item>
           <el-form-item>
               <el-button type="primary" @click="saveData" :disabled="!user.consent_personal">Сохранить</el-button>
               <el-button>Отменить</el-button>
           </el-form-item>
       </el-form>
       </div>
   </div>
        </div>
    </div>
</template>

<script>
import { getMyInfo, setUserInfo } from '../../api/user.js'
import { getSteadsList } from '../../api/stead.js'

export default {
    data() {
        return {
            data: false,
            user: {
                steads: []
            },
            form: {
                name: '',
                region: '',
                date1: '',
                date2: '',
                delivery: false,
                type: [],
                resource: '',
                desc: ''
            },
            loading: false,
            steadsList: [100, 200,300,400]


        }
    },
    mounted() {
        this.getInfo()
    },
    methods: {
        saveData(){
            if (this.user.consent_personal){
                const data ={
                    user: this.user
                }
                setUserInfo(data)
                    .then(response => {
                        console.log(response)
                    })
            }
        },
        getInfo(){
            getMyInfo()
                .then(response =>{
                    console.log(response.data)
                    this.user = response.data
                })
            getSteadsList()
                .then(response => {
                    console.log(response.data)
                    this.steadsList = response.data
                })
        },
        findStead(query){
           console.log(query)
            const data = {
               query: query
            }
           getSteadsList(data)
               .then(response => {
                    console.log(response.data)
                    this.steadsList = response.data
                })
        }
    }
}
</script>

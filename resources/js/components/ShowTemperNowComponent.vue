<template>
    <span v-if="data">
        <el-tooltip class="item" effect="dark" :content="data.time | moment('Do MMMM, HH:mm') " placement="bottom">
            <a href="/temper">
                {{data.temp}}°C
            </a>
        </el-tooltip>
    </span>
</template>

<script>
    import {getTemperNow } from '../api/temper.js'

    export default {
        data() {
            return {
                data: false
            }
        },
        mounted() {
            getTemperNow().then(response => {
                this.data = response.data.temper;
                console.log(data)

            })
        },
        methods: {
        }
    }
</script>

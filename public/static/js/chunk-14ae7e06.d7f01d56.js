(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-14ae7e06"],{"1dbd":function(e,t,o){},b179:function(e,t,o){"use strict";var r=o("1dbd"),a=o.n(r);a.a},b9f6:function(e,t,o){"use strict";o.r(t);var r=function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"ps-card"},[o("el-card",[o("div",{staticClass:"article-preview-header"},[o("h2",[e._v("Установить новый пароль")])]),e._v(" "),o("div",{staticClass:"article-preview-body"},[o("span",[e._v("Введите новвый пароль")])]),e._v(" "),o("div",{staticClass:"article-preview-body",staticStyle:{"padding-left":"20px",width:"400px"}},[o("el-form",{ref:"Resetform",attrs:{model:e.form,"label-width":"120px","label-position":"top",rules:e.loginRules}},[o("el-form-item",{attrs:{label:"E-mail",prop:"email"}},[o("el-input",{ref:"email",attrs:{name:"email",readonly:""},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"Пароль",prop:"password"}},[o("el-input",{attrs:{"show-password":"",tabindex:"1"},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),e._v(" "),o("el-form-item",{attrs:{label:"Потвердить пароль",prop:"password_confirmation"}},[o("el-input",{attrs:{"show-password":"",tabindex:"2"},model:{value:e.form.password_confirmation,callback:function(t){e.$set(e.form,"password_confirmation",t)},expression:"form.password_confirmation"}})],1)],1)],1),e._v(" "),o("div",{staticClass:"article-preview-footer"},[o("el-row",{staticClass:"row-bg",attrs:{type:"flex",justify:"space-between",align:"center"}},[o("el-col",{attrs:{span:12}},[o("div",{staticStyle:{"padding-left":"20px"}},[o("el-button",{attrs:{type:"primary",size:"mini",plain:""},on:{click:e.save}},[e._v("Сохранить")])],1)])],1)],1)])],1)},a=[],i=o("2d8e"),s=(o("4360"),o("a18c"),{data:function(){var e=this,t=function(e,t,o){console.log("valid eamil");var r=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;r.test(t)?o():o(new Error("Пожалуйста, введите действующий email"))},o=function(e,t,o){t.length<6?o(new Error("Пароль не может быть менее 8 символов")):o()},r=function(t,o,r){""===o?r(new Error("Пожалуйста, введите пароль еще раз")):o!==e.form.password?r(new Error("Пароли не совпадают!")):r()};return{form:{email:null,token:"",password:"",password_confirmation:""},loginRules:{email:[{required:!0,trigger:"blur",validator:t}],password:[{required:!0,trigger:"blur",validator:o}],password_confirmation:[{required:!0,trigger:"blur",validator:r}]}}},mounted:function(){console.log("this.$route.par"),console.log(this.$route),this.form.email=this.$route.query.email,this.form.token=this.$route.query.token},methods:{save:function(){var e=this;this.$refs.Resetform.validate((function(t){console.log(t),t&&(Object(i["e"])(e.form).then((function(t){t.data?window.location="/":e.$alert("Упс. Что-то пошло не так, попробуйье еще раз.","Ошибка",{confirmButtonText:"OK"}),console.log(t.data)})),console.log("ok"))}))}}}),l=s,n=(o("b179"),o("2877")),c=Object(n["a"])(l,r,a,!1,null,"0410759e",null);t["default"]=c.exports}}]);
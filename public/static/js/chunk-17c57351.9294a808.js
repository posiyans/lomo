(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-17c57351"],{"8c14":function(e,t,n){},c6e4:function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"app-container"},[n("el-tag",[e._v("Настройки")]),e._v(" "),n("el-tabs",{staticClass:"setting-body",attrs:{type:"border-card"}},[n("el-tab-pane",{attrs:{label:"Реквизиты"}},[n("GardeningCommponent")],1)],1)],1)},o=[],l=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("el-form",{ref:"form",attrs:{model:e.form,"label-width":"200px"}},[n("el-form-item",{attrs:{label:"Название"}},[n("el-input",{model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"Полное название"}},[n("el-input",{model:{value:e.form.full_name,callback:function(t){e.$set(e.form,"full_name",t)},expression:"form.full_name"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"Счет"}},[n("el-input",{model:{value:e.form.PersonalAcc,callback:function(t){e.$set(e.form,"PersonalAcc",t)},expression:"form.PersonalAcc"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"Банк"}},[n("el-input",{model:{value:e.form.BankName,callback:function(t){e.$set(e.form,"BankName",t)},expression:"form.BankName"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"БИК"}},[n("el-input",{model:{value:e.form.BIC,callback:function(t){e.$set(e.form,"BIC",t)},expression:"form.BIC"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"Корсчет"}},[n("el-input",{model:{value:e.form.CorrespAcc,callback:function(t){e.$set(e.form,"CorrespAcc",t)},expression:"form.CorrespAcc"}})],1),e._v(" "),n("el-form-item",{attrs:{label:"ИНН"}},[n("el-input",{model:{value:e.form.PayeeINN,callback:function(t){e.$set(e.form,"PayeeINN",t)},expression:"form.PayeeINN"}})],1),e._v(" "),n("el-form-item",[n("el-button",{attrs:{type:"primary"},on:{click:e.onSubmit}},[e._v("Сохранить")]),e._v(" "),n("el-button",[e._v("Отмена")]),e._v("\n    обновлено "+e._s(e.form.updated_at)+"\n  ")],1)],1)},r=[],m=n("a2df"),c=n("b775");function s(e){return Object(c["a"])({url:"/api/v1/admin/gardening",method:"post",data:e})}var f={data:function(){return{form:{name:"",full_name:"",PersonalAcc:"",BankName:"",BIC:"",CorrespAcc:"",PayeeINN:""}}},mounted:function(){this.getData()},methods:{getData:function(){var e=this;Object(m["a"])().then((function(t){e.form=t.data}))},onSubmit:function(){s(this.form).then((function(e){console.log(e.data)})),console.log("submit!")}}},i=f,u=n("2877"),p=Object(u["a"])(i,l,r,!1,null,null,null),b=p.exports,d={components:{GardeningCommponent:b}},v=d,_=(n("ef03"),Object(u["a"])(v,a,o,!1,null,"106842f4",null));t["default"]=_.exports},ef03:function(e,t,n){"use strict";var a=n("8c14"),o=n.n(a);o.a}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3c6f0f79"],{"09f4":function(t,e,a){"use strict";a.d(e,"a",(function(){return l})),Math.easeInOutQuad=function(t,e,a,i){return t/=i/2,t<1?a/2*t*t+e:(t--,-a/2*(t*(t-2)-1)+e)};var i=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||function(t){window.setTimeout(t,1e3/60)}}();function n(t){document.documentElement.scrollTop=t,document.body.parentNode.scrollTop=t,document.body.scrollTop=t}function s(){return document.documentElement.scrollTop||document.body.parentNode.scrollTop||document.body.scrollTop}function l(t,e,a){var l=s(),o=t-l,r=20,u=0;e="undefined"===typeof e?500:e;var c=function t(){u+=r;var s=Math.easeInOutQuad(u,l,o,e);n(s),u<e?i(t):a&&"function"===typeof a&&a()};c()}},6724:function(t,e,a){"use strict";a("8d41");var i="@@wavesContext";function n(t,e){function a(a){var i=Object.assign({},e.value),n=Object.assign({ele:t,type:"hit",color:"rgba(0, 0, 0, 0.15)"},i),s=n.ele;if(s){s.style.position="relative",s.style.overflow="hidden";var l=s.getBoundingClientRect(),o=s.querySelector(".waves-ripple");switch(o?o.className="waves-ripple":(o=document.createElement("span"),o.className="waves-ripple",o.style.height=o.style.width=Math.max(l.width,l.height)+"px",s.appendChild(o)),n.type){case"center":o.style.top=l.height/2-o.offsetHeight/2+"px",o.style.left=l.width/2-o.offsetWidth/2+"px";break;default:o.style.top=(a.pageY-l.top-o.offsetHeight/2-document.documentElement.scrollTop||document.body.scrollTop)+"px",o.style.left=(a.pageX-l.left-o.offsetWidth/2-document.documentElement.scrollLeft||document.body.scrollLeft)+"px"}return o.style.backgroundColor=n.color,o.className="waves-ripple z-active",!1}}return t[i]?t[i].removeHandle=a:t[i]={removeHandle:a},a}var s={bind:function(t,e){t.addEventListener("click",n(t,e),!1)},update:function(t,e){t.removeEventListener("click",t[i].removeHandle,!1),t.addEventListener("click",n(t,e),!1)},unbind:function(t){t.removeEventListener("click",t[i].removeHandle,!1),t[i]=null,delete t[i]}},l=function(t){t.directive("waves",s)};window.Vue&&(window.waves=s,Vue.use(l)),s.install=l;e["a"]=s},"838f":function(t,e,a){"use strict";a.d(e,"b",(function(){return n})),a.d(e,"d",(function(){return s})),a.d(e,"c",(function(){return l})),a.d(e,"a",(function(){return o}));var i=a("b775");function n(t){return Object(i["a"])({url:"/api/v1/admin/appeal",method:"get",params:t})}function s(t,e){return Object(i["a"])({url:"/api/v1/admin/appeal/"+e,method:"put",data:t})}function l(t){return Object(i["a"])({url:"/api/v1/article/pv",method:"get",params:{pv:t}})}function o(t){return Object(i["a"])({url:"/api/v1/article/create",method:"post",data:t})}},"8d41":function(t,e,a){},"926a":function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"app-container"},[a("div",{staticClass:"filter-container"},[a("el-input",{staticClass:"filter-item",staticStyle:{width:"200px"},attrs:{placeholder:"Поиск"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.handleFilter(e)}},model:{value:t.listQuery.title,callback:function(e){t.$set(t.listQuery,"title",e)},expression:"listQuery.title"}}),t._v(" "),a("el-select",{staticClass:"filter-item",staticStyle:{width:"130px"},attrs:{placeholder:"Тип",clearable:""},model:{value:t.listQuery.type,callback:function(e){t.$set(t.listQuery,"type",e)},expression:"listQuery.type"}},t._l(t.appealTypeObject,(function(t){return a("el-option",{key:t.key,attrs:{label:t.display_name,value:t.key}})})),1),t._v(" "),a("el-select",{staticClass:"filter-item",staticStyle:{width:"130px"},attrs:{placeholder:"Статус",clearable:""},model:{value:t.listQuery.status,callback:function(e){t.$set(t.listQuery,"status",e)},expression:"listQuery.status"}},t._l(t.selectStatusOptions,(function(t){return a("el-option",{key:t.key,attrs:{label:t.display_name,value:t.key}})})),1),t._v(" "),a("el-button",{directives:[{name:"waves",rawName:"v-waves"}],staticClass:"filter-item",attrs:{type:"primary",icon:"el-icon-search"},on:{click:t.handleFilter}},[t._v("\n        Показать\n      ")]),t._v(" "),t._e()],1),t._v(" "),a("el-table",{directives:[{name:"loading",rawName:"v-loading",value:t.listLoading,expression:"listLoading"}],key:t.tableKey,staticStyle:{width:"100%"},attrs:{data:t.list,border:"",fit:"","highlight-current-row":""},on:{"sort-change":t.sortChange}},[a("el-table-column",{attrs:{label:"№",align:"center",width:"80"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("span",[t._v(t._s(i.id))])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"Дата",prop:"created_at",width:"150px",sortable:"custom",align:"center"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("span",[t._v(t._s(t._f("parseTime")(i.created_at," {d}-{m}-{y} {h}:{i}")))])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"Тип",align:"center",width:"150px"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("span",{staticClass:"link-type"},[t._v(t._s(t._f("typeFilter")(i.type)))])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"Заголовок","min-width":"150px"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("el-tag",[t._v(t._s(i.user.fName))]),t._v(" "),a("span",{staticClass:"link-type",on:{click:function(e){return t.handleShow(i)}}},[t._v(t._s(i.title))])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"Статус","class-name":"status-col",width:"100"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;return[a("el-tag",{attrs:{type:t._f("statusFilter")(i.status)}},[t._v("\n            "+t._s(i.status)+"\n          ")])]}}])}),t._v(" "),a("el-table-column",{attrs:{label:"Actions",align:"center",width:"230","class-name":"small-padding fixed-width"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.row;e.$index;return[a("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(e){return t.handleUpdate(i)}}},[t._v("\n            Edit\n          ")]),t._v(" "),"open"==i.status?a("el-button",{attrs:{size:"small",type:"danger"},on:{click:function(e){return t.handleModifyStatus(i,"close")}}},[t._v("\n            Закрыть\n          ")]):t._e(),t._v(" "),"open"!=i.status?a("el-button",{attrs:{size:"small"},on:{click:function(e){return t.handleModifyStatus(i,"open")}}},[t._v("\n           Закрыто\n          ")]):t._e()]}}])})],1),t._v(" "),a("pagination",{directives:[{name:"show",rawName:"v-show",value:t.total>0,expression:"total>0"}],attrs:{total:t.total,page:t.listQuery.page,limit:t.listQuery.limit},on:{"update:page":function(e){return t.$set(t.listQuery,"page",e)},"update:limit":function(e){return t.$set(t.listQuery,"limit",e)},pagination:t.getList}}),t._v(" "),a("el-dialog",{attrs:{title:t.textMap[t.dialogStatus],visible:t.dialogFormVisible},on:{"update:visible":function(e){t.dialogFormVisible=e}}},[a("el-form",{ref:"dataForm",staticStyle:{width:"600px","margin-left":"100px"},attrs:{rules:t.rules,model:t.temp,"label-position":"left","label-width":"100px"}},[a("el-form-item",{attrs:{label:"Автор",prop:"user"}},[a("el-tag",[t._v(" "+t._s(t.temp.user.fullName)+" ")]),t._v(" "),a("el-tag",{attrs:{type:"info"}},[t._v(t._s(t._f("parseTime")(t.temp.created_at," {d}-{m}-{y} {h}:{i}")))])],1),t._v(" "),a("el-form-item",{attrs:{label:"Заголовок",prop:"title"}},[a("el-input",{attrs:{readonly:""},model:{value:t.temp.title,callback:function(e){t.$set(t.temp,"title",e)},expression:"temp.title"}})],1),t._v(" "),a("el-form-item",{attrs:{label:"Текст"}},[a("el-input",{attrs:{autosize:{minRows:2},type:"textarea",placeholder:"Нет теста =(",readonly:""},model:{value:t.temp.text,callback:function(e){t.$set(t.temp,"text",e)},expression:"temp.text"}}),t._v(" "),t._l(t.temp.message,(function(e){return a("div",{key:e.id,staticClass:"text item"},[a("el-avatar",{attrs:{size:30,src:e.user.avatar},on:{error:t.errorHandler}},[a("img",{attrs:{src:"/images/default-avatar.jpg"}})]),t._v("\n            "+t._s(e.user.last_name)+" "+t._s(e.user.name)+". "+t._s(e.user.middle_name)+". "+t._s(e.text)+"\n          ")],1)}))],2),t._v(" "),a("el-form-item",{attrs:{label:"Ответить"}},[a("el-input",{attrs:{autosize:{minRows:2,maxRows:4},type:"textarea",placeholder:"Введите сообщение для пользователя"},model:{value:t.temp.new_message,callback:function(e){t.$set(t.temp,"new_message",e)},expression:"temp.new_message"}})],1)],1),t._v(" "),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:function(e){t.dialogFormVisible=!1}}},[t._v("\n          Отмена\n        ")]),t._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.updateData()}}},[t._v("\n          Отправить\n        ")])],1)],1),t._v(" "),a("el-dialog",{attrs:{visible:t.dialogPvVisible,title:"Reading statistics"},on:{"update:visible":function(e){t.dialogPvVisible=e}}},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.pvData,border:"",fit:"","highlight-current-row":""}},[a("el-table-column",{attrs:{prop:"key",label:"Channel"}}),t._v(" "),a("el-table-column",{attrs:{prop:"pv",label:"Pv"}})],1),t._v(" "),a("span",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{attrs:{type:"primary"},on:{click:function(e){t.dialogPvVisible=!1}}},[t._v("Confirm")])],1)],1)],1)},n=[],s=(a("20d6"),a("28a5"),a("838f")),l=a("6724"),o=a("ed08"),r=a("333d"),u=[{key:"CN",display_name:"China"},{key:"US",display_name:"USA"},{key:"JP",display_name:"Japan"},{key:"EU",display_name:"Eurozone"}],c=[{key:"open",display_name:"Открытые"},{key:"close",display_name:"Закрытые"},{key:"all",display_name:"Все"}],d=[{key:"other",display_name:"Прочее"},{key:"stead",display_name:"Участок"}],p=d.reduce((function(t,e){return t[e.key]=e.display_name,t}),{}),m=(u.reduce((function(t,e){return t[e.key]=e.display_name,t}),{}),{name:"ComplexTable",components:{Pagination:r["a"]},directives:{waves:l["a"]},filters:{statusFilter:function(t){var e={close:"success",draft:"info",open:"danger"};return e[t]},typeFilter:function(t){var e=t.split("_");return e[1]in p?p[e[1]]:"Другое"}},data:function(){return{tableKey:0,list:null,total:0,listLoading:!0,listQuery:{page:1,limit:20,importance:void 0,title:void 0,type:void 0,status:"open",sort:"+created_at"},importanceOptions:[1,2,3],calendarTypeOptions:u,selectStatusOptions:c,appealTypeObject:d,sortOptions:[{label:"ID Ascending",key:"+id"},{label:"ID Descending",key:"-id"}],statusOptions:["published","draft","deleted"],temp:{id:void 0,user:{},importance:1,new_message:"",timestamp:new Date,title:"",type:"",status:"published"},dialogFormVisible:!1,dialogStatus:"",textMap:{update:"Edit",create:"Create",show:"Обращение"},dialogPvVisible:!1,pvData:[],rules:{},downloadLoading:!1}},mounted:function(){this.getList()},methods:{getList:function(){var t=this;this.listLoading=!0,Object(s["b"])(this.listQuery).then((function(e){t.list=e.data.data,t.total=e.data.meta.total,setTimeout((function(){t.listLoading=!1}),1500)}))},handleFilter:function(){this.listQuery.page=1,this.getList()},handleModifyStatus:function(t,e){var a=this;t.status=e;var i={appeal:t};Object(s["d"])(i,t.id).then((function(t){console.log(t.data),a.$message({message:"Success",type:"success"})}))},sortChange:function(t){var e=t.prop,a=t.order;"created_at"===e&&this.sortByID(a)},sortByID:function(t){console.log(t),this.listQuery.sort="ascending"===t?"+created_at":"descending"===t?"-created_at":"",this.handleFilter()},resetTemp:function(){this.temp={id:void 0,user:{},importance:1,remark:"",timestamp:new Date,title:"",status:"published",type:""}},createData:function(){var t=this;this.$refs["dataForm"].validate((function(e){e&&(t.temp.id=parseInt(100*Math.random())+1024,t.temp.author="vue-element-admin",Object(s["a"])(t.temp).then((function(){t.list.unshift(t.temp),t.dialogFormVisible=!1,t.$notify({title:"Success",message:"Created Successfully",type:"success",duration:2e3})})))}))},handleUpdate:function(t){var e=this;this.temp=Object.assign({},t),this.temp.timestamp=new Date(this.temp.timestamp),this.dialogStatus="update",this.dialogFormVisible=!0,this.$nextTick((function(){e.$refs["dataForm"].clearValidate()}))},handleShow:function(t){var e=this;this.temp=Object.assign({},t),this.temp.timestamp=new Date(this.temp.timestamp),this.dialogStatus="show",this.dialogFormVisible=!0,this.$nextTick((function(){e.$refs["dataForm"].clearValidate()}))},updateData:function(){var t=this,e={appeal:this.temp};Object(s["d"])(e,this.temp.id).then((function(e){var a=t.list.findIndex((function(e){return e.id===t.temp.id}));t.temp.message.push({text:t.temp.new_message,user:{name:"я"}}),t.temp.new_message="",t.list.splice(a,1,t.temp),console.log(e.data),t.dialogFormVisible=!1,t.$notify({title:"Success",message:"Update Successfully",type:"success",duration:2e3})}))},handleDelete:function(t,e){this.$notify({title:"Success",message:"Delete Successfully",type:"success",duration:2e3}),this.list.splice(e,1)},handleFetchPv:function(t){var e=this;Object(s["c"])(t).then((function(t){e.pvData=t.data.pvData,e.dialogPvVisible=!0}))},handleDownload:function(){var t=this;this.downloadLoading=!0,Promise.all([a.e("chunk-5bdd67a2"),a.e("chunk-2d3eea4a"),a.e("chunk-58293907")]).then(a.bind(null,"4bf8d")).then((function(e){var a=["timestamp","title","type","importance","status"],i=["timestamp","title","type","importance","status"],n=t.formatJson(i);e.export_json_to_excel({header:a,data:n,filename:"table-list"}),t.downloadLoading=!1}))},formatJson:function(t){return this.list.map((function(e){return t.map((function(t){return"timestamp"===t?Object(o["d"])(e[t]):e[t]}))}))}}}),f=m,v=a("2877"),y=Object(v["a"])(f,i,n,!1,null,null,null);e["default"]=y.exports}}]);
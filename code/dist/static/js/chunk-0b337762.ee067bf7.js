(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0b337762"],{"403f":function(e,t,a){"use strict";var n=a("d4bc"),i=a.n(n);i.a},7647:function(e,t,a){"use strict";a.d(t,"m",function(){return i}),a.d(t,"u",function(){return s}),a.d(t,"C",function(){return d}),a.d(t,"f",function(){return l}),a.d(t,"y",function(){return o}),a.d(t,"k",function(){return r}),a.d(t,"c",function(){return c}),a.d(t,"o",function(){return u}),a.d(t,"w",function(){return m}),a.d(t,"n",function(){return f}),a.d(t,"j",function(){return v}),a.d(t,"x",function(){return g}),a.d(t,"p",function(){return h}),a.d(t,"a",function(){return p}),a.d(t,"q",function(){return _}),a.d(t,"v",function(){return b}),a.d(t,"D",function(){return k}),a.d(t,"g",function(){return y}),a.d(t,"s",function(){return O}),a.d(t,"d",function(){return T}),a.d(t,"A",function(){return M}),a.d(t,"i",function(){return w}),a.d(t,"t",function(){return C}),a.d(t,"e",function(){return j}),a.d(t,"B",function(){return S}),a.d(t,"l",function(){return I}),a.d(t,"r",function(){return D}),a.d(t,"b",function(){return P}),a.d(t,"z",function(){return x}),a.d(t,"h",function(){return A});var n=a("b775");function i(e){return Object(n["a"])({url:"/manage/Man/selDevType",method:"POST",data:e})}function s(e){return Object(n["a"])({url:"/manage/Man/addDevType",method:"POST",data:e})}function d(e){return Object(n["a"])({url:"/manage/Man/updDevType",method:"POST",data:e})}function l(e){return Object(n["a"])({url:"/manage/Man/delDevType",method:"POST",data:e})}function o(e){return Object(n["a"])({url:"/manage/Index/globalConfig",method:"POST",data:e})}function r(e){return Object(n["a"])({url:"/manage/Index/selConfig",method:"POST",data:e})}function c(e){return Object(n["a"])({url:"/manage/Index/delBanner",method:"POST",data:e})}function u(e){return Object(n["a"])({url:"/manage/Index/selPayMoudle",method:"POST",data:e})}function m(e){return Object(n["a"])({url:"/manage/Index/addMoudlePay",method:"POST",data:e})}function f(e){return Object(n["a"])({url:"/manage/Index/selMoudlePay",method:"POST",data:e})}function v(e){return Object(n["a"])({url:"/manage/Index/selModule",method:"POST",data:e})}function g(e){return Object(n["a"])({url:"/manage/Index/setDevMod",method:"POST",data:e})}function h(e){return Object(n["a"])({url:"/manage/Index/selDevMod",method:"POST",data:e})}function p(e){return Object(n["a"])({url:"/manage/Index/updPayMoudle",method:"POST",data:e})}function _(e){return Object(n["a"])({url:"/manage/man/selHoliday",method:"POST",data:e})}function b(e){return Object(n["a"])({url:"/manage/man/addHoliday",method:"POST",data:e})}function k(e){return Object(n["a"])({url:"/manage/man/updHoliday",method:"POST",data:e})}function y(e){return Object(n["a"])({url:"/manage/man/delHoliday",method:"POST",data:e})}function O(e){return Object(n["a"])({url:"/manage/Man/addCopyConf",method:"POST",data:e})}function T(e){return Object(n["a"])({url:"/manage/Man/delCopyConf",method:"POST",data:e})}function M(e){return Object(n["a"])({url:"/manage/Man/updCopyConf",method:"POST",data:e})}function w(e){return Object(n["a"])({url:"/manage/Man/selCopyConf",method:"POST",data:e})}function C(e){return Object(n["a"])({url:"/manage/Man/addInterface",method:"POST",data:e})}function j(e){return Object(n["a"])({url:"/manage/Man/delInterface",method:"POST",data:e})}function S(e){return Object(n["a"])({url:"/manage/Man/updInterface",method:"POST",data:e})}function I(e){return Object(n["a"])({url:"/manage/Man/selInterface",method:"POST",data:e})}function D(e){return Object(n["a"])({url:"/manage/Man/addDevPosition",method:"POST",data:e})}function P(e){return Object(n["a"])({url:"/manage/Man/delDevPosition",method:"POST",data:e})}function x(e){return Object(n["a"])({url:"/manage/Man/updDevPosition",method:"POST",data:e})}function A(e){return Object(n["a"])({url:"/manage/Man/selDevPosition",method:"POST",data:e})}},a15f:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"kioskManager appMainWarp"},[n("el-row",{staticClass:"demo-el-row",attrs:{type:"flex",justify:"start"}},[n("el-col",{attrs:{span:6}},[[n("span",[e._v("所在区域：")]),e._v(" "),n("span",[e._v(e._s(e.kioskInfo.position_name))])]],2),e._v(" "),n("el-col",{attrs:{span:6}},[[n("span",[e._v("自助机编号：")]),e._v(" "),n("span",[e._v(e._s(e.kioskInfo.code))])]],2),e._v(" "),n("el-col",{attrs:{span:6}},[[n("span",[e._v("自助机型号：")]),e._v(" "),n("span",[e._v(e._s(e.kioskInfo.dev_type))])]],2),e._v(" "),n("el-col",{attrs:{span:6}},[[n("span",[e._v("当前状态：")]),e._v(" "),n("span",[e._v(e._s(e.kioskInfo.state_name))])]],2)],1),e._v(" "),n("el-form",{staticClass:"demo-form-inline",attrs:{inline:!0}},[n("el-form-item",{attrs:{label:"选择功能名称:"}},[n("el-select",{attrs:{filterable:"",placeholder:"请选择"},on:{change:e.onsearch},model:{value:e.searchKey.id,callback:function(t){e.$set(e.searchKey,"id",t)},expression:"searchKey.id"}},e._l(e.searchKey.funcTypeList,function(e){return n("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})}),1)],1),e._v(" "),n("el-form-item",{staticClass:"btn"},[n("el-button",{attrs:{type:"primary"},on:{click:e.onsearch}},[e._v("查询")]),e._v(" "),n("el-button",{attrs:{type:"success"},on:{click:e.multiChange}},[e._v("批量操作")])],1)],1),e._v(" "),n("el-row",{staticClass:"row-bg",attrs:{type:"flex",justify:"start"}},e._l(e.searchData,function(t,i){return n("el-col",{key:i,attrs:{span:6}},[n("div",{staticClass:"func-item"},[[n("el-checkbox-group",{on:{change:function(t){return e.multi(t)}},model:{value:e.fun_ids,callback:function(t){e.fun_ids=t},expression:"fun_ids"}},[n("el-checkbox",{attrs:{label:t.id}})],1),e._v(" "),n("div",{staticClass:"func-box"},[n("div",{staticClass:"func-model"},[n("div",{staticClass:"func-img"},[n("img",{staticClass:"icon_img",attrs:{src:t.img_url}})]),e._v(" "),n("p",{staticClass:"func-title"},[e._v(e._s(t.name))]),e._v(" "),"1"===t.is_disabled?n("div",{staticClass:"time"},[n("span",[e._v(e._s(t.service_start_time)+"-"+e._s(t.service_end_time))])]):e._e(),e._v(" "),"0"===t.is_disabled?n("div",{staticClass:"kiosk-status"},[n("span",[e._v("已禁用")])]):e._e()]),e._v(" "),n("div",{staticClass:"img-icon"},[n("img",{staticClass:"icon",attrs:{src:a("c6d6")},on:{click:function(a){return e.single(t)}}})])])]],2)])}),1),e._v(" "),n("func-dev-dialog",{attrs:{title:"操作",counter:e.counter,"model-flag":e.singleModel},on:{submit:e.singelSevModel,addComponent:e.addComponent,close:e.closeModel}}),e._v(" "),n("func-dev-dialog",{attrs:{title:"批量操作",counter:e.counter,"model-flag":e.multiModel},on:{submit:e.multiSevModel,addComponent:e.addComponent,close:e.closeModel}})],1)},i=[],s=(a("ac6a"),a("75fc")),d=a("7647"),l=a("fdb9"),o={name:"KioskManager",components:{funcDevDialog:l["default"]},data:function(){return{counter:[{is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""}],kioskInfo:{},value:"100",radio:"1",searchKey:{id:"",funcType:"",funcTypeList:[],status:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}]},searchData:[],page:{currentPage:1,pageSize:20,totalRow:0,totalPage:0},singleModel:{Info:{},flag:!1,is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""},fun_ids:[],mod_ids:[],multiModel:{flag:!1,is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""}}},mounted:function(){var e=window.decodeURIComponent(window.atob(this.$route.query.item));e=JSON.parse(e),this.kioskInfo=e,this.onsearch()},methods:{closeModel:function(e){e.flag=!1,this.counter=[{is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""}]},onsearch:function(){this.getFuncList()},getFuncList:function(){var e=this,t={};t.id=e.searchKey.id,t.dev_id={id:e.kioskInfo.id},Object(d["j"])(t).then(function(t){e.searchData=t.data;var a=[{id:"",code:"",name:"请选择"}],n=[].concat(a,Object(s["a"])(t.data));e.searchKey.funcTypeList=n}).catch(function(t){e.$message({type:"warning",message:t})})},addComponent:function(e){this.counter.push({is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""})},clear:function(){this.singleModel={Info:{},flag:!1,is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""}},clearAll:function(){this.multiModel={fun_ids:[],flag:!1,is_disabled:"",checkDate:"",weekday:[],weeks:[{id:1,val:"0",name:"周一"},{id:2,val:"0",name:"周二"},{id:3,val:"0",name:"周三"},{id:4,val:"0",name:"周四"},{id:5,val:"0",name:"周五"},{id:6,val:"0",name:"周六"},{id:7,val:"0",name:"周日"},{id:8,val:"0",name:"法定假日"}],statusId:"",statusList:[{id:"",name:"请选择"},{id:1,name:"隐藏"},{id:2,name:"置灰"},{id:3,name:"弹框显示"}],startTime:"",endTime:"",msg:""}},singleWeek:function(e){var t=e,a=["0","0","0","0","0","0","0","0"],n=[];return t.map(function(e,t){n.push(e.id)}),n.forEach(function(e){a.splice(e-1,1,"1")}),a=a.join(""),this.singleModel.checkDate=a,this.singleModel.checkDate},single:function(e){this.singleModel.flag=!0,this.singleModel.Info=e},singelSevModel:function(){var e=this,t=this,a={},n=[];this.counter.forEach(function(a){n.push({dev:[{id:t.kioskInfo.id}],mod_list:[{id:t.singleModel.Info.id}],is_disabled:a.is_disabled,service_date:e.singleWeek(a.weekday),service_start_time:a.startTime,service_end_time:a.endTime,disabled_type:a.statusId,msg:a.msg})}),a.dev_list=n,Object(d["x"])(a).then(function(e){"0"===e.code&&(t.$message({type:"success",message:e.msg}),t.clear())}).catch(function(e){t.$message({type:"warning",message:e})})},multiWeek:function(e){var t=this.multiModel.weekday,a=["0","0","0","0","0","0","0","0"],n=[];t.map(function(e,t){n.push(e.id)}),n.forEach(function(e){a.splice(e-1,1,"1")}),a=a.join(""),this.multiModel.checkDate=a},multiChange:function(){var e=this.fun_ids;if(0===e.length)this.$message({type:"warning",message:"请选择要设置的功能"});else{var t=[];e.forEach(function(e,a){t.push(Object.assign({},{id:e}))}),this.mod_ids=t,this.multiModel.flag=!0}},multi:function(e){var t=e;this.fun_ids=t},multiSevModel:function(){var e=this,t=this,a={},n=[];this.counter.forEach(function(a){n.push({dev:[{id:t.kioskInfo.id}],mod_list:t.mod_ids,is_disabled:a.is_disabled,service_date:e.singleWeek(a.weekday),service_start_time:a.startTime,service_end_time:a.endTime,disabled_type:a.statusId,msg:a.msg})}),a.dev_list=n,Object(d["x"])(a).then(function(e){"0"===e.code&&(t.$message({type:"success",message:e.msg}),t.clearAll())}).catch(function(e){t.$message({type:"warning",message:e})})}}},r=o,c=(a("403f"),a("0c7c")),u=Object(c["a"])(r,n,i,!1,null,"589331ff",null);t["default"]=u.exports},af41:function(e,t,a){},c6d6:function(e,t){e.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAALCAYAAABLcGxfAAABEElEQVQoU73RsS8DYRgG8Of5cu6u57tb7f6CbtaODAZDMUpI2DrYpUJiMFQkTXSgKxWJ2NxCDAaJSDdDY5CKkEjInfiu9F5holerd3yS3/A8L6d3OiMpZRS910WzMeccjtfEy1kmJFVegAqn6qYEwYKQu79MF823rh3mrOScQBgnZm3QddvfQFIUGrPuxE9QrD5q5QYXJA9eYnvV18k+wLgvKFZFq1xyLeBWFNvrvjbHFNV+GLZnMqBwItbQjbkXciWKnZqvzSnBq9a7U7qcx0cGfJX0BpJXiMoDaR3EWaqdRRV3lgQSZcDY5lMQaK8l4B0hR3u37jLKTCe3TQWKz3+W7l35nwEEG5lP9wmELH8C02yfc+JFoOYAAAAASUVORK5CYII="},d4bc:function(e,t,a){},f4cd:function(e,t,a){"use strict";var n=a("af41"),i=a.n(n);i.a},fdb9:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"kiosksetting appMainWarp"},[a("el-dialog",{attrs:{title:"操作",visible:e.modelFlag.flag,width:"40%",center:""},on:{"update:visible":function(t){return e.$set(e.modelFlag,"flag",t)},close:function(t){return e.modelFun()}}},[e._l(e.counter,function(t,n){return a("div",{key:n},[a("el-form",{attrs:{"label-width":"150px",center:""}},[a("el-form-item",[[a("el-radio-group",{model:{value:t.is_disabled,callback:function(a){e.$set(t,"is_disabled",a)},expression:"modelData.is_disabled"}},[a("el-radio",{attrs:{label:0}},[e._v("启用功能")]),e._v(" "),a("el-radio",{attrs:{label:1}},[e._v("禁用功能")])],1),e._v(" "),e.counter.length>1?a("el-button",{staticStyle:{"margin-left":"10px"},attrs:{size:"mini",type:"danger"},on:{click:function(t){return e.deleteComponent(n)}}},[e._v("删 除")]):e._e()]],2),e._v(" "),a("el-form-item",[a("el-checkbox-group",{staticClass:"checkGroup",model:{value:t.weekday,callback:function(a){e.$set(t,"weekday",a)},expression:"modelData.weekday"}},e._l(t.weeks,function(t,n){return a("el-checkbox",{key:n,attrs:{label:t}},[e._v(e._s(t.name))])}),1)],1),e._v(" "),a("el-form-item",{attrs:{label:"开始使用时间:"}},[a("el-time-picker",{attrs:{"value-format":"HH:mm:ss",type:"time",placeholder:"选择时间"},model:{value:t.startTime,callback:function(a){e.$set(t,"startTime",a)},expression:"modelData.startTime"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"结束使用时间:"}},[a("el-time-picker",{attrs:{"value-format":"HH:mm:ss",type:"time",placeholder:"选择时间"},model:{value:t.endTime,callback:function(a){e.$set(t,"endTime",a)},expression:"modelData.endTime"}})],1),e._v(" "),a("el-form-item",{attrs:{label:"不可用提示状态:"}},[a("el-select",{attrs:{filterable:"",placeholder:"请选择"},model:{value:t.statusId,callback:function(a){e.$set(t,"statusId",a)},expression:"modelData.statusId"}},e._l(t.statusList,function(e){return a("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})}),1)],1),e._v(" "),a("el-form-item",{attrs:{label:"不可用弹窗提示语:"}},[a("el-input",{attrs:{type:"textarea"},model:{value:t.msg,callback:function(a){e.$set(t,"msg",a)},expression:"modelData.msg"}})],1)],1)],1)}),e._v(" "),a("div",{staticClass:"dialog-footer",attrs:{slot:"footer"},slot:"footer"},[a("el-button",{on:{click:e.modelFun}},[e._v("取 消")]),e._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:e.submitChildren}},[e._v("确 定")]),e._v(" "),a("el-button",{attrs:{type:"primary"},on:{click:function(t){return e.addComponent()}}},[e._v("增 加")])],1)],2)],1)},i=[],s={name:"Kiosksetting",props:{modelFlag:{type:Object,required:!0},counter:{type:Array,required:!0},title:{type:String,default:""}},data:function(){return{show:!1}},mounted:function(){},methods:{deleteComponent:function(e){0!==e&&this.counter.splice(e,1)},modelFun:function(){this.$emit("close",this.modelFlag)},submitChildren:function(){this.$emit("submit")},addComponent:function(){this.$emit("addComponent")}}},d=s,l=(a("f4cd"),a("0c7c")),o=Object(l["a"])(d,n,i,!1,null,"0f750d4c",null);t["default"]=o.exports}}]);
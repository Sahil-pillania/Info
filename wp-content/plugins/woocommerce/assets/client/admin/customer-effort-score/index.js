!function(){var e={31772:function(e,o,t){"use strict";var r=t(25148);function c(){}function n(){}n.resetWarningCache=c,e.exports=function(){function e(e,o,t,c,n,a){if(a!==r){var l=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw l.name="Invariant Violation",l}}function o(){return e}e.isRequired=e;var t={array:e,bigint:e,bool:e,func:e,number:e,object:e,string:e,symbol:e,any:e,arrayOf:o,element:e,elementType:e,instanceOf:o,node:e,objectOf:o,oneOf:o,oneOfType:o,shape:o,exact:o,checkPropTypes:n,resetWarningCache:c};return t.PropTypes=t,t}},7862:function(e,o,t){e.exports=t(31772)()},25148:function(e){"use strict";e.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"}},o={};function t(r){var c=o[r];if(void 0!==c)return c.exports;var n=o[r]={exports:{}};return e[r](n,n.exports,t),n.exports}t.n=function(e){var o=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(o,{a:o}),o},t.d=function(e,o){for(var r in o)t.o(o,r)&&!t.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:o[r]})},t.o=function(e,o){return Object.prototype.hasOwnProperty.call(e,o)},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var r={};!function(){"use strict";t.r(r),t.d(r,{CustomerEffortScore:function(){return p},default:function(){return d}});var e=window.wp.element,o=t(7862),c=t.n(o),n=window.wp.i18n,a=window.wp.compose,l=window.wp.data,i=window.wp.components,s=window.wc.experimental;function u(o){let{recordScoreCallback:t,label:r}=o;const c=[{label:(0,n.__)("Very difficult","woocommerce"),value:"1"},{label:(0,n.__)("Somewhat difficult","woocommerce"),value:"2"},{label:(0,n.__)("Neutral","woocommerce"),value:"3"},{label:(0,n.__)("Somewhat easy","woocommerce"),value:"4"},{label:(0,n.__)("Very easy","woocommerce"),value:"5"}],[a,l]=(0,e.useState)(NaN),[u,m]=(0,e.useState)(""),[f,p]=(0,e.useState)(!1),[d,w]=(0,e.useState)(!0),b=()=>w(!1);return d?(0,e.createElement)(i.Modal,{className:"woocommerce-customer-effort-score",title:(0,n.__)("Please share your feedback","woocommerce"),onRequestClose:b,shouldCloseOnClickOutside:!1},(0,e.createElement)(s.Text,{variant:"subtitle.small",as:"p",weight:"600",size:"14",lineHeight:"20px"},r),(0,e.createElement)("div",{className:"woocommerce-customer-effort-score__selection"},(0,e.createElement)(i.RadioControl,{selected:a.toString(10),options:c,onChange:e=>{const o=parseInt(e,10);l(o),p(!Number.isInteger(o))}})),(1===a||2===a)&&(0,e.createElement)("div",{className:"woocommerce-customer-effort-score__comments"},(0,e.createElement)(i.TextareaControl,{label:(0,n.__)("Comments (Optional)","woocommerce"),help:(0,n.__)("Your feedback will go to the WooCommerce development team","woocommerce"),value:u,onChange:e=>m(e),rows:5})),f&&(0,e.createElement)("div",{className:"woocommerce-customer-effort-score__errors",role:"alert"},(0,e.createElement)(s.Text,{variant:"body",as:"p"},(0,n.__)("Please provide feedback by selecting an option above.","woocommerce"))),(0,e.createElement)("div",{className:"woocommerce-customer-effort-score__buttons"},(0,e.createElement)(i.Button,{isTertiary:!0,onClick:b,name:"cancel"},(0,n.__)("Cancel","woocommerce")),(0,e.createElement)(i.Button,{isPrimary:!0,onClick:()=>{Number.isInteger(a)?(w(!1),t(a,u)):p(!0)},name:"send"},(0,n.__)("Send","woocommerce")))):null}u.propTypes={recordScoreCallback:c().func.isRequired,label:c().string.isRequired};var m=u;const f=()=>{};function p(o){let{recordScoreCallback:t,label:r,createNotice:c,onNoticeShownCallback:a=f,onNoticeDismissedCallback:l=f,onModalShownCallback:i=f,icon:s}=o;const[u,p]=(0,e.useState)(!0),[d,w]=(0,e.useState)(!1);return(0,e.useEffect)((()=>{u&&(c("success",r,{actions:[{label:(0,n.__)("Give feedback","woocommerce"),onClick:()=>{w(!0),i()}}],icon:s,explicitDismiss:!0,onDismiss:l}),p(!1),a())}),[u]),u?null:d?(0,e.createElement)(m,{label:r,recordScoreCallback:t}):null}p.propTypes={recordScoreCallback:c().func.isRequired,label:c().string.isRequired,createNotice:c().func.isRequired,onNoticeShownCallback:c().func,onNoticeDismissedCallback:c().func,onModalShownCallback:c().func,icon:c().element};var d=(0,a.compose)((0,l.withDispatch)((e=>{const{createNotice:o}=e("core/notices2");return{createNotice:o}})))(p)}(),(window.wc=window.wc||{}).customerEffortScore=r}();
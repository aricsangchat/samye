!function(e){function t(a){if(n[a])return n[a].exports;var r=n[a]={i:a,l:!1,exports:{}};return e[a].call(r.exports,r,r.exports,t),r.l=!0,r.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,a){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:a})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t){var n=wp.i18n,a=n.__,r=n.setLocaleData,l=wp.blocks.registerBlockType,o=wp.editor,c=o.RichText,s=o.AlignmentToolbar,u=o.BlockControls;r(window.gutenberg_examples_04_esnext.localeData,"gutenberg-examples"),l("gutenberg-examples/example-04-controls-esnext",{title:a("Example: Controls (esnext)","gutenberg-examples"),icon:"universal-access-alt",category:"layout",attributes:{content:{type:"array",source:"children",selector:"p"},alignment:{type:"string",default:"none"}},edit:function(e){var t=e.attributes,n=t.content,a=t.alignment,r=e.className,l=function(t){e.setAttributes({content:t})},o=function(t){e.setAttributes({alignment:void 0===t?"none":t})};return wp.element.createElement("div",null,wp.element.createElement(u,null,wp.element.createElement(s,{value:a,onChange:o})),wp.element.createElement(c,{className:r,style:{textAlign:a},tagName:"p",onChange:l,value:n}))},save:function(e){return wp.element.createElement(c.Content,{className:"gutenberg-examples-align-"+e.attributes.alignment,tagName:"p",value:e.attributes.content})}})}]);
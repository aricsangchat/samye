!function(e){function t(l){if(n[l])return n[l].exports;var c=n[l]={i:l,l:!1,exports:{}};return e[l].call(c.exports,c,c.exports,t),c.l=!0,c.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,l){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:l})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});n(1),n(4)},function(e,t,n){"use strict";var l=n(2),c=(n.n(l),n(3)),r=(n.n(c),wp.i18n.__);(0,wp.blocks.registerBlockType)("cgb/block-my-block",{title:r("my-block - CGB Block"),icon:"shield",category:"common",keywords:[r("my-block \u2014 CGB Block"),r("CGB Example"),r("create-guten-block")],edit:function(e){return wp.element.createElement("div",{className:e.className},wp.element.createElement("p",null,"\u2014 Hello from the backend."),wp.element.createElement("p",null,"CGB BLOCK: ",wp.element.createElement("code",null,"my-block")," is a new Gutenberg block"),wp.element.createElement("p",null,"It was created via"," ",wp.element.createElement("code",null,wp.element.createElement("a",{href:"https://github.com/ahmadawais/create-guten-block"},"create-guten-block")),"."))},save:function(e){return wp.element.createElement("div",null,wp.element.createElement("p",null,"\u2014 Hello from the frontend."),wp.element.createElement("p",null,"CGB BLOCK: ",wp.element.createElement("code",null,"my-block")," is a new Gutenberg block."),wp.element.createElement("p",null,"It was created via"," ",wp.element.createElement("code",null,wp.element.createElement("a",{href:"https://github.com/ahmadawais/create-guten-block"},"create-guten-block")),"."))}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";var l=n(5),c=(n.n(l),n(6)),r=(n.n(c),wp.i18n.__),o=wp.blocks.registerBlockType,a=wp.editor.RichText,m=wp.components.Spinner,u=wp.data.withSelect;o("cgb/grd-home-block",{title:r("GRD HOME BLOCK"),description:r("Most recent GRD Posts"),icon:"image-filter",category:"common",keywords:[r("grd-home-block")],edit:u(function(e){return{posts:e("core").getEntityRecords("postType","post",{per_page:3})}})(function(e){var t=e.posts,n=e.className,l=e.attributes,c=e.setAttributes;return console.log(l),t?0===t.length?wp.element.createElement("p",null,r("No Posts")):wp.element.createElement("div",{className:n},wp.element.createElement(a,{tagName:"h2",value:l.content,onChange:function(e){return c({content:e})},placeholder:r("Add Title")}),wp.element.createElement(a,{tagName:"p",value:l.description,onChange:function(e){return c({description:e})},placeholder:r("Add Title")}),wp.element.createElement("ul",null,t.map(function(e){return wp.element.createElement("li",null,wp.element.createElement("a",{className:n,href:e.link},e.title.rendered))}))):wp.element.createElement("p",{className:n},wp.element.createElement(m,null),r("Loading Posts"))}),save:function(){return null}})},function(e,t){},function(e,t){}]);
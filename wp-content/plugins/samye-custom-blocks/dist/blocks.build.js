!function(e){function t(l){if(n[l])return n[l].exports;var o=n[l]={i:l,l:!1,exports:{}};return e[l].call(o.exports,o,o.exports,t),o.l=!0,o.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,l){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:l})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});n(1),n(4),n(7)},function(e,t,n){"use strict";var l=n(2),o=(n.n(l),n(3)),c=(n.n(o),wp.i18n.__);(0,wp.blocks.registerBlockType)("cgb/block-my-block",{title:c("my-block - CGB Block"),icon:"shield",category:"common",keywords:[c("my-block \u2014 CGB Block"),c("CGB Example"),c("create-guten-block")],edit:function(e){return wp.element.createElement("div",{className:e.className},wp.element.createElement("p",null,"\u2014 Hello from the backend."),wp.element.createElement("p",null,"CGB BLOCK: ",wp.element.createElement("code",null,"my-block")," is a new Gutenberg block"),wp.element.createElement("p",null,"It was created via"," ",wp.element.createElement("code",null,wp.element.createElement("a",{href:"https://github.com/ahmadawais/create-guten-block"},"create-guten-block")),"."))},save:function(e){return wp.element.createElement("div",null,wp.element.createElement("p",null,"\u2014 Hello from the frontend."),wp.element.createElement("p",null,"CGB BLOCK: ",wp.element.createElement("code",null,"my-block")," is a new Gutenberg block."),wp.element.createElement("p",null,"It was created via"," ",wp.element.createElement("code",null,wp.element.createElement("a",{href:"https://github.com/ahmadawais/create-guten-block"},"create-guten-block")),"."))}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";var l=n(5),o=(n.n(l),n(6)),c=(n.n(o),wp.i18n.__),r=wp.blocks.registerBlockType,a=wp.editor.RichText,i=wp.components.Spinner,u=wp.data.withSelect;r("cgb/grd-home-block",{title:c("GRD HOME BLOCK"),description:c("Most recent GRD Posts"),icon:"image-filter",category:"common",keywords:[c("grd-home-block")],edit:u(function(e){return{posts:e("core").getEntityRecords("postType","grd-teaching",{per_page:3})}})(function(e){var t=e.posts,n=e.className,l=e.attributes,o=e.setAttributes;if(l.className="haha",console.log(l),!t)return wp.element.createElement("p",{className:n},wp.element.createElement(i,null),c("Loading Posts"));if(0===t.length)return wp.element.createElement("p",null,c("No Posts"));var r=function(e){o({description:e})};return wp.element.createElement("div",{className:n},wp.element.createElement(a,{tagName:"h2",value:l.content,onChange:function(e){return o({content:e})},placeholder:c("Add Title")}),wp.element.createElement(a,{tagName:"p",value:l.description,onChange:r,placeholder:c("Add Description")}),wp.element.createElement("ul",null,t.map(function(e){return wp.element.createElement("li",null,wp.element.createElement("a",{className:n,href:e.link},e.title.rendered))})))}),save:function(){return null}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";var l=n(8),o=(n.n(l),n(9)),c=(n.n(o),n(10)),r=(n.n(c),wp.i18n.__),a=wp.blocks.registerBlockType,i=wp.editor.RichText,u=(wp.components.Spinner,wp.data.withSelect);a("cgb/featured-post-block",{title:r("Featured Post Block"),description:r("Feature posts"),icon:"image-filter",category:"common",keywords:[r("featured-post-block")],edit:u(function(e){return console.log(e),{posts:e("core").getEntityRecords("postType","grd-teaching",{per_page:3})}})(function(e){var t=(e.posts,e.className),n=e.attributes,l=e.setAttributes;n.className="haha",console.log(n);var o=function(e){l({description:e})},a=function(e){l({dropdown:e})};return wp.element.createElement("div",{className:t},wp.element.createElement(c.DropdownMenu,{icon:"move",label:"Select a direction",controls:[{title:"Up",icon:"arrow-up-alt",onClick:function(){return a("up")}},{title:"Right",icon:"arrow-right-alt",onClick:function(){return a("down")}}]}),wp.element.createElement(i,{tagName:"h2",value:n.content,onChange:function(e){return l({content:e})},placeholder:r("Add Title")}),wp.element.createElement(i,{tagName:"p",value:n.description,onChange:o,placeholder:r("Add Description")}))}),save:function(){return null}})},function(e,t){},function(e,t){},function(e,t){e.exports=wp.components}]);
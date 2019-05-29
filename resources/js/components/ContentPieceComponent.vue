<template>
    <div @mouseover="showPieceButtons = true" @mouseleave="showPieceButtons = false" class="piece-wrapper">
        <h3 class="content-piece-title">{{contentPiece.title}}</h3>
        <div class="piece-body">
            <p>{{contentPiece.body}}</p>
        </div>
        <pre v-if="contentPiece.code" class="piece-code-body">
            <code ref="codeBody" class="ltr microlight">{{contentPiece.code}}</code>
        </pre>
        <div v-if="!contentPiece.imageIsUploaded && contentPiece.imageBase64" class="image-wrapper">
            <img :src="contentPiece.imageBase64" class="piece-image" alt="from base64"/>
        </div>
        <!--<p >ax nadare</p>-->
        <div class="image-wrapper" v-else-if="contentPiece.imageUrl">
            <img :src="imageUrl(contentPiece.imageUrl)" alt="from url">
        </div>
        <!-- ImportantComment <p>location on page : {{contentPiece.locationOnPage}}</p>-->

        <div :class="{show :showPieceButtons,hidden:!showPieceButtons}">
            <button @click="removePiece(contentPiece)" class="icon-button-small-red">
                <i class="demo-icon icon-trash-1"></i>
            </button>

            <!--<span>بعد از این تکه اضافه کن </span>-->
            <button @click="showAddTextModal(contentPiece.locationOnPage)" class="icon-button-small">
                <!--متن &nbsp;-->
                <i class="demo-icon icon-doc-text-inv"></i>
            </button>
            <!--<button @click="showAddAudioModal(0)">Add Audio</button>-->

            <!--<button @click="showAddImageModal(0)" class="icon-button">-->
            <!--صدا-->
            <!--<i class="demo-icon icon-picture-2"></i>-->
            <!--</button>-->
            <button @click="showAddImageModal(contentPiece.locationOnPage)" class="icon-button-small">
                <!--عکس &nbsp;-->
                <i class="demo-icon icon-picture-2"></i>
            </button>
            <button @click="showAddCodeModal(contentPiece.locationOnPage)" class="icon-button-small">
                <!--کد&nbsp;-->
                <i class="demo-icon icon-code-3"></i>
            </button>
        </div>
        <!--<hr>-->
    </div>
</template>
<script>
    import EventManager from "../EventManager";
    // import prism from 'prismjs'
    import hljs from 'highlight.js/lib/highlight';
    import 'highlight.js/styles/xcode.css';
    import vex from 'vex-js'

    import apache from 'highlight.js/lib/languages/apache';
    import bash from 'highlight.js/lib/languages/bash';
    import coffeescript from 'highlight.js/lib/languages/coffeescript';
    import cpp from 'highlight.js/lib/languages/cpp';
    import csp from 'highlight.js/lib/languages/csp';
    import css from 'highlight.js/lib/languages/css';
    import http from 'highlight.js/lib/languages/http';
    import ini from 'highlight.js/lib/languages/ini';
    import javascript from 'highlight.js/lib/languages/javascript';
    import json from 'highlight.js/lib/languages/json';
    import java from 'highlight.js/lib/languages/java';
    import makefile from 'highlight.js/lib/languages/makefile';
    import markdown from 'highlight.js/lib/languages/markdown';
    import nginx from 'highlight.js/lib/languages/nginx';
    import php from 'highlight.js/lib/languages/php';
    import python from 'highlight.js/lib/languages/python';
    import django from 'highlight.js/lib/languages/django';
    import ruby from 'highlight.js/lib/languages/ruby';
    import shell from 'highlight.js/lib/languages/shell';
    import powershell from 'highlight.js/lib/languages/powershell';
    import sql from 'highlight.js/lib/languages/sql';
    import htmlbars from 'highlight.js/lib/languages/htmlbars';

    hljs.registerLanguage('apache', apache);
    hljs.registerLanguage('bash', bash);
    hljs.registerLanguage('coffeescript', coffeescript);
    hljs.registerLanguage('cpp', cpp);
    hljs.registerLanguage('csp', csp);
    hljs.registerLanguage('css', css);
    hljs.registerLanguage('http', http);
    hljs.registerLanguage('ini', ini);
    hljs.registerLanguage('javascript', javascript);
    hljs.registerLanguage('java', java);
    hljs.registerLanguage('json', json);
    hljs.registerLanguage('makefile', makefile);
    hljs.registerLanguage('markdown', markdown);
    hljs.registerLanguage('nginx', nginx);
    hljs.registerLanguage('php', php);
    hljs.registerLanguage('python', python);
    hljs.registerLanguage('django', django);
    hljs.registerLanguage('ruby', ruby);
    hljs.registerLanguage('shell', shell);
    hljs.registerLanguage('powershell', powershell);
    hljs.registerLanguage('sql', sql);
    hljs.registerLanguage('htmlbars', htmlbars);

    hljs.initHighlightingOnLoad();

    export default {
        name: "ContentPieceComponent",
        props: ['contentPiece'],
        data() {
            return {
                showPieceButtons: false,
            }
        },
        computed: {},
        mounted() {
            if (typeof this.contentPiece.code === 'string') {
                if (this.contentPiece.code.length !== 0) {
                    hljs.highlightBlock(this.$refs.codeBody);
                }
            }
        },
        methods: {
            imageUrl(imageUrl) {
                return '/public' + imageUrl
            },
            showAddTextModal(locationOnPage) {
                EventManager.fire('showAddTextModal', locationOnPage);
            },
            showAddAudioModal(locationOnPage) {
                EventManager.fire('showAddAudioModal', locationOnPage);
            },
            showAddImageModal(locationOnPage) {
                EventManager.fire('showAddImageModal', locationOnPage);
            },
            showAddCodeModal(locationOnPage) {
                EventManager.fire('showAddCodeModal', locationOnPage);
            },
            removePiece(piece) {
                vex.dialog.confirm({
                    message: 'این تکه پاک شه؟',
                    callback: value => {
                        // console.log(value)
                        if (value) {
                            this.$store.dispatch('topics/removePiece', piece)
                        }
                    }
                })

            }
        }
    }
</script>


<style scoped>

    .piece-wrapper {
        display: flex;
        flex-flow: column;
        /*justify-content: center;*/
        /*align-items: center;*/
    }

    .hljs {
        line-height: 1.9rem;
        font-family: "Operator Mono Light";
    }

    .show {
        visibility: visible;
    }

    .hidden {
        visibility: hidden;
    }

    .content-piece-title {
        font-family: B Mitra;
        font-size: 1.3rem;
        margin: .1rem .5rem;
        color: #292929;
    }

    .piece-body {
        margin: 1rem .5rem .3rem .5rem;
        padding: 0 .5rem;
        font-family: "B Mitra";
        line-height: 1.5rem;
        font-size: 1.1rem;
        color: #292929;
        /*background: #0e301a;*/
    }

    .piece-code-body {
        text-align: left;
        direction: ltr;
        margin: .1rem .5rem;
        padding: .5rem;
        font-size: .9rem;
        font-weight: normal;
        font-family: monospace;
        /*background: #c7bec0;*/
    }

    .image-wrapper {
        margin: .5rem;
        padding: .5rem;
        align-self: center;
        width: 20em;
    }

    .image-wrapper img {
        width: 100%;
    }
</style>

<!--<script>-->
<!--[{-->
<!--"body": "aaa",-->
<!--"code": "aaa",-->
<!--"title": "aaa",-->
<!--"imageUrl": "/uploads/Q9DLgnKC0d/u26jExObdh/Penguins.jpg",-->
<!--"locationOnPage": 1,-->
<!--"imageIsUploaded": true-->
<!--}, {-->
<!--"body": "sss",-->
<!--"code": "sss",-->
<!--"title": "sss",-->
<!--"imageUrl": "/uploads/Q9DLgnKC0d/u26jExObdh/Tulips.jpg",-->
<!--"locationOnPage": 3,-->
<!--"imageIsUploaded": true-->
<!--}, {-->
<!--"body": "q",-->
<!--"code": "q",-->
<!--"title": "qq",-->
<!--"imageUrl": "/uploads/Q9DLgnKC0d/u26jExObdh/Jellyfish.jpg",-->
<!--"locationOnPage": 4,-->
<!--"imageIsUploaded": true-->
<!--}, {-->
<!--"body": "www",-->
<!--"code": "www",-->
<!--"title": "www",-->
<!--"imageUrl": "/uploads/Q9DLgnKC0d/u26jExObdh/Jellyfish.jpg",-->
<!--"locationOnPage": 5,-->
<!--"imageIsUploaded": true-->
<!--}, {"body": "sssssssssss", "title": "ssssssss", "locationOnPage": 2}]-->
<!--</script>-->
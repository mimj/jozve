<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <form action="" id="addPageForm" @submit.prevent="submitForm">
                <!--<h3>اضافه شود در: <span class="latin">{{parentPageTitle()}}</span></h3>-->
                <!--<h3>Topic ID: <span>{{topic_id}}</span></h3>-->
                <!--<h3>Parent Position: <span>{{ParentPosition}}</span></h3>-->
                <div class="input-wrapper">
                    <label for="page-title">
                        عنوان صفحه
                    </label>
                    <input v-model="title" type="text" name="" id="page-title" class="default-input">
                </div>
                <input type="submit" value="افزودن">
            </form>
        </div>
        <button @click="closeModal" class="modal-close is-large" aria-label="close">
            <i class="demo-icon icon-cancel"></i>
        </button>
    </div>

</template>

<script>
    import EventManager from '../EventManager'
    import {mapActions} from 'vuex'
    import toastr from 'toastr'

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    export default {
        name: "AddPageModalComponent",
        props: {
            // title: '',
            parent_id: 0,
            topic_id: null,
            prevPageId: null,
            // ParentPosition: null,
        },
        data() {
            return {
                title: '',
                // title:this.$props.title
            }
        },
        mounted() {
            // console.log("topic_id", this.topic_id);
            // console.log("position", this.position)
        },
        methods: {
            // ...mapActions('topics', ['addToTopics']),

            parentPageTitle() {
                //TODO: this is just default. for sub-pages Should be parent page title
                return this.$route.params.topic;
            },

            closeModal() {
                console.log("ok");
                EventManager.fire('hide');
            },
            submitForm() {
                let previousPageId = this.prevPageId;
                let parentPageId = this.parent_id;
                // if (previousPagePosition === -1) {
                //     previousPagePosition = 0;
                // }
                // if (parentPageId === -1) {
                //     parentPageId = 0;
                // }

                this.$store.dispatch('topics/addPageTitleToTopic',
                    Object.assign({}, {
                        'title': this.title,
                        prevPageId: previousPageId,
                        parent_id: parentPageId,
                        // 'topic_id': this.$route.params.topicId
                    }))
                    .then((res) => {
                        console.log('this.$store.state.topics.currentTopic', this.$store.state.topics.currentTopic);
                        toastr.success("صفحه اضافه شد");
                        EventManager.fire('hide');
                        EventManager.fire('updateSubPageTitles', {parent_id: this.parent_id, subPageTitles: res.pages});
                        if (this.parent_id == null) {
                            // debugger
                            EventManager.fire('forceUpdate', {pages: res.pages})
                        }
                    }).catch((e) => {
                    console.log('error', e);
                    toastr.error("مشکلی پیش اومد")
                })
            }
        }
    }
</script>

<style scoped>
    #addPageForm {
        background: #fcf2fe;
    }

    /*.modal {*/
    /*position: fixed;*/
    /*!*background: rgba(100, 100, 100, .3);*!*/
    /*top: 0;*/
    /*left: 0;*/
    /*bottom: 0;*/
    /*right: 0;*/
    /*!*z-index: 100;*!*/
    /*display: flex;*/
    /*justify-content: center;*/
    /*align-items: center;*/
    /*}*/

    /*.modal-background {*/
    /*background-color: rgba(48, 48, 48, 0.93);*/
    /*position: absolute;*/
    /*top: 0;*/
    /*left: 0;*/
    /*bottom: 0;*/
    /*right: 0;*/
    /*}*/

    /*.modal-content {*/
    /*width: 400px;*/
    /*height: 200px;*/
    /*position: relative;*/
    /*border-radius: 5px;*/
    /*}*/

    /*.modal-content form {*/
    /*height: 100%;*/
    /*display: flex;*/
    /*flex-flow: column;*/
    /*border-radius: 10px;*/
    /*overflow: visible;*/
    /*}*/

    /*.modal-content form input[type='submit'] {*/
    /*border-radius: 999rem;*/
    /*border: none;*/
    /*background: #5640c7;*/
    /*color: white;*/
    /*margin: 1rem;*/
    /*padding: .5rem;*/
    /*font-family: B_Roya;*/
    /*font-weight: bolder;*/
    /*}*/

    /*.input-wrapper {*/
    /*display: flex;*/
    /*flex-flow: row;*/
    /*margin: 2rem 1rem 1rem 1rem;*/
    /*justify-content: center;*/
    /*align-items: center;*/
    /*}*/

    /*.default-input {*/
    /*!*width: 90%;*!*/
    /*flex-grow: 1;*/
    /*padding: 10px;*/
    /*border: 2px solid #9fa0a2;*/
    /*margin: .5rem 1rem;*/
    /*background-color: transparent !important;*/
    /*color: #636168;*/
    /*border-radius: 999px;*/
    /*font-family: "B_Roya";*/
    /*font-weight: bold;*/
    /*-webkit-box-shadow: 0px 0px 27px -7px rgba(0, 0, 0, 0.75);*/
    /*-moz-box-shadow: 0px 0px 27px -7px rgba(0, 0, 0, 0.75);*/
    /*box-shadow: 0px 0px 27px -7px rgba(0, 0, 0, 0.75);*/
    /*outline: none*/
    /*}*/

    /*.modal-close {*/
    /*position: fixed;*/
    /*top: 0;*/
    /*right: 0;*/
    /*margin: 30px;*/
    /*border: none;*/
    /*background: transparent;*/
    /*cursor: pointer;*/
    /*font-size: 20px;*/
    /*padding: 5px;*/
    /*color: white;*/
    /*}*/

    /*.modal-close:hover {*/
    /*background: rgba(18, 18, 18, 0.93);*/
    /*border-radius: 50%;*/
    /*}*/
</style>
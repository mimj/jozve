<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <form action="" id="addTopicForm" @submit.prevent="submitForm">
                <div class="input-wrapper">
                    <label for="topic-title">
                        عنوان موضوع
                    </label>
                    <input v-model="title" type="text" name="" id="topic-title" class="default-input" autofocus>
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

    export default {
        name: "AddTopicComponent",
        data() {
            return {
                title: ''
            }
        },
        methods: {
            // ...mapActions('topics', ['addToTopics']),
            closeModal() {
                EventManager.fire('hide');
            },
            submitForm() {
                // this.addToTopics(Object.assign({}, {'title': this.title}))
                this.$store.dispatch('topics/addToTopics', Object.assign({}, {'title': this.title}))
                    .then(() => {
                        this.closeModal()
                    })
            }
        }
    }
</script>

<style scoped>
    #addTopicForm {
        background: #efefef;
    }


</style>
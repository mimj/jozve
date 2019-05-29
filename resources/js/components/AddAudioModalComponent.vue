<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <form action="" id="addTopicForm" @submit.prevent="submitForm">
                <label for="topic-title">
                    Topic Title
                    <input v-model="title" type="text" name="" id="topic-title">
                </label>
                <input type="submit" value="add topic">
            </form>
        </div>
        <button @click="closeModal" class="modal-close is-large" aria-label="close"></button>
    </div>
</template>

<script>
    import EventManager from "../EventManager";

    export default {
        name: "AddAudioModalComponent",
        data(){
            return{
                title:null
            }
        },
        methods:{
            closeModal() {
                EventManager.fire('hide');
            },
        }
    }
</script>

<style scoped>

</style>
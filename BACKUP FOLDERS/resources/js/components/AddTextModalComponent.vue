<template>
    <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-content">
            <form action="" class="addPieceForm" @submit.prevent="submitForm">
                <div class="input-wrapper">
                    <label for="text-title">
                        عنوان
                    </label>
                    <input v-model="title" type="text" name="" id="text-title" class="default-input">
                </div>
                <div class="input-wrapper">
                    <label for="text-body">
                        متن:
                    </label>
                    <textarea v-model="body" name="" id="text-body" class="default-textarea"></textarea>
                </div>
                <div class="input-wrapper">
                    <label for="text-position">
                        text position on page
                    </label>
                    <input :value="locationOfPieceToBeAdded" type="text" name="" id="text-position" class="default-input">
                </div>
                <br>
                <input type="submit" value="اضافه کن">
            </form>
        </div>
        <button @click="closeModal" class="modal-close is-large" aria-label="close">
            <i class="demo-icon icon-cancel"></i>
        </button>
    </div>
</template>

<script>
    import EventManager from "../EventManager";

    export default {
        name: "AddTextModalComponent",
        props: {
            locationOfPieceToBeAdded: null
        },
        data() {
            return {
                title: null,
                body: null,
                // locationOnPage: 0,
            }
        },
        methods: {

            closeModal() {
                EventManager.fire('hide');
            },
            submitForm() {
                if (!this.body) {
                    alert('برای تکه متن، باید متن رو وارد کنی!');
                    return
                }
                if (this.$store.state.topics.allPiecesLocations.includes(this.locationOfPieceToBeAdded)) {
                    alert('location ghablan set shode')
                }

                this.$store.dispatch('topics/addTextPiece', Object.assign({}, {
                    title: this.title,
                    body: this.body,
                    locationOnPage: this.locationOfPieceToBeAdded,
                }))
                    .then(() => {
                        this.title = null;
                        this.body = null;
                        EventManager.fire('hide');
                        EventManager.fire('updatePage')
                    })
            }
        }
    }
</script>

<style scoped>
</style>
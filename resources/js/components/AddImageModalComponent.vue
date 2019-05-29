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
                    <label for="code-body">
                        کد:
                    </label>
                    <textarea v-model="code" name="" id="code-body" class="default-textarea ltr"></textarea>
                </div>
                <div class="input-wrapper">
                    <label for="image">
                        عکس:
                    </label>
                    <input type="file" v-bind:model="image" ref="imageInput" @change="fileSelected" name=""
                           class="file-input" id="image"/>
                </div>
                <img :src="imageBase64" ref="imagePreview" alt="" width="100">
                <!--<div class="input-wrapper">-->
                    <!--<label for="text-position">-->
                        <!--محل متن در صفحه-->
                    <!--</label>-->
                    <!--<input :value="locationOfPieceToBeAdded" type="text" name="" id="text-position"-->
                           <!--class="default-input">-->
                <!--</div>-->
                <input type="submit" value="اضافه کن">
            </form>
        </div>
        <button @click="closeModal" class="modal-close is-large" aria-label="close">
            <i class="demo-icon icon-cancel"></i>
        </button>
    </div>
</template>

<script>
    import EventManager from '../EventManager'
    import vex from '../Helpers/ModalManager'
    import toastr from 'toastr'
    export default {
        name: "AddImageModalComponent",
        props: {
            locationOfPieceToBeAdded: null
        },
        data() {
            return {
                title: null,
                body: null,
                code: null,
                image: null,
                imageUrl: null,
                imageBase64: null,
                imageIsUploaded: null,
            }
        },
        created() {
            console.log('oi')
        },
        mounted() {
            console.log('currentPage ooo', this.$store.state.topics.currentPage);
            console.log('currentTopic ooo', this.$store.state.topics.currentTopic);

            // console.log('page id', this.$route.params.page)
            //
            // console.log('current Page', currentPage);
        },
        methods: {


            closeModal() {
                EventManager.fire('hide');
            },
            fileSelected(event) {
                let fileNameRepeated = false;
                if (event.target.files && event.target.files[0]) {

                    for (let item in this.$store.state.topics.currentPage.content) {
                        // console.log("item.image.name", item.image.name);
                        // console.log("event.target.files[0].name", event.target.files[0].name);

                        //     TODO: currently it bypasses name of images stored in server,should prevent when image is stored on both state or server
                        // debugger
                        if (this.$store.state.topics.currentPage.content[item].hasOwnProperty('image')) {
                            // if (this.$store.state.topics.currentPage.content[item].image.hasOwnProperty('name')) {
                            if (this.$store.state.topics.currentPage.content[item].image.name == event.target.files[0].name) {
                                alert('esme file ha nabayad yeki bashe '); //TODO: Farsi kon ino
                                fileNameRepeated = true;
                                this.$refs.imageInput.value = "";
                                return
                            }
                            // }
                        } else if (this.$store.state.topics.currentPage.content[item].hasOwnProperty('imageUrl')) {
                            let imageUrl = this.$store.state.topics.currentPage.content[item].imageUrl;
                            let alreadyUploadedImageName = (imageUrl).substring(imageUrl.lastIndexOf('/') + 1);
                            console.log('last part of imageurl',)
                            if (alreadyUploadedImageName == event.target.files[0].name) {
                                alert('esme file ha nabayad yeki bashe '); //TODO: Farsi kon ino
                                fileNameRepeated = true;
                                this.$refs.imageInput.value = "";
                                return
                            }
                        }

                    }

                    // this.$store.state.topics.currentPage.content.forEach(item => {
                    //
                    // });

                    // if (fileNameRepeated) {
                    //     return;
                    // }
                    this.image = event.target.files[0];
                    console.log('temp image: ', event.target.files[0]);
                    // create a new FileReader to read this image and convert to base64 format
                    let reader = new FileReader();
                    // Define a callback function to run, when FileReader finishes its job
                    reader.onload = (e) => {
                        // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                        // Read image as base64 and set to imageData
                        this.imageBase64 = e.target.result;
                    };
                    // Start the reader job - read file as a data url (base64 format)
                    reader.readAsDataURL(event.target.files[0]);
                }
            },
            submitForm() {
                if (!this.image) {
                    toastr.warning("فیلد عکس نباید خالی باشند")
                    return
                }
                // if (this.$store.state.topics.allPiecesLocations.includes(this.locationOfPieceToBeAdded)) {
                //     alert('location ghablan set shode')
                // }


                this.$store.dispatch('topics/addTextPiece', Object.assign({}, {
                    title: this.title,
                    body: this.body,
                    code: this.code,
                    image: this.image,
                    imageUrl: this.makeImageUrl(),
                    imageBase64: this.imageBase64,
                    imageIsUploaded: false,
                    locationOnPage: this.locationOfPieceToBeAdded,
                }))
                    .then(() => {
                        this.title = null;
                        this.body = null;
                        this.code = null;
                        this.image = null;
                        // this.imageUrl = this.makeImageUrl();
                        this.$refs.imageInput.value = '';
                        this.$refs.imagePreview.src = '';
                        this.imageData = null;
                        EventManager.fire('hide');
                        EventManager.fire('updatePage')
                    })
            },
            makeImageUrl() {
                let currentPage = this.$store.state.topics.currentPage;
                let currentTopic = this.$store.state.topics.currentTopic;
                let random = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                let url = '/uploads/' + currentTopic.unique_name + '/' + currentPage.unique_name + '/' + this.image.name;
                console.log('url', url);
                return url;
            },
            makeUniqueName() {

            }
        }
    }
</script>

<style scoped>

</style>
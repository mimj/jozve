<template>
    <div class="page-content">
        <h1 class="page-title">{{pageTitle}}</h1>
        <div class="content">
            <div v-for="contentPiece in pageContent" :key="contentPiece.locationOnPage" class="content-piece">
                <ContentPieceComponent :content-piece="contentPiece"/>
            </div>
            <!--<hr>-->
        </div>
        <!--ImportantComment <h3>{{$route.params.page}}</h3> -->

        <div>
            <button @click="showAddTextModal(0)" class="icon-button">
                متن &nbsp;
                <i class="demo-icon icon-doc-text-inv"></i>
            </button>
            <button @click="showAddImageModal(0)" class="icon-button">
                عکس &nbsp;
                <i class="demo-icon icon-picture-2"></i>
            </button>
            <!--<button @click="showAddAudioModal(0)">Add Audio</button>-->

            <!--<button @click="showAddImageModal(0)" class="icon-button">-->
            <!--صدا-->
            <!--<i class="demo-icon icon-picture-2"></i>-->
            <!--</button>-->
            <button @click="showAddCodeModal(0)" class="icon-button">
                کد&nbsp;
                <i class="demo-icon icon-code-3"></i>
            </button>
            <button @click="saveAllChangesToDB()" class="icon-button">Save All Changes to DB</button>
        </div>

        <AddTextModalComponent v-show="isShowingAddTextModalComponent"
                               :locationOfPieceToBeAdded="locationOfPieceToBeAdded"/>
        <AddAudioModalComponent v-show="isShowingAddAudioModalComponent"
                                :locationOfPieceToBeAdded="locationOfPieceToBeAdded"/>
        <AddImageModalComponent v-show="isShowingAddImageModalComponent"
                                :locationOfPieceToBeAdded="locationOfPieceToBeAdded"/>
        <AddCodeModalComponent v-show="isShowingAddCodeModalComponent"
                               :locationOfPieceToBeAdded="locationOfPieceToBeAdded"/>
    </div>
</template>

<script>
    import vex from '../Helpers/ModalManager'

    import toastr from 'toastr'

    import PageContentComponent from "../components/PageContentComponent";
    import AddTextModalComponent from "../components/AddTextModalComponent";
    import AddAudioModalComponent from "../components/AddAudioModalComponent";
    import AddImageModalComponent from "../components/AddImageModalComponent";
    import AddCodeModalComponent from "../components/AddCodeModalComponent";
    import ContentPieceComponent from '../components/ContentPieceComponent'
    import EventManager from "../EventManager";
    import _ from 'lodash';

    export default {
        name: "PageContentComponent",
        components: {
            AddTextModalComponent, AddAudioModalComponent, AddImageModalComponent, AddCodeModalComponent,
            ContentPieceComponent
        },
        data() {
            return {
                isShowingAddTextModalComponent: false,
                isShowingAddAudioModalComponent: false,
                isShowingAddImageModalComponent: false,
                isShowingAddCodeModalComponent: false,
                page: {content: []},
                content: null,
                allPiecesLocations: [],
                lastPieceLocation: 0,
                locationOfPieceToBeAdded: 0
            }
        },
        beforeCreate() {
            // window.onpopstate = () => {
            //     this.$router.push({name: 'MainPage'})
            // }

            // this.$store.dispatch('topics/getPageContent', this.$route.params.page)
            //     .then((res) => {
            //         console.log('page', res);
            //         this.page = res
            //     })
        },

        // beforeRouteEnter(to, from, next) {
        //     debugger
        //     next(vm => {
        //         window.onpopstate = () => {
        //             next({name: 'MainPage'})
        //         };
        //     })
        //     // next()
        // },

        created() {
            this.setCurrentPage(this.$route.params.page);
            // this.setAllPiecesLocations();
            // this.setLastPieceLocation();

            EventManager.listen('showAddTextModal', (locationOnPage) => {
                this.showAddTextModal(locationOnPage);
            });
            EventManager.listen('showAddAudioModal', (locationOnPage) => {
                this.showAddAudioModal(locationOnPage);
            });
            EventManager.listen('showAddImageModal', (locationOnPage) => {
                this.showAddImageModal(locationOnPage);
            });
            EventManager.listen('showAddCodeModal', (locationOnPage) => {
                this.showAddCodeModal(locationOnPage);
            });

            EventManager.listen('hide', () => {
                this.isShowingAddTextModalComponent = false;
                this.isShowingAddAudioModalComponent = false;
                this.isShowingAddImageModalComponent = false;
                this.isShowingAddCodeModalComponent = false
            });
            EventManager.listen('updatePage', () => {
                this.page = this.$store.state.topics.currentPage;
                this.content = this.$store.state.topics.currentPage.content;
                console.log('page updated', this.page);
                this.$forceUpdate();
            });
            EventManager.listen('updateLocationOfPieceToBeAdded', (data) => {
                this.locationOfPieceToBeAdded = Number(data + 1);

                // if (this.$store.state.topics.allPiecesLocations.includes(this.locationOfPieceToBeAdded)) {
                //     // alert('position ghablan set shode')
                //     console.log('ino', this.$store.state.topics.allPiecesLocations)
                // }
            })

        },
        beforeUpdate() {
            this.setAllPiecesLocations();
            this.setLastPieceLocation();
        },
        watch: {
            '$route'(to, from) {
                this.setCurrentPage(this.$route.params.page)
            }
        },
        computed: {
            pageTitle() {
                return this.page.title;
            },
            pageContent() {
                this.content = this.content || [];
                return _.orderBy(this.content, 'locationOnPage')
            }
        },
        methods: {
            // locationOfPieceToBeAdded(callerLocation) {
            //     if (callerLocation === 0) {
            //         return Number(this.lastPieceLocation + 1)
            //     } else {
            //         alert('not implemented')
            //     }
            // },
            setCurrentPage(pageId) {
                this.$store.dispatch("topics/setCurrentPage", pageId)
                    .then(res => {
                        this.$store.dispatch('topics/getPageContent', res.id)
                            .then(page => {
                                this.page = page;
                                this.content = page.content;
                                // this.page = {
                                //     ...this.page,
                                //     content: page.content
                                // };
                                // setTimeout(this.setAllPiecesLocations, 1000);
                                // setTimeout(this.setLastPieceLocation, 2000);
                                this.setAllPiecesLocations();
                                this.setLastPieceLocation();
                            })
                    })
            },
            setAllPiecesLocations() {
                this.$store.dispatch('topics/setAllPiecesLocations')
                    .then(res => {
                        this.allPiecesLocations = res;
                    });
            },
            setLastPieceLocation() {
                this.$store.dispatch('topics/setLastPieceLocation')
                    .then(res => {
                        this.lastPieceLocation = res;
                    });
            },
            showAddTextModal(callerLocation) {

                if (callerLocation == 0) {
                    this.locationOfPieceToBeAdded = this.lastPieceLocation + 1
                } else {
                    this.locationOfPieceToBeAdded = callerLocation + 1;
                }
                this.isShowingAddTextModalComponent = true
            },
            showAddImageModal(callerLocation) {
                if (callerLocation == 0) {
                    this.locationOfPieceToBeAdded = this.lastPieceLocation + 1
                } else {
                    this.locationOfPieceToBeAdded = callerLocation + 1;
                }
                this.isShowingAddImageModalComponent = true
            },
            showAddAudioModal(callerLocation) {
                if (callerLocation == 0) {
                    this.locationOfPieceToBeAdded = this.lastPieceLocation + 1
                } else {
                    this.locationOfPieceToBeAdded = callerLocation + 1;
                }
                this.isShowingAddAudioModalComponent = true
            },
            showAddCodeModal(callerLocation) {
                if (callerLocation == 0) {
                    this.locationOfPieceToBeAdded = this.lastPieceLocation + 1
                } else {
                    this.locationOfPieceToBeAdded = callerLocation + 1;
                }
                this.isShowingAddCodeModalComponent = true
            },
            saveAllChangesToDB() {
                this.$store.dispatch('topics/savePageToDB')
                    .then(() => {
                        toastr.success('همه تغییرات ذخیره شد')
                    })

            }
        }
    }
</script>

<style scoped>
    .page-content {
        padding-right: 1rem;
        margin: 1rem;
    }

    .page-title {
        font-family: 'B_Roya', sans-serif;
        margin: 1rem;
        color: #3e80e3;
        font-size: 2rem;
    }

    .content-piece {
        width: 80%;
    }
</style>
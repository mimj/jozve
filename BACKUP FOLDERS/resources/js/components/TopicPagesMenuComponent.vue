<template>
    <div>
        <!--<input v-model="filter" type="search" name="filter" class="filter" placeholder="لیست صفحات">-->

        <aside class="menu">
            <ul class="menu-list">
                <!--TODO: Colorize Selected Link-->
                <TopicPagesMenuItemComponent v-for="page in pages()" :page="page" :key="page.id"/>
            </ul>
            <button class="add-new-page" @click="showAddPageModalComponent(0,0)">
                <span>صفحه جدید</span>
                <i class="demo-icon icon-plus-3"></i>
            </button>
            <AddPageModalComponent v-show="isShowingAddPageModalComponent"
                                   :topic_id="topicId()"
                                   :prevPagePosition='prevPagePosition'
                                   :parent_id="parent_id"/>
            <!--:ParentPosition="positionOfNewPageParent"/>-->
        </aside>


    </div>
</template>

<script>
    import AddPageModalComponent from './AddPageModalComponent'
    import EventManager from '../EventManager'
    import vex from '../Helpers/ModalManager'
    import toastr from 'toastr'
    import TopicPagesMenuItemComponent from "./TopicPagesMenuItemComponent";

    export default {
        name: "TopicPagesMenuComponent",
        components: {TopicPagesMenuItemComponent, AddPageModalComponent},
        data() {
            return {
                isShowingAddPageModalComponent: false,
                pagesTitles: [],
                // filter: '',
                prevPagePosition: 0,
                parent_id: 0,
                showButtons: null,
            }
        },
        beforeMount() {
            EventManager.listen('forceUpdate', (payload) => {
                debugger
                console.log('payloadd', payload);
                let titles = payload.filter(title => {
                    return title.parent_id === 0;
                })
                this.pagesTitles = payload;
                this.$forceUpdate()
            });

            this.$store.dispatch('topics/getPages', 0)
                .then((pages) => {
                    console.log('allPages:', pages);
                    let pagesTitles = pages.filter(page => {
                        if (typeof page !== 'undefined' && page.parent_id === 0) {
                            return page;
                        }
                    });
                    // debugger
                    this.pagesTitles = pagesTitles
                });

        },
        mounted() {
            EventManager.listen('hide', () => {
                this.isShowingAddPageModalComponent = false;
            });
            EventManager.listen('showAddPageModalComponent', (payload) => {
                this.showAddPageModalComponent(payload.pagePosition, payload.pageId)
            });
            EventManager.listen('removePage', (payload) => {
                this.removePage(payload.pageId)
            });
        },
        methods: {
            showAddPageModalComponent(prevPagePosition = 0, parent_id = 0) {
                console.log('position', prevPagePosition);
                console.log('parent id', parent_id);
                this.isShowingAddPageModalComponent = true;
                this.parent_id = parent_id;
                this.prevPagePosition = prevPagePosition;
            },
            topicId() {
                return this.$store.state.topics.currentTopicId
            },
            removePage(id) {
                vex.dialog.confirm({
                    message: 'صفحه از دیتابیس پاک شود؟',
                    callback: (value) => {
                        if (value) {
                            this.$store.dispatch('topics/removePage', id)
                                .then(res => {
                                    toastr.success('صفحه پاک شد');
                                    this.pagesTitles = res;
                                })
                        }
                    }

                })
            },
            pages() {
                // let allPages = this.pagesTitles;
                // let filteredPages;
                // filteredPages = allPages.filter((page) => {
                //     if (page.title.toLowerCase().indexOf(this.filter.toLowerCase()) > -1) {
                //         return page
                //     }
                // });

                // let allPages = this.pagesTitles;
                // /**Descending sort by position*/
                // let filteredPages = allPages.sort((a, b) => {
                //     if (a.position > b.position) {
                //         return 1;
                //     }
                //     if (a.position < b.position) {
                //         return -1;
                //     }
                //     // a must be equal to b
                //     return 0;
                // })
                let sortedPagesTitles = [...this.pagesTitles];
                return sortedPagesTitles.sort((a, b) => {
                    if (a.position > b.position) {
                        return 1;
                    }
                    if (a.position < b.position) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                });
                // return sortedPagesTitles;
            },

        },
        computed: {

            topicTitle() {
                return this.$store.getters['topics/getCurrentTopic'].title
            },

        }
    }
</script>

<style scoped>
    .menu {
        margin: 10px;
        width: 500px;
    }

    .menu-list {
        /*width: inherit;*/
        display: flex;
        flex-flow: column;
        overflow: auto;

    }

    .add-new-page {
        background: none;
        border: none;
        font-family: "B_Roya";
        font-weight: bold;
        padding: .3rem;
        margin: .3rem;
    }

    .add-new-page:hover {
        background: #3e80e3;
        cursor: pointer;
        color: white;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
</style>
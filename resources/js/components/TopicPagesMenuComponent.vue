<template>
    <div>
        <!--<input v-model="filter" type="search" name="filter" class="filter" placeholder="لیست صفحات">-->

        <aside class="menu">
            <ul class="menu-list">
                <!--TODO: Colorize Selected Link-->
                <TopicPagesMenuItemComponent v-for="page in pages()" :page="page" :key="page.id"/>
            </ul>
            <button class="add-new-page" @click="showAddPageModalComponent()">
                <span>صفحه جدید</span>
                <i class="demo-icon icon-plus-3"></i>
            </button>
            <AddPageModalComponent v-show="isShowingAddPageModalComponent"
                                   :topic_id="topicId()"
                                   :prevPageId='prevPageId'
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
                filter: '',
                prevPageId: 0,
                parent_id: 0,
                showButtons: null,
            }
        },
        beforeMount() {
            EventManager.listen('forceUpdate', (payload) => {
                // debugger
                console.log('payloadd', payload);
                let titles = payload.pages.filter(title => {
                    return title.parent_id == null;
                });
                this.pagesTitles = titles;
                this.$forceUpdate()
            });

            this.$store.dispatch('topics/getPages', null)
                .then((pages) => {
                    console.log('allPages:', pages);
                    let pagesTitles = pages.filter(page => {
                        if (typeof page !== 'undefined') {
                            return page;
                        }
                    });
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
                this.removePage(payload.pageId, payload.parentPageId)
            });
        },
        methods: {
            showAddPageModalComponent(prevPageId = null, parent_id = null) {
                console.log('position', prevPageId);
                console.log('parent id', parent_id);
                this.isShowingAddPageModalComponent = true;
                this.parent_id = parent_id;
                this.prevPageId = prevPageId;
            },
            topicId() {
                return this.$store.state.topics.currentTopicId
            },
            removePage(id, parent_id) {
                vex.dialog.confirm({
                    message: 'صفحه از دیتابیس پاک شود؟',
                    callback: (value) => {
                        if (value) {
                            this.$store.dispatch('topics/removePage', id)
                                .then(res => {
                                    // debugger
                                    toastr.success('صفحه پاک شد');
                                    if (parent_id == null) {
                                        EventManager.fire('forceUpdate', {pages: res});
                                    }
                                    else if (parent_id != null) {
                                        EventManager.fire('updateSubPageTitles', {
                                            parent_id: parent_id,
                                            subPageTitles: res
                                        })
                                    }
                                })
                        }
                    }

                })
            },
            pages() {
                let sortedPagesTitles = [...this.pagesTitles];
                // let result = [];
                // if (this.filter.length > 0) {
                //     for (let page in sortedPagesTitles) {
                //         result = this.recursiveSearch(this.filter, sortedPagesTitles[page], result)
                //     }
                //     return result;
                // } else {

                return sortedPagesTitles.sort((a, b) => {
                    // debugger
                    if (a._lft > b._lft) {
                        return 1;
                    }
                    if (a._lft < b._lft) {
                        return -1;
                    }
                    // a must be equal to b
                    return 0;
                });
                // }
                // return sortedPagesTitles;
            },
            recursiveSearch(term, page, filteredResult) {
                // debugger
                if (page.title.includes(term)) {
                    filteredResult.push(page)
                } else {
                    if (page.hasOwnProperty('pages')) {
                        if (page.pages.length > 0) {
                            for (let i = 0; i < page.pages.length; i++) {
                                let p = page.pages[i];
                                this.recursiveSearch(term, p, filteredResult);
                            }
                        }
                    }
                }
                return filteredResult;
            }

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
        width: 200px;
        overflow-x: auto;
        /*background: #acffb5;*/
    }

    .menu-list {
        /*width: inherit;*/
        display: flex;
        flex-flow: column;
        /*overflow: auto;*/
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
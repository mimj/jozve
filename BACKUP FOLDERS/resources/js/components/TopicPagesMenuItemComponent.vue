<template>
    <li @mouseover="showActionButtons(page.id,$event)" @mouseleave="hideActionButtons()" class="page-title">

        <i
                :class="{'icon-left-open':!isopen,'icon-down-open':isopen}"
                class="demo-icon icon-button-verysmall"
                style="float: right"
                @click="showSubPageTitles(page.id)"></i>
        <div class="title-wrapper">
            <router-link :to="{name:'page',params:{page:page.id}}">{{page.title}}</router-link>
            <ul v-if="showButtons === page.id" class="actionButtons" ref="actionButtons">
                <li
                        title="add page"
                        @click="showAddPageModalComponent(page.position,page.parent_id)"
                        class="demo-icon icon-plus-1 icon-button-small"
                        style="float: left; "></li>
                <li
                        title="add sub page"
                        @click="showAddPageModalComponent(page.position,page.id)"
                        class="demo-icon icon-plus-circled icon-button-small"
                        style="float: left;"></li>
                <li
                        @click="removePage(page.id)"
                        class="demo-icon icon-trash-1 icon-button-small-red"
                        style="float: left;"></li>
            </ul>
        </div>
        <ul class="menu-list" v-if="isopen">
            <TopicPagesMenuItemComponent v-for="subPage in subPageTitles" :page="subPage" :key="subPage.id"/>
        </ul>
    </li>
</template>

<script>
    import EventManager from '../EventManager'
    import vex from '../Helpers/ModalManager'
    import toastr from 'toastr'


    export default {
        name: "TopicPagesMenuItemComponent",
        props: ['page'],
        data() {
            return {
                showButtons: null,
                subPageTitles: [
                    // {
                    //     id: 212,
                    //     title: 'title',
                    //     parent_id: 23,
                    //     position: 1
                    // }
                ],
                isopen: false
            }
        },
        mounted() {
            // EventManager.listen('updateSubPageTitles', (payload) => {
            //     // debugger
            //     console.log('payload', payload)
            //     // this.$forceUpdate()
            // });

            EventManager.listen('updateSubPageTitles', (payload) => {
                if (payload.parent_id === this.page.id) {
                    if (payload.newPage) {
                        this.subPageTitles.push(payload.newPage);
                    } else if (payload.subPageTitles) {
                        this.subPageTitles = payload.subPageTitles;
                    }
                }
                // let parent_page = this.pagesTitles.filter(page => {
                //     return page.id === subPagesTitles.parent_id;
                // });
                // console.log('parent_page', parent_page);
                // parent_page.subPageTitles = parent_page.subPageTitles || [];
                // parent_page.subPageTitles.push(subPagesTitles.newPage);
                // console.log('updateSubPageTitles listener payload', subPagesTitles)
                this.$forceUpdate()
            });
        },
        methods: {
            showActionButtons(id, event) {
                this.showButtons = id;
            },
            hideActionButtons(event) {
                // console.log('hide')
                this.showButtons = null;
            },
            showAddPageModalComponent(pagePosition, pageId) {
                EventManager.fire('showAddPageModalComponent', {pagePosition, pageId})
            },
            removePage(id) {
                EventManager.fire('removePage', {pageId: id})
            },
            showSubPageTitles(pageId) {
                if (!this.isopen) {
                    this.$store.dispatch('topics/getPages', this.page.id)
                        .then((pages) => {
                            console.log('subPages:', pages);
                            let subPageTitles = pages.filter(page => {
                                if (typeof page !== 'undefined' && page.parent_id === page.parent_id) {
                                    return page;
                                }
                            });
                            console.log('p', subPageTitles);
                            this.subPageTitles = subPageTitles;
                            EventManager.fire('updateSubPageTitles', {parent_id: this.page.id, subPageTitles})
                        });

                }
                this.isopen = !this.isopen;

            }

        }
    }
</script>

<style scoped>
    .page-title {
        /*background-color: red;*/
        /*border-bottom: 1px solid #767583;*/
        position: relative;
        /*padding: .4rem .1rem;*/
        /*width: 500px;*/
        margin: 1rem 0rem 0 0;
        font-family: sans-serif;
    }

    .page-title:last-child {
        border: none;
    }

    .actionButtons {
        /*float: left;*/
        display: flex;
        /*left: 0;*/
        /*top: 0;*/
        height: 0;
        line-height: 0;
        margin-top: -.3rem;
        /*transform: translate(0, -50%);*/
    }

    .menu-list {
        /*width: inherit;*/
        display: flex;
        flex-flow: column;
        /*overflow: scroll;*/

    }

    .title-wrapper {
        display: flex;
        flex-flow: row
    }

    .page-title a {
        font-family: 'sans-serif', B_Roya;
        color: #2643ff;
        /*width: 100%;*/
        height: 100%;
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>
<template>
    <div id="topicsListWrapper">
        <div class="menu">
            <input v-model="filter" type="search" name="filter" class="filter" placeholder="لیست موضوعات">
            <!--<button @click="defineDirection" v-html="leftToRightOrRightToLeft"></button>-->
            <span v-if="(topics.length>0)" @click="defineDirection" class="directionButton"
                  :class="leftToRightOrRightToLeft">

             </span>
            <ul class="menu-list" :class="{directionAndFont : !rtl}">

                <!--<i v-if="!(topics.length>0)" class="demo-icon icon-book-open-1 empty-list-indicator"></i>-->

                <li v-for="topic in topics" :key="topic.id">
                    <a href=""
                       @click.prevent="setCurrentTopicAndGoToReadingPage(topic.id,{name: 'topic',params:{topic: topicTitleTrimmed(topic.title),topicId:topic.id}})">
                        {{topic.title}}
                    </a>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    export default {
        name: "topics-list",
        data() {
            return {
                rtl: true,
                filter: ''
            }
        },
        beforeMount() {
            this.$store.dispatch('topics/getAllTopics');
        },
        mounted() {
            // console.log(this.$store.state)
        },
        methods: {
            // topics(){
            //     let topics = this.$store.state.topics.allTopics;
            //     // topics = topics.forEach(topic => topic.title.replace(/\s+/g, '-').toLowerCase());
            //     console.log('topics',topics)
            //     return topics;
            // }
            setCurrentTopicAndGoToReadingPage(id, routParams) {
                let routeParameters = routParams;
                this.$store.dispatch('topics/updateCurrentTopic', id)
                    .then(() => {
                        this.$router.push(routeParameters)
                    })
            },
            defineDirection() {
                this.rtl = !this.rtl;
            }
        },
        computed: {
            leftToRightOrRightToLeft() {
                if (this.rtl) {
                    return "right"
                } else {
                    return 'left'
                }
            },
            // ...Vuex.mapState('topics', {topics: 'allTopics'}),
            topics() {
                let allTopics = this.$store.state.topics.allTopics;
                let filteredTopics = allTopics.filter((topic) => {
                    if (topic.title.toLowerCase().indexOf(this.filter.toLowerCase()) > -1) {
                        return topic
                    }
                });
                return filteredTopics;

            },

            /**
             * Replaces spaces with dashes and make all letters lowercase
             */
            topicTitleTrimmed(title) {
                return title => title.replace(/\s+/g, '-').toLowerCase();
            }
        },

    }
</script>

<style scoped>
    #topicsListWrapper {
        display: flex;
        flex-flow: column;
        align-items: center;
        text-align: right;
    }

    #topicsListWrapper h3 {
        font-size: 2rem;
        margin: 1rem;
        color: #4a484f;
    }

    .directionAndFont {
        direction: ltr;
        text-align: left;
        font-family: Arial, "Segoe UI", sans-serif;
    }

    .menu {
        width: 20rem;
        font-family: sans-serif;
    }


    .empty-list-indicator{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        font-size: 3rem;
    }

    ul {
        padding: 0;
        max-width: 300px;
    }

    /* List element */
    li {
        display: flex;
        align-items: center;
        box-sizing: border-box;
        margin-right: 10px;
    }

    li > a {
        width: 100%;
        height: 100%;
        padding: 12px 10px;
        color: #393c86;
    }

    li > a:hover {
        background: #464a96;
        color: #dbdbdb;
    }

    /* Element separation */
    li + li {
        border-top: 1px solid rgba(30, 30, 30, 0.2);
    }

    /**DIRECTION BUTTON*/
    /*.directionButton {*/
    /*width: 40px;*/
    /*padding: 20px;*/
    /*position: relative;*/
    /*}*/

    .left {
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg"  width="24" height="24" viewBox="0 0 24 24"><path  d="M0 12l9-8v6h15v4h-15v6z"/></svg>') no-repeat;
    }

    .right {
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 12l-9-8v6h-15v4h15v6z"/></svg>') no-repeat;
    }

    /**DIRECTION BUTTON*/

</style>
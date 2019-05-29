<template>

    <div>
        <!--<div class="header">-->
            <!--<a class="brand" href="/app">-->
                <!--جزوه-->
            <!--</a>-->
            <!--<ul>-->
                <!--<li class="">-->
                    <!--<a href="/logout" @click.prevent="logout">-->
                        <!--خروج-->
                    <!--</a>-->
                <!--</li>-->
            <!--</ul>-->
        <!--</div>-->
        <div class="topic-title">
            <h2>
                <h1 class="latin">{{$route.params.topic}}</h1>
            </h2>
            <!--<h3>currentTopicId: <span>{{currentTopicId}}</span></h3>-->
        </div>

        <div class="wrapper">
            <div id="sidebarRight">
                <br>
                <TopicPagesMenuComponent/>
            </div>
            <div id="content">

                <router-view></router-view>

            </div>
        </div>

    </div>

</template>
<script>
    import TopicPagesMenuComponent from "../components/TopicPagesMenuComponent";
    import PageContentComponent from "../components/PageContentComponent";
    import AddTextModalComponent from "../components/AddTextModalComponent";
    import AddAudioModalComponent from "../components/AddAudioModalComponent";
    import AddImageModalComponent from "../components/AddImageModalComponent";
    import AddCodeModalComponent from "../components/AddCodeModalComponent";
    import EventManager from "../EventManager";

    export default {
        name: "ReadingPage",
        components: {
            AddImageModalComponent, AddAudioModalComponent, AddTextModalComponent, AddCodeModalComponent,
            PageContentComponent, TopicPagesMenuComponent
        },
        data() {
            return {
                currentTopicId: null,
            }
        },
        beforeRouteEnter(to, from, next) {
            if (from.name == null) {
                next('/app')
            }
            next(vm => {
                // console.log(vm.$root.$store.state)
            })
        },
        // beforeRouteLeave(to, from, next) {
        //     console.log(this)
        //     // console.log('jojo')
        //     // next(vm => {
        //     //     console.log('vm',vm)
        //     //     console.log('to',vm)
        //     //     console.log('from',vm)
        //     //     // console.log(vm.$root.$store.state)
        //     // })
        // },
        beforeMount() {
            this.getCurrentTopicId();
        },
        methods: {
            currentTopicTitle() {
                // let topic = this.$store.getters['topics/getTopicById'](this.$store.state.topics.getCurrentTopicId).title;
                // return topic.title;

                // return topic = this.$store.state.topics.allTopics.find(topic => topic.id === this.$store.state.topics.getCurrentTopicId);
                return "kkk";

            },
            getCurrentTopicId() {
                // return this.$store.state.topics.getCurrentTopicId
                this.$store.dispatch('topics/getCurrentTopicId').then(value => {
                    // console.log('lll', value);
                    this.currentTopicId = value
                })
            },
        }
    }
</script>
<style scoped>
    .topic-title {
        padding: 1rem;
        /*background: #0e301a;*/
        color: #009f76;
        font-size: 2rem;
    }
    .topic-title h1{
        max-width: 100%;
    }

    .wrapper {
        display: flex;
        /*flex-wrap: wrap;*/
    }

    #sidebarRight {
        /*background: #b2adb2;*/
        /*align-self: right;*/
        padding: 10px;
        min-height: 100vh;
        min-width: 250px;
        /*overflow-y: scroll;*/
        top: 0;
    }

    #content {
        /*background: #7a8561;*/
        /*width: 100%;*/
        flex-grow: 1;
    }

    @media screen and (max-width: 800px) {
        .wrapper {
            flex-flow: column;
        }
    }
</style>
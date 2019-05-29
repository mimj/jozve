<template>

    <div>
        <div class="header">
            <a class="brand" href="/app">
                جزوه
            </a>
            <ul>
                <li class="">
                    <a href="/logout" @click.prevent="logout">
                        خروج
                    </a>
                </li>
            </ul>
        </div>
        <!--<SearchComponent></SearchComponent>-->
        <TopicsList></TopicsList>
        <a class="addTopicButton" @click.prevent="showAddTopicComponent">موضوع جدید</a>
        <br>
        <AddTopicComponent v-show="isShowingAddTopicComponent"></AddTopicComponent>

    </div>
</template>

<script>
    import TopicsList from '../components/TopicsListComponent'
    import SearchComponent from "../components/SearchComponent";
    import AddTopicComponent from "../components/AddTopicComponent";
    import EventManager from '../EventManager'
    import axios from 'axios'

    export default {
        name: "MainPage",
        components: {SearchComponent, TopicsList, AddTopicComponent},
        data() {
            return {
                isShowingAddTopicComponent: false
            }
        },
        methods: {
            showAddTopicComponent() {
                this.isShowingAddTopicComponent = true
            },
            hideAddTopicComponent() {
                this.isShowingAddTopicComponent = false
            },
            logout() {
                axios.post('/logout')
            }
        },
        mounted() {
            EventManager.listen('hide', () => {
                this.hideAddTopicComponent();
            })
        }
    }
</script>

<style scoped>
    .addTopicButton {
        position: fixed;
        background: #4a4bff;
        color: white;
        top: 50%;
        cursor: pointer;
        padding: 20px 40px 20px 20px;
        border-top-left-radius: 999px;
        border-bottom-left-radius: 999px;
    }

    @media screen and (max-width: 800px) {
        .addTopicButton {
            top: 0;
            left: 2rem;
            cursor: pointer;
            padding: 40px 20px 20px 20px;

            border-top-left-radius: 0;
            border-bottom-right-radius: 999px;
            border-bottom-left-radius: 999px;
        }
    }
</style>
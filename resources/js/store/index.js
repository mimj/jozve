import Vuex from 'vuex'
import Vue from 'vue'
import topics from './modules/topics'

Vue.use(Vuex);
export default new Vuex.Store({
    state: {},
    getters: {},
    modules: {
        topics: topics
    }

})

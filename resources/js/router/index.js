import Vue from 'vue'
import Router from 'vue-router'
import MainPage from '../pages/MainPage'
import ReadingPage from '../pages/ReadingPage'
import PageContentComponent from '../components/PageContentComponent'

Vue.use(Router);

const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/app',
            name: 'MainPage',
            component: MainPage,
        }, {
            path: '/app/:topic',
            name: 'topic',
            component: ReadingPage,
            children: [
                {
                    canReuse: false,
                    path: ':page',
                    name: 'page',
                    component: PageContentComponent,

                }
            ]
        }
    ],
})


// router.beforeEach((to, from, next) => {
//     let isDelegated = false;
//
//     for (let matched = (to.matched || []), i = matched.length; i--;) {
//         let route = matched[i];
//
//         if (route.meta.beforeEach) {
//             isDelegated = true;
//             route.meta.beforeEach(to, from, next);
//         }
//     }
//
//     !isDelegated && next();
// });

export default router
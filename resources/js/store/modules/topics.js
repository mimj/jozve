import axios from 'axios'
import PouchDB from "pouchdb";
import _ from "lodash"
import EventManager from "../../EventManager";
import findRecursive from '../../Helpers/DeepFind'

/**
 * Redirect to login page if UNAUTHENTICATED user send ajax request
 */
axios.interceptors.response.use(function (response) {
    // Do something with response data
    // console.log("response interceptors:", response);

    return response;
}, function (error) {
    if (error.response.status === 401) {
        window.location = "/login";
    }
    return Promise.reject(error);
});

let db = new PouchDB('offlineState');
let state = {
    "_id": "state",
    "name": "state",
    "currentTopicId": null,
    "allTopics": [{a: 1, b: 2}]
};
db.put(state)
    .then(res => {
        // console.log("pouchDB: indexedDB created successfully")
    }).catch(err => {
    if (err.name == 'conflict') {
        // console.log("pouchDB: indexedDB already exists")
    }
});

export default {
    namespaced: true,
    state: {
        allTopics: [],
        currentTopicId: null,
        currentTopic: {},
        currentPage: {content: [], parent_id: 0},
        // @type Number
        allPiecesLocations: [],
        lastPieceLocation: 0, //location of text,audio,movie or codes to be added on the page
    },
    mutations: {
        addToTopics(state, topic) {
            state.allTopics = state.allTopics || [];
            state.allTopics.push(topic)
        },
        updateTopics(state, topics) {
            state.allTopics = topics;
        },
        updateCurrentTopic(state, id) {
            db.get('state')
                .then((offlineState) => {
                    offlineState.currentTopicId = id;
                    db.put(offlineState)
                });
            state.currentTopicId = id;
            state.currentTopic = state.allTopics.find(topic => topic.id === state.currentTopicId);
        },
        addPageTitleToTopic(state, page) {
            // debugger
            let topic = state.allTopics.find((topic) => topic.id === state.currentTopicId);
            // console.log('topic here', topic);
            // console.log('page here', page);
            // pages = [{}]
            topic.pages = topic.pages || [];
            // debugger
            let parentPage = topic.pages.filter(parent_page => {
                return parent_page.id === page.parent_id;
            });
            // console.log('parentPage', parentPage);
            parentPage.push(page);
            topic.pages.push(page);
            state.allTopics = [
                ...state.allTopics.filter((topic) => topic.id !== state.currentTopicId),
                topic
            ];
            console.log('state.alltopics', state.allTopics)
        },
        updatePagesForTopic(state, payload) {
            // debugger
            //okok
            let topic = state.allTopics.find((topic) => topic.id === state.currentTopicId);

            if (payload.parentPageId == null) {
                Object.assign(topic, {'pages': payload.pages});
            } else {
                // let page = topic.pages.find((page) => page.id === payload.parentPageId);
                let page = null;
                for (let i = 0; i < topic.pages.length; i++) {
                    // debugger
                    let p = topic.pages[i];
                    page = findRecursive(payload.parentPageId, p);
                    if (page) {
                        state.currentPage = page;
                        break
                    }
                }

                Object.assign(page, {'pages': payload.pages})
                // page.subPages = payload.pages;
            }
            state.allTopics = [
                ...state.allTopics.filter((topic) => topic.id !== state.currentTopicId),
                topic
            ];
        },
        setCurrentPage(state, pageId, parentPage_id) {
            let currentTopic = state.allTopics.find(topic => topic.id === state.currentTopicId);
            if (pageId == null) {
                alert('ojojoj');
                // currentTopic.pages.sort((a, b) => (a.position > b.position) ? 1 : -1);
                // state.currentPage = currentTopic.pages[0];
            } else {
                /** I Don't Know Why this Does not work with TRIPLE EQUALS When Back Button Clicked
                 * By Passing the Page Id to the Number Function it Worked
                 * It Seems that store returning value as string*/
                // state.currentPage = currentTopic.pages.find(function f(page) {
                //         if (page.id === Number(pageId)) {
                //             return true
                //         }
                //         if (page.subPages) {
                //             return (page.subPages = page.subPages.find(f)).length
                //         }
                //     }
                // )

                for (let i = 0; i < currentTopic.pages.length; i++) {
                    // debugger
                    let p = currentTopic.pages[i];
                    state.currentPage = findRecursive(pageId, p);
                    if (state.currentPage)
                        break
                }
                // debugger


            }
            /** if parent page id not set( adding new page using the add page button)*/
            // parentPage_id ? state.currentPage.parent_id = parentPage_id : state.currentPage.parent_id = parentPage_id = 0
        },
        // setCurrentPage2(state, page) {
        //     debugger;
        //     let currentTopic = state.allTopics.find(topic => topic.id === state.currentTopicId);
        //     if (page.id == null) {
        //         currentTopic.pages.sort((a, b) => (a.position > b.position) ? 1 : -1);
        //         state.currentPage = currentTopic.pages[0];
        //         state.currentPage.content = JSON.parse(state.currentPage.content)
        //
        //
        //     } else {
        //         /** I Don't Know Why this Does not work with TRIPLE EQUALS When Back Button Clicked
        //          * By Passing the Page Id to the Number Function it Worked
        //          * It Seems that store returning value as string*/
        //         debugger
        //         state.currentPage = currentTopic.pages.find(p => p.id === Number(page.id));
        //         state.currentPage.content = JSON.parse(state.currentPage.content)
        //
        //         // for (const page of currentTopic.pages) {
        //         //     if (page.id == pageId) {
        //         //         console.log('id in mutation', pageId)
        //         //         state.currentPage = page;
        //         //     }
        //         // }
        //
        //     }
        // },
        updateCurrentPageContent(state, content) {
            state.currentPage.content = content;
            EventManager.fire('updatePage');
        },
        setAllPiecesLocations(state) {
            state.currentPage.content = state.currentPage.content || [];
            // debugger
            state.allPiecesLocations = state.currentPage.content.map(c => Number(c.locationOnPage));
        },
        setLastPieceLocation(state) {

            // _.orderBy(state.allPiecesLocations)
            // debugger
            state.lastPieceLocation = state.allPiecesLocations.sort((a, b) => a - b)[state.allPiecesLocations.length - 1];
        },
        updateContentPiecesLocations(state, payload) {
            console.log('isRemove: ', payload.isRemove);
            // let content = state.currentPage.content.map(piece => {
            //     if (piece.locationOnPage > startLocation) {
            //         state.currentPage.content.find()
            //     }
            // })
            for (let i = 0; i < state.currentPage.content.length; i++) {
                if (state.currentPage.content[i].locationOnPage >= payload.startLocation) {
                    state.currentPage.content[i].locationOnPage += 1
                }
            }
        },
        addTextPiece(state, text) {
            /** If There is no content in that array calling push causes error so using this or clause*/

            state.currentPage.content = state.currentPage.content || [];
            state.currentPage.content.push(text);
            EventManager.fire('updatePage') // IMPORTANT: it will be listened in PageContentComponent
        },
        removePiece(state, piece) {
            console.log('commit remove this piece: ', piece);
            for (let i = 0; i < state.currentPage.content.length; i++) {
                if (state.currentPage.content[i].locationOnPage === piece.locationOnPage) {
                    // debugger
                    state.currentPage.content.splice(i, 1)
                }
            }
        },
        // removePage(state, pageId) {
        //     let topicOfPage = state.allTopics.find(topic => topic.id === state.currentTopicId);
        //     let pages = topicOfPage.pages.filter(page => page.id !== Number(pageId));
        //     // state.allTopics = state.allTopics.map(topic => {
        //     //     if (topic.id == topicOfPage.id) {
        //     //         topicOfPage.pages = pages;
        //     //         return topicOfPage;
        //     //     } else {
        //     //         return topic;
        //     //     }
        //     // });
        //     // console.log('mutation pageToDelete: ', state.allTopics)
        // }
    },
    getters: {
        // pageContent(state) {
        //     let topic = state.allTopics.find(topic => topic.id === state.currentTopicId)
        //     // console.log('page here', topic.pages.find(page => page.id === pageId))
        //     return (pageId) => {
        //         return topic.pages.find(page => page.id === pageId)
        //     }
        // },
        getCurrentTopic(state) {
            // let topic = state.allTopics.find(topic => topic.id === state.currentTopicId);
            // return topic;
            return state.currentTopic;
        },
        getCurrentPage(state, pageId) {
            let topic = state.allTopics.find(topic => topic.id === state.currentTopicId);

            return (pageId) => {
                return state.currentTopic.pages.find(page => page.id === pageId)
            }
        },
    },
    actions: {
        //Topics
        getAllTopics({commit, state, getters, dispatch}) {
            axios.get('/topics')
                .then((res) => {
                    commit('updateTopics', res.data)
                })
        },
        addToTopics({commit}, topicTitle) {
            axios.post('/topics', topicTitle)
                .then((res) => {
                    commit('addToTopics', res.data)
                })
        },
        updateCurrentTopic({commit}, topic_id) {
            commit('updateCurrentTopic', topic_id);
            sessionStorage.setItem('currentTopicId', topic_id)
        },
        getCurrentTopicId({state}) {
            if (state.currentTopicId) {
                return state.currentTopicId
            } else {
                return db.get('state').then((offlineState) => {
                    return offlineState.currentTopicId
                    // console.log('offoo', offlineState.currentTopicId)
                })
            }
        },
        removeTopic({state}, topicId) {
            return axios.delete('/topics/' + topicId)
                .then(() => {
                    // let topic = state.allTopics.find((topic) => topic.id === state.currentTopicId);
                    state.allTopics = state.allTopics.filter((topic) => topic.id !== topicId);
                    console.log('removed topic');
                }).catch(() => {
                console.log('مشکلی پیش امد')
            })
        },
        //Topics

        //Pages
        getPages({commit, state}, parentPageId) {
            console.log('parentPageId', parentPageId);
            let formData = new FormData();
            formData.set('parentPageId', parentPageId);
            return axios.get('/' + state.currentTopicId + "/pages", {params: {parentPageId}})
                .then((res) => {
                    console.log('res', res)
                    commit('updatePagesForTopic', {parentPageId, pages: res.data});
                    return res.data
                })

        },
        getPageContent({commit, state}, pageId) {
            return axios.get('/' + state.currentTopicId + '/pages/' + pageId)
                .then(res => {
                    let parsedContentAsJson = JSON.parse(res.data.content);
                    let page = res.data;
                    page.content = parsedContentAsJson;
                    let pageWithParsedContentAsJson = page;
                    commit('updateCurrentPageContent', parsedContentAsJson);
                    return pageWithParsedContentAsJson
                })
        },
        addPageTitleToTopic({commit, state, dispatch}, pageTitle) {
            let topic = state.allTopics.find((topic) => topic.id === state.currentTopicId);
            console.log('pageTitle', pageTitle);
            let data = {
                topic_id: state.currentTopicId,
                title: pageTitle.title,
                parent_id: pageTitle.parent_id,
                prevPageId: pageTitle.prevPageId,
                page_content: null
            };

            return axios.post('/' + state.currentTopicId + "/pages", data)
                .then((res) => {
                    console.log('page saved: ', res);
                    // commit("addPageTitleToTopic", res.data);
                    return dispatch('getPages', res.data.parent_id)
                        .then((result) => {
                            // debugger
                            return {parent_id: res.data.parent_id, pages: result};
                        })
                })
        },
        setCurrentPage({commit, state}, pageId = null, parent_page_id = 0) {
            // axios.get('/' + state.currentTopicId + '/pages/' + pageId)
            // .then(res => {
            //     // commit('setCurrentPage2', res.data);
            //     // return state.currentPage;
            // });
            // debugger
            commit('setCurrentPage', pageId, parent_page_id);
            return state.currentPage;
        },
        // getFirstPage({state}) {
        //     let currentTopic = state.allTopics.find(topic => topic.id === state.currentTopicId);
        //     currentTopic.pages.sort((a, b) => (a.position > b.position) ? 1 : -1);
        //     return currentTopic.pages[0];
        // },
        // getPage({state}, pageId) {
        //     let currentTopic = state.allTopics.find(topic => topic.id === state.currentTopicId);
        //     // currentTopic.pages.sort((a, b) => (a.position > b.position) ? 1 : -1)
        //     return currentTopic.pages.find(page => page.id === pageId);
        // }
        setAllPiecesLocations({state, commit}) {
            commit('setAllPiecesLocations');
            return state.allPiecesLocations;
        },
        setLastPieceLocation({state, commit}) {
            commit('setLastPieceLocation');
            return state.lastPieceLocation || 0;
        },
        updateContentPiecesLocations({state, commit}, location) {
            commit('updateContentPiecesLocations', location)
        },
        addTextPiece({state, commit, dispatch}, text) {
            console.log('action addTextPiece', text);
            if (state.allPiecesLocations.includes(text.locationOnPage)) {
                dispatch('updateContentPiecesLocations', {startLocation: text.locationOnPage, isRemove: false})
                    .then(res => {
                        commit('addTextPiece', text);
                    })
            } else {
                commit('addTextPiece', text);
            }
        },
        removePiece({state, commit, dispatch}, piece) {
            console.log('action remove Piece', piece);
            if (state.allPiecesLocations.includes(piece.locationOnPage)) {
                // dispatch('updateContentPiecesLocations', {startLocation: piece.locationOnPage, isRemove: true})
                //     .then(res => {
                //         commit('removePiece', piece);
                //     })
                let promise = new Promise((resolve, reject) => {
                    commit("removePiece", piece);
                    console.log('piece imageUrl: ', piece.imageUrl);
                    resolve()
                }).then(() => {
                    let formData = new FormData();
                    formData.append('topic_unique_name', state.currentTopic.unique_name);
                    formData.append('page_unique_name', state.currentPage.unique_name);
                    axios.delete('/' + state.currentTopicId + "/pages/" + state.currentPage.id + '/' + piece.locationOnPage, formData)
                        .then(res => {
                            console.log("DELEteD:", res);
                        })
                })
            } else {
                alert('مشکلی پیش اومد مشخصات این تکه پیدا نشد')
            }
        },
        savePageToDB({state, commit}) {
            // let promise = new Promise((resolve, reject) => {
            let filesFormData = new FormData(); //add files to this formData
            let formData = new FormData(); //add other content to this formData
            let currentPage = Object.assign({}, state.currentPage); // copy currentPage for consistency
            let content = []; //temporary content array to delete 'image' and 'imageBase64'  fields from each content piece
            Object.keys(currentPage).forEach(function (key) {
                if (key == 'content') {
                    for (let item in currentPage.content) {
                        //each item is a Content Piece

                        let tempItem = {};
                        Object.assign(tempItem, currentPage[key][item]);

                        //TODO: check if image is uploaded by checking state.currentPage.content[item].imageIsUploaded
                        if (currentPage[key][item].hasOwnProperty('image')) {
                            console.log('image image: ', currentPage[key][item].image);
                            filesFormData.set('files[' + currentPage[key][item].image.name + ']', currentPage[key][item].image);
                            // Object.assign(tempItem, {image: {}, imageBase64: {}}) //this removes 'image' and 'imageBase6 from tempItem object'
                            delete tempItem.image;
                            delete tempItem.imageBase64
                        }
                        content.push(tempItem)
                    }
                    console.log('assigned content', content);
                    currentPage[key] = JSON.stringify(content); //to store in database it should be stringified before send to backend
                }
                formData.set(key, currentPage[key]);
                formData.append('_method', 'put');
            });
            // for (let i of formData.entries()) {
            //     console.log('yyyy', i[0] + ', ' + i[1]);
            // }
            formData.set('topic_unique_name', state.currentTopic.unique_name);
            axios.post('/' + state.currentTopicId + "/pages/" + currentPage.id + "/upload", filesFormData).then((res) => {
                console.log('images sent to db', res)

                //TODO: mutate imageIsUploaded property to true in each content piece of currentPage in both client and backend
                let content = [];
                // debugger
                for (let item in state.currentPage.content) {
                    //each item is a Content Piece

                    let tempItem = {};
                    Object.assign(tempItem, state.currentPage.content[item]);

                    //TODO: check if image is uploaded by checking state.currentPage.content[item].imageIsUploaded
                    if (state.currentPage.content[item].hasOwnProperty('imageIsUploaded')) {
                        delete tempItem.image;
                        delete tempItem.imageBase64;
                        Object.assign(tempItem, {imageIsUploaded: true}) //this removes 'image' and 'imageBase6 from tempItem object'
                    }
                    content.push(tempItem)
                }
                // debugger
                return content;

            }).then((content) => {
                formData.set('content', JSON.stringify(content));
                axios.post('/' + state.currentTopicId + "/pages/" + currentPage.id, formData)

                    .then((res) => {
                        commit('updateCurrentPageContent', content);
                        //TODO: mutate image and imageBase64 property to null in each content piece of currentPage
                        console.log('content sent', res)
                    })
            })
            // resolve(formData);
            // });
            // promise.then((data) => {
            //     // for (let i of data.entries()) {
            //     //     console.log('yyyy', i[0] + ', ' + i[1]);
            //     // }
            //     data.append('_method', 'put');
            //     console.log('before save: ', state.currentPage);
            //     axios.post('/' + state.currentTopicId + "/pages/" + state.currentPage.id, {
            //         ...state.currentPage,
            //         '_method': 'put'
            //     }).then((res) =>
            //         console.log('saved to db', res)
            //     )
            // });

        },
        removePage({state, commit}, pageId) {
            // commit('removePage', pageId);
            return axios.delete('/' + state.currentTopicId + '/pages/' + pageId)
                .then(res => {
                    commit('updatePagesForTopic', {parentPageId: pageId, pages: res.data});
                    return res.data;
                })
        }
        //Pages
    }

}
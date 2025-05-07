import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import {getItem} from '../js/components/details/crytp'

// import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex)
// const persistedData = new createPersistedState({
//     key:'store',
//     storage: localStorage,
//     reducer : state => ({
//         isLoggedin : state.isLoggedin,
//         loggedInUser : state.loggedInUser
//     })
// })

export default new Vuex.Store({
    state:{
        test:'1',
        overlay:false,
        hideLogin : false,
        user : getItem('user')
    },

    actions:{
       
    },

    mutations:{
       
    },
    getters:{},
    // plugins: [persistedData]
});
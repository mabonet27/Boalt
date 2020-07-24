import Vue from "vue";
import Vuex from "vuex";
import axios from "axios";

Vue.use(Vuex);
const now = new Date();
const store = new Vuex.Store({
  state: {
    url: 'http://boalt-app.lav',
    user: {
      name: 'Operator',
      img: '../assets/operator.png'
    },

    sessions: [],
    currentSessionId: 1,
    filterKey: "",
    filterNotification: "",
    count: 0,
   
  },

  getters : {

    sessions: (state) => (filterKey) =>{
      let result = state.sessions.filter(session =>
        session.user.name.includes(filterKey)
      );
      return result;
    },
    
  },

  

  mutations: {

    incremet(state) {
      state.count++;
    },
    incremetBy(payload) {
      this.state.count += payload.amount;
    },

    INIT_DATA(state , data) {
      console.log(data);
      if (data.length > 0) {
        data.forEach(element => {
            element.img = '../assets/client-1.png'
          });
        state.sessions = data;
        state.currentSessionId = data[0].id;
      }
    },

    SELECT_SESSION(state, id) {
      state.currentSessionId = id;
    },

    SET_FILTER_KEY(state, value) {
      state.filterKey = value;
    },

    SET_NOTIFICATION_KEY(state, value) {
      state.filterNotification = value;
    },

  },

  actions : {

    initData(context) { 
      axios.get('api/users').then( resp => {
        context.commit('INIT_DATA',  resp.data);
        
      }).catch(error => {
        console.log(error);
      }); 
     
    },
  
    selectSession(context, id) { 
      context.commit('SELECT_SESSION', id);
    },
  
    search(context, value) { 
      context.commit('SET_FILTER_KEY', value);
    },

    searchNotification(context, value) { 
      context.commit('SET_NOTIFICATION_KEY', value);
    },
  },

});


export default store;


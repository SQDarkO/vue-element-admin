import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    admin_gid:1
  },
  mutations: {
    changeGid(state,gid){
      this.state.admin_gid = gid;
    }
  },
  actions: {

  }
})

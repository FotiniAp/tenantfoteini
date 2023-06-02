export default {
  namespaced: true,
  state: {
    access_token: "",
    userInfo: null,
    role: '',
    tenant: [],
    tenantVal: ''
  },
  getters: {
    isAuthenticated: state => !!state.access_token,
    getaccess_token: state => state.access_token,
    isRole: state => state.role,
    tenantVal: state => state.tenantVal,
  },
  mutations: {
    setStates(state, model) {
      state.access_token = model.access_token;
      state.userInfo = model.data;
      state.role = model.data.role_name;
    },
    tenantData(state, model) {
      state.tenant = model.data
    },
    logout(state) {
      state.access_token = '';
      state.userInfo = '';
      state.role = '';
    },
    setTenant(state, model) {
      state.tenantVal = model
    }
  },
  actions: {
    login({ commit }, model) {
      commit("setStates", model);
    },
    logout({ commit }) {
      commit("logout");
    },
    tenantData({ commit }, model) {
      commit("tenantData", model);
    },
    setTenant({ commit }, model) {
      commit("setTenant", model)
    }
  }
};

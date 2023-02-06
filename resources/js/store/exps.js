import api from '../api'

const exps = {
  state: {
    list: [],
  },

  getters: {
    expById(state) {
      return (id) => {
        for (const exp of state.list) {
          if (exp.id == id) {
            return exp
          }
        }

        return {}
      }
    },
  },

  mutations: {
    expsSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    expsAll({ commit }) {
      return api.exps.all().then(data => {
        if (data) {
          commit('expsSet', data)
        }

        return data
      })
    },
  },
}

export default exps
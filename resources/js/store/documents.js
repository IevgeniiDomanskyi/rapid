import api from '../api'

const documents = {
  state: {
    list: [],
  },

  getters: {
    documentById(state) {
      return (id) => {
        for (const document of state.list) {
          if (document.id == id) {
            return document
          }
        }

        return {}
      }
    },
  },

  mutations: {
    documentsSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    documentsAll({ commit }, payload) {
      return api.documents.all(payload).then(data => {
        if (data) {
          commit('documentsSet', data)
        }

        return data
      })
    },

    documentsSign({ commit }, payload) {
      return api.documents.sign(payload).then(data => {
        if (data) {
          commit('documentsSet', data)
        }

        return data
      })
    },
  },
}

export default documents
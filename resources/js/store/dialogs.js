import api from '../api'

const dialogs = {
  state: {
    list: [],
    current: {},
  },

  getters: {
    dialogById(state) {
      return (id) => {
        for (const dialog of state.list) {
          if (dialog.id == id) {
            return dialog
          }
        }

        return {}
      }
    },

    dialogByBookId(state) {
      return (bookId) => {
        for (const dialog of state.list) {
          if (dialog.book && dialog.book.id == bookId) {
            return dialog
          }
        }

        return []
      }
    },

    dialogByEnquiryId(state) {
      return (enquiryId) => {
        for (const dialog of state.list) {
          if (dialog.enquiry && dialog.enquiry.id == enquiryId) {
            return dialog
          }
        }

        return []
      }
    },
  },

  mutations: {
    dialogsSet(state, payload) {
      state.list = payload
    },

    dialogsCurrentSet(state, payload) {
      state.current = payload
    },
  },

  actions: {
    dialogsAll({ commit }, payload) {
      return api.dialogs.all(payload).then(data => {
        if (data) {
          commit('dialogsSet', data)
        }

        return data
      })
    },
    
    dialogsGet({ commit }, payload) {
      return api.dialogs.get(payload).then(data => {
        if (data) {
          commit('dialogsCurrentSet', data)
        }

        return data
      })
    },

    dialogsMessageRead({}, payload) {
      return api.dialogs.messageRead(payload).then(data => {
        return data
      })
    },

    dialogsAddCall({ }, payload) {
      return api.dialogs.addCall(payload).then(data => {
        return data
      })
    },

    dialogsAddNotes({ }, payload) {
      return api.dialogs.addNotes(payload).then(data => {
        return data
      })
    },

    dialogsAddMessage({ commit }, payload) {
      return api.dialogs.addMessage(payload).then(data => {
        if (data) {
          commit('dialogsCurrentSet', data)
        }

        return data
      })
    },

    dialogsClose({ commit }, payload) {
      return api.dialogs.close(payload).then(data => {
        if (data) {
          commit('dialogsCurrentSet', data)
        }

        return data
      })
    },
  },
}

export default dialogs
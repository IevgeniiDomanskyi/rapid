const messages = {
  state: {
    list: [],
  },

  mutations: {
    messagesSet(state, payload) {
      state.list.push(payload)
    },

    messagesClear(state) {
      state.list = []
    },
  },
}

export default messages
import api from '../api'

const vouchers = {
  state: {
    list: [],
    codes: [],
    item: {},
    users: [],
  },

  getters: {
    voucherById(state) {
      return (id) => {
        for (const coach of state.list) {
          if (coach.id == id) {
            return coach
          }
        }

        return {}
      }
    },
  },

  mutations: {
    vouchersSet(state, payload) {
      state.list = payload
    },

    vouchersCodesSet(state, payload) {
      state.codes = payload
    },

    vouchersItemSet(state, payload) {
      state.item = payload
    },

    vouchersUsersSet(state, payload) {
      state.users = payload
    },
  },

  actions: {
    vouchersAll({ commit }) {
      return api.vouchers.all().then(data => {
        if (data) {
          commit('vouchersSet', data)
        }

        return data
      })
    },

    vouchersRemove({ commit }, payload) {
      return api.vouchers.remove(payload).then(data => {
        if (data) {
          commit('vouchersSet', data)
        }

        return data
      })
    },

    vouchersUpload({ commit }, payload) {
      return api.vouchers.upload(payload).then(data => {
        if (data) {
          commit('vouchersCodesSet', data)
        }

        return data
      })
    },

    vouchersSave({ commit }, payload) {
      return api.vouchers.save(payload).then(data => {
        if (data) {
          commit('vouchersSet', data)
        }

        return data
      })
    },

    vouchersCheck({ }, payload) {
      return api.vouchers.check(payload).then(data => {
        return data
      })
    },

    vouchersGet({ commit }, payload) {
      return api.vouchers.get(payload).then(data => {
        if (data) {
          commit('vouchersItemSet', data)
        }

        return data
      })
    },

    vouchersUsersAll({ commit }) {
      return api.vouchers.users().then(data => {
        if (data) {
          commit('vouchersUsersSet', data)
        }

        return data
      })
    },
  },
}

export default vouchers
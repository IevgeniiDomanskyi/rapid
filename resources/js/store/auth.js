import Vue from 'vue'
import VueCookie from 'vue-cookie'
import api from '../api'

const auth = {
  state: {
    token: VueCookie.get('token') || null,
    me: {},
  },

  getters: {
    customer(state) {
      if (state.me && state.me.role == 0) {
        return state.me
      }

      return {}
    },
  },

  mutations: {
    authTokenSet(state, payload) {
      state.token = payload.token

      let options = {}
      if (payload.remember) {
        options.expires = '1Y'
      }

      Vue.cookie.set('token', payload.token, options)
    },

    authTokenClear(state) {
      state.token = null

      Vue.cookie.delete('token')
    },

    authMeSet(state, payload) {
      state.me = payload
    },
  },

  actions: {
    authMe({ commit }) {
      return api.auth.me().then(data => {
        if (data) {
          commit('authMeSet', data)
        }

        return data
      })
    },

    authLogin({ commit }, payload) {
      return api.auth.login(payload).then(data => {
        if (data) {
          commit('authMeSet', data)

          if (data.token) {
            commit('authTokenSet', {
              token: data.token,
              remember: payload.remember,
            })
          }
        }

        return data
      })
    },

    authMagic({ commit }, payload) {
      return api.auth.magic(payload).then(data => {
        if (data) {
          commit('authMeSet', data)

          if (data.token) {
            commit('authTokenSet', {
              token: data.token,
            })
          }
        }

        return data
      })
    },

    authRegister({ commit }, payload) {
      return api.auth.register(payload).then(data => {
        if (data) {
          commit('authMeSet', data)

          if (data.token) {
            commit('authTokenSet', {
              token: data.token,
            })
          }
        }

        return data
      })
    },

    authBook({ commit }, payload) {
      return api.auth.book(payload).then(data => {
        if (data) {
          commit('authMeSet', data)

          if (data.token) {
            commit('authTokenSet', {
              token: data.token,
            })
          }
        }

        return data
      })
    },

    authRecovery({ }, payload) {
      return api.auth.recovery(payload).then(data => {
        return data
      })
    },

    authActivate({ }, payload) {
      return api.auth.activate(payload).then(data => {
        return data
      })
    },

    authLogout({ commit }) {
      commit('authMeSet', {})
      commit('authTokenClear')
      commit('bookTokenClear', null, { root: true })
    },

    authProfile({ commit }, payload) {
      return api.auth.profile(payload).then(data => {
        if (data) {
          commit('authMeSet', data)
        }

        return data
      })
    },

    authPassword({ commit }, payload) {
      return api.auth.password(payload).then(data => {
        return data
      })
    },
  },
}

export default auth
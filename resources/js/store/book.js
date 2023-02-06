import Vue from 'vue'
import VueCookie from 'vue-cookie'
import api from '../api'

const book = {
  state: {
    list: [],
    enquiries: [],
    pay: [],
    current: {},

    step: 1,
    max: 1,
    options: {
      exp: 0,
      course: 0,
      level: 0,
      terms: false,
      card: 0,
      plan: 0,
      instalment: 2,
      county: 0,
      message: '',
      eForm: {},
    },
    token: VueCookie.get('book-token') || null,

    complete: false,
  },

  getters: {
    stepTitle() {
      return (step, enquiry) => {
        switch (step) {
          case 1: return 'Pick your course'
          case 2: return 'Choose your level'
          case 3: return 'Enter your details'
          case 4: return enquiry ? 'Write your message' : 'Terms and conditions and Rider declaration'
          case 5: return enquiry ? 'Send your enquiry' : 'Payment method'
          case 6: return 'Your payment plan'
          case 7: return 'Where would you like to do your course?'
          case 8: return 'Book now'
        }
      }
    },
  },

  mutations: {
    bookMaxSet(state, payload) {
      state.max = Math.max(payload, state.max)
    },

    bookStepSet(state, payload) {
      if (payload.step) {
        state.step = payload.step
      }

      if (payload.options) {
        state.options = { ...state.options, ...payload.options }
      }
    },

    bookTokenSet(state) {
      const pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      let token = ''
      for (let i = 0; i < 16; i++) {
        token += pool.charAt(Math.floor(Math.random() * pool.length));
      }

      state.token = token

      let options = { expires: 1 }

      Vue.cookie.set('book-token', token, options)
    },

    bookTokenClear(state) {
      state.token = null

      Vue.cookie.delete('book-token')
    },

    bookCompleteSet(state) {
      state.complete = true
    },

    bookStoreClear(state) {
      state.step = 1
      state.max = 1
      state.options.exp = 0
      state.options.course = 0
      state.options.level = 0
      state.options.terms = false
      state.options.card = 0
      state.options.plan = 0
      state.options.instalment = 2
      state.options.county = 0
      state.options.message = ''
      state.options.eForm = {}
    },

    bookSet(state, payload) {
      state.list = payload
    },

    enquirySet(state, payload) {
      state.enquiries = payload
    },

    bookCurrentSet(state, payload) {
      state.current = payload
    },

    bookPaySet(state, payload) {
      state.pay = payload
    },
  },

  actions: {
    tempGet({ state, commit, rootState }) {
      if (state.token) {
        return api.book.tempGet({ token: state.token }).then(data => {
          if (data) {
            if (!rootState.auth.me.id || (rootState.auth.me.id && rootState.auth.me.role > 0)) {
              if (data.step > 3) {
                data.step = 3
              }

              if (data.max > 3) {
                data.max = 3
              }
            }

            commit('bookStepSet', data)
            commit('bookMaxSet', data.max)
          }
          return data
        })
      }
    },

    bookStep({ state, commit }, payload) {
      if ( ! state.token) {
        commit('bookTokenSet')
      }

      commit('bookStepSet', payload)
      commit('bookMaxSet', payload.step)

      return api.book.temp(state).then(data => {
        return data
      })
    },

    bookSave({ state, commit }) {
      return api.book.save({ token: state.token }).then(data => {
        if (data) {
          commit('bookCompleteSet')
          commit('bookTokenClear')
          commit('bookStoreClear')
        }
        return data
      })
    },

    enquirySave({ state, commit }) {
      return api.book.enquiry({ token: state.token }).then(data => {
        if (data) {
          commit('bookCompleteSet')
          commit('bookTokenClear')
          commit('bookStoreClear')
        }
        return data
      })
    },

    enquiryAll({ state, commit }) {
      return api.book.enquiryAll().then(data => {
        if (data) {
          commit('enquirySet', data)
        }
        return data
      })
    },

    bookAll({ commit }) {
      return api.book.all().then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookGet({ commit }, payload) {
      return api.book.get(payload).then(data => {
        if (data) {
          commit('bookCurrentSet', data)
        }

        return data
      })
    },

    bookForPay({ commit }) {
      return api.book.forPay().then(data => {
        if (data) {
          commit('bookPaySet', data)
        }

        return data
      })
    },

    bookAssign({ commit }, payload) {
      return api.book.assign(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookTrackDateDefine({ commit }, payload) {
      return api.book.trackDateDefine(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookTrackPay({ commit }, payload) {
      return api.book.trackPay(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookRoadDateDefine({ commit }, payload) {
      return api.book.roadDateDefine(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookAgreeRoadDates({ commit }, payload) {
      return api.book.agreeRoadDates(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookRequestPayment({ commit }, payload) {
      return api.book.requestPayment(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookPay({ commit }, payload) {
      return api.book.pay(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },

    bookCreate({ commit }, payload) {
      return api.book.create(payload).then(data => {
        if (data) {
          commit('bookSet', data)
        }

        return data
      })
    },
  },
}

export default book
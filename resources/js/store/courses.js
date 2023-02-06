import api from '../api'

const courses = {
  state: {
    list: [],
  },

  getters: {
    courseById(state) {
      return (id) => {
        for (const course of state.list) {
          if (course.id == id) {
            return course
          }
        }

        return {}
      }
    },
  },

  mutations: {
    coursesSet(state, payload) {
      state.list = payload
    },
  },

  actions: {
    coursesAll({ commit }) {
      return api.courses.all().then(data => {
        if (data) {
          commit('coursesSet', data)
        }

        return data
      })
    },
  },
}

export default courses
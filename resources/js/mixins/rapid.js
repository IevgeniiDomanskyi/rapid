import { mapState } from 'vuex'

const Rapid = {
  computed: {
    ...mapState({
      me: state => state.auth.me,
    }),

    isCustomer() {
      return this.me.role == 0
    },

    isCoach() {
      return this.me.role == 1
    },

    isAdmin() {
      return this.me.role == 2
    },
  },

  methods: {
    sortByDate(array, field, asc = true) {
      return array.sort((a, b) => {
        return asc ? (this.makeDate(a[field]) - this.makeDate(b[field])) : this.makeDate(b[field]) - this.makeDate(a[field])
      })
    },

    makeDate(date) {
      const temp = date.split('/')
      return new Date(temp[2], temp[1], temp[0])
    },
  },
}

export default Rapid
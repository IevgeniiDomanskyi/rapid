import { mapState, mapGetters, mapActions } from 'vuex'

export default {
  data: () => ({
    isBookLoading: false,
  }),

  computed: {
    ...mapState({
      user: state => state.auth.me,
      step: state => state.book.step,
      max: state => state.book.max,
      options: state => state.book.options,
      eStep: state => state.events.step,
      eMax: state => state.events.max,
      eOptions: state => state.events.options,
      tStep: state => state.trackDays.step,
      tMax: state => state.trackDays.max,
      tOptions: state => state.trackDays.options,
      token: state => state.auth.token,
      exps: state => state.exps.list,
      courses: state => state.courses.list,
      regions: state => state.regions.list,
      complete: state => state.book.complete,
    }),

    ...mapGetters([
      'customer',
      'expById',
      'courseById',
      'regionById',
      'postcodeById',
      'cardById',
    ]),
  },

  methods: {
    ...mapActions([
      'tempGet',
      'authMe',
      'authBook',
      'expsAll',
      'coursesAll',
      'regionsAll',
      'usersCardAdd',
      'usersCardAll',
      'usersCardTest',
      'bookSave',
      'enquirySave',
      'eventsBook',
      'eventsEnquiry',
      'trackDaysBook',
      'trackDaysEnquiry',
    ]),

    onBook() {
      this.isBookLoading = true

      this.bookSave().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },

    onEnquiry() {
      this.isBookLoading = true

      this.enquirySave().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },

    onEvent() {
      this.isBookLoading = true

      this.eventsBook().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },

    onEventEnquiry() {
      this.isBookLoading = true

      this.eventsEnquiry().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },

    onTrackDay() {
      this.isBookLoading = true

      this.trackDaysBook().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },

    onTrackDayEnquiry() {
      this.isBookLoading = true

      this.trackDaysEnquiry().then(data => {
        if (data) {
          window.scrollTo(0, 0)
        }

        this.isBookLoading = false
        return data
      })
    },
  },
}
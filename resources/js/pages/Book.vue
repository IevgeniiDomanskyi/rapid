<template>
  <v-app
    class="book"
  >
    <book-header />

    <div class="content">
      <div
        v-if=" ! complete"
        class="banner"
      >
        <div class="banner-inner">
          <h3>BOOKING</h3>

          <p>
            Great, now you’re on the road to booking a Rapid course. Places are limited, so to help us arrange
            the best date and location that work for you, just complete the details below.
            No payment will be taken until your course is fully confirmed with your Pro Coach.
          </p>
        </div>
      </div>

      <!-- <div
        v-else
        class="banner"
      >
        <div class="banner-inner">
          <h3>THANK YOU</h3>

          <p>
            Thank you for your booking. No payment has been taken yet. You will now be assigned a Rapid Training
            instructor to agree your course dates. Stay Tuned!
          </p>

          <book-button
            label="Go To Home"
            color="red"
            @click="onHome"
          />
        </div>
      </div> -->

      <div
        v-if=" ! complete"
        :class="{ loading: ! pageLoaded }"
        class="body-content"
      >
        <div
          :class="{ isReady: pageLoaded }"
          class="body"
        >
          <book-sidebar />

          <book-content />

          <book-progress />
        </div>

        <rapid-loader
          v-if=" ! pageLoaded"
          absolute
        />
      </div>

      <!-- <div
        v-else
        class="book-space"
      /> -->
    </div>

    <book-footer />
  </v-app>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import BookHeader from '../components/BookHeader'
  import BookFooter from '../components/BookFooter'
  import BookSidebar from '../components/BookSidebar'
  import BookContent from '../components/BookContent'
  import BookProgress from '../components/BookProgress'
  import RapidLoader from '../components/RapidLoader'
  import BookButton from '../components/BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'Book',

    mixins: [RapidBook],

    components: {
      BookHeader,
      BookFooter,
      BookSidebar,
      BookContent,
      BookProgress,
      RapidLoader,
      BookButton,
    },

    data: () => ({
      pageLoaded: false,
    }),

    computed: {
      ...mapState({
        step: state => state.book.step,
        voucher: state => state.book.options.voucher,
      }),
    },

    created() {
      if (this.token) {
        this.authMe().then(data => {
          this.tempGet().then(data => {
            this.afterTempGet()
          })
        })
      } else {
        this.tempGet().then(data => {
          this.afterTempGet()
        })
      }
    },

    watch: {
      complete(val) {
        if (val) {
          window.location.href = 'https://www.rapidtraining.co.uk/thank-you-for-booking'
        }
      }
    },

    methods: {
      ...mapActions([
        'bookStep',
        'vouchersGet',
      ]),

      afterTempGet() {
        if ( !! this.voucher) {
          this.vouchersGet({
            code: this.voucher
          })
        }

        this.expsAll().then(data => {
          this.coursesAll().then(data => {
            this.regionsAll().then(data => {
              const { courseId, level } = this.$router.history.current.params
              if (courseId) {
                const course = this.courseById(courseId)
                let levelObj = {}
                if (course.id) {
                  for (const item of course.levels) {
                    if (item.level == level) {
                      levelObj = item
                    }
                  }

                  if (levelObj.id && this.step <= 3) {
                    this.bookStep({
                      step: 3,
                      options: {
                        course: course.id,
                        level: levelObj.id,
                      },
                    }).then(data => {
                      this.pageLoaded = true
                    })
                  } else {
                    this.pageLoaded = true
                  }
                } else {
                  this.pageLoaded = true
                }
              } else {
                this.pageLoaded = true
              }
            })
          })
        })
      },

      onHome() {
        window.location.href = 'https://www.rapidtraining.co.uk'
      },
    },
  }
</script>

<style lang="scss">
  @import '../../sass/book.scss';

  .content {
    .banner {
      width: 100%;
      height: 400px;
      border-top: solid #b1b1b3 1px;
      background: url('../assets/img/back.jpg') center no-repeat;
      background-size: cover;
      position: relative;
      z-index: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 10px 30px;

      &-inner {
        background: rgba(255, 255, 255, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 30px;
        border-radius: 10px;
      }

      &:before {
        content: '';
        display: block;
        position: absolute;
        z-index: -1;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
      }

      h3 {
        font-family: FuturaPTHeavy, sans-serif;
        font-size: 50px;
        text-transform: uppercase;
        color: #303030;
        letter-spacing: 6px;
        margin-bottom: 30px;
      }

      p {
        font-family: FuturaPTDemi, sans-serif;
        font-size: 18px;
        color: #303030;
        max-width: 690px;
        text-align: justify;
        text-align-last: center;
        line-height: 1.4;
      }
    }

    .body-content {
      position: relative;
      z-index: 0;

      &.loading {
        max-height: 500px;
        overflow: hidden;
      }
    }

    .body {
      padding: 20px;
      display: flex;
      align-items: flex-start;
      margin: 0 auto;
      justify-content: space-between;
      max-width: 1400px;
      opacity: 0;
      transition: all 0.3s linear 0s;

      &.isReady {
        opacity: 1;
      }
    }
  }

  .book-space {
    height: 40px;
  }

  @media (max-width: 768px) {
    .body {
      flex-direction: column;
    }

    .content {
      .banner {
        h3 {
          font-size: 35px;
          letter-spacing: 4px;
        }
      }
    }
  }
</style>
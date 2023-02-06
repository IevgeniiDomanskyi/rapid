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
          <h3 class="enquiry-title">Send us some details and we’ll get straight back to you</h3>

          <p>
            If you’re into performance riding and you want to challenge yourself to see how good you can be, our Rapid programmes have been designed for you. Our Rapid Pro Coaches are the best in the business and can get you to where you want to be, fast. To take the first step on your Rapid journey, please send us some brief details and we’ll get back to you, rapid.
          </p>
        </div>
      </div>

      <div
        v-else
        class="banner"
      >
        <div class="banner-inner">
          <h3 class="enquiry-title">THANK YOU</h3>

          <p>
            Thank you for your message, someone will be in touch within 2 days.
          </p>

          <book-button
            label="Go To Home"
            color="red"
            @click="onHome"
          />
        </div>
      </div>

      <div
        v-if=" ! complete"
        :class="{ loading: ! pageLoaded }"
        class="body-content"
      >
        <div
          :class="{ isReady: pageLoaded }"
          class="body"
        >
          <book-sidebar
            enquiry
          />

          <book-content
            enquiry
          />

          <book-progress
            enquiry
          />
        </div>

        <rapid-loader
          v-if=" ! pageLoaded"
          absolute
        />
      </div>

      <div
        v-else
        class="book-space"
      />
    </div>

    <book-footer />
  </v-app>
</template>

<script>
  import { mapActions } from 'vuex'
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

    methods: {
      ...mapActions([
        'bookStep',
        'tempGet',
      ]),

      afterTempGet() {
        this.expsAll().then(data => {
          this.coursesAll().then(data => {
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
      },

      onHome() {
        window.location.href = 'https://www.rapidtraining.co.uk/'
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

  .enquiry-title {
    line-height: 1;
    text-align: center;
    max-width: 730px;
  }

  @media (max-width: 768px) {
    .content {
      .banner {
        h3 {
          letter-spacing: 4px;
        }

        .enquiry-title {
          font-size: 20px;
        }

        p {
          font-size: 14px;
        }
      }
    }
  }
</style>
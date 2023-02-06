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
        <div
          class="banner-inner"
        >
          <h3>{{ currentEvent.name }}</h3>

          <p>
            {{ currentEvent.from }} - {{ currentEvent.to }}
          </p>

          <p
            v-if="currentEvent.region"
          >
            {{ currentEvent.region.title }}
          </p>
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
          <event-sidebar
            enquiry
          />

          <event-content
            enquiry
          />

          <event-progress
            enquiry
          />
        </div>

        <rapid-loader
          v-if=" ! pageLoaded"
          absolute
        />
      </div>
    </div>

    <book-footer />
  </v-app>
</template>

<script>
  import { mapState, mapActions } from 'vuex'
  import BookHeader from '../components/BookHeader'
  import BookFooter from '../components/BookFooter'
  import EventSidebar from '../components/EventSidebar'
  import EventContent from '../components/EventContent'
  import EventProgress from '../components/EventProgress'
  import RapidLoader from '../components/RapidLoader'
  import BookButton from '../components/BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'EventEnquiry',

    mixins: [RapidBook],

    components: {
      BookHeader,
      BookFooter,
      EventSidebar,
      EventContent,
      EventProgress,
      RapidLoader,
      BookButton,
    },

    data: () => ({
      pageLoaded: false,
    }),

    computed: {
      ...mapState({
        step: state => state.events.step,
        currentEvent: state => state.events.current,
      }),
    },

    created() {
      this.eventsGet({id: this.$route.params.eventId})

      if (this.token) {
        this.authMe().then(() => {
          this.eventsTempGet().then(() => {
            this.pageLoaded = true
          })
        })
      } else {
        this.eventsTempGet().then(() => {
          this.pageLoaded = true
        })
      }
    },

    methods: {
      ...mapActions([
        'eventsGet',
        'eventsTempGet',
      ]),

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
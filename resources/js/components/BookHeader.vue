<template>
  <div>
    <header class="book-header">
      <div class="book-header__inner">
        <div class="book-header__inner-left">
          <div class="book-header__inner-left_tagline">
            HIGH PERFORMANCE COACHING
          </div>
        </div>
        
        <div class="book-header__inner-center">
          <a href="https://www.rapidtraining.co.uk" class="book-header__inner-center_logo">
            <img src="//static1.squarespace.com/static/5c3ca4562714e564a6ae7f97/t/5cdaf163f4e1fcb564be5abc/1593279603338/?format=1500w" alt="RAPID" class="book-header__inner-center_logo-img" />
          </a>
        </div>
        
        <div class="book-header__inner-right">
          <nav class="book-header__nav">
            <div class="book-header__nav-inner">
              <span class="book-header__item book-header__item-folder">
                <a href="https://www.rapidtraining.co.uk/our-courses" class="book-header__item-folder_title">Courses</a>
                <span class="book-header__item-folder_box">
                  <a href="https://www.rapidtraining.co.uk/bikemaster" class="book-header__item-folder_box-item">Bikemaster</a>
                  <a href="https://www.rapidtraining.co.uk/roadmaster" class="book-header__item-folder_box-item">Roadmaster</a>  
                  <a href="https://www.rapidtraining.co.uk/bespoke-courses" class="book-header__item-folder_box-item">Bespoke</a>
                  <a href="https://www.rapidtraining.co.uk/track-days" class="book-header__item-folder_box-item">Track Days</a>
                  <a href="https://www.rapidtraining.co.uk/blyton-park" class="book-header__item-folder_box-item">Blyton Park</a>
                  <a href="https://www.rapidtraining.co.uk/gift-vouchers" class="book-header__item-folder_box-item">Gift Vouchers</a>
                </span>
              </span><a href="https://www.rapidtraining.co.uk/motorcycle-tours" class="book-header__item">
                Tours
              </a><a href="https://www.rapidtraining.co.uk/members-area" class="book-header__item">
                Community
              </a><span class="book-header__item book-header__item-folder">
                <a href="https://www.rapidtraining.co.uk/news-and-stories" class="book-header__item-folder_title">News</a>
                <span class="book-header__item-folder_box">
                  <a href="https://www.rapidtraining.co.uk/latest-news" class="book-header__item-folder_box-item">News</a>
                  <a href="https://www.rapidtraining.co.uk/stories" class="book-header__item-folder_box-item">Stories</a>
                </span>
              </span><span class="book-header__item book-header__item-folder">
                <a href="https://www.rapidtraining.co.uk/team" class="book-header__item-folder_title">Team</a>
                <span class="book-header__item-folder_box">
                  <a href="https://www.rapidtraining.co.uk/about" class="book-header__item-folder_box-item">About</a>
                  <a href="https://www.rapidtraining.co.uk/rapid-team" class="book-header__item-folder_box-item">Rapid Team</a>
                  <a href="https://www.rapidtraining.co.uk/join-the-team" class="book-header__item-folder_box-item">Recruitment</a>
                </span>
              </span><a href="https://www.rapidtraining.co.uk/racing" class="book-header__item">
                Racing
              </a><a href="https://www.rapidtraining.co.uk/contact" class="book-header__item">
                Contact
              </a>
            </div>
          </nav>
        </div>
      </div>
    </header>

    <div class="mobile-header">
      <div class="mobile-header__bar">
        <div class="mobile-header__bar-left">
        </div>

        <div class="mobile-header__bar-center">
          <a href="https://www.rapidtraining.co.uk/" class="mobile-header__bar-center_logo">
            <img src="//static1.squarespace.com/static/5c3ca4562714e564a6ae7f97/t/5cdaf163f4e1fcb564be5abc/1593599600061/?format=1500w" alt="RAPID" />
          </a>
        </div>

        <div class="mobile-header__bar-right">
          <button class="mobile-header__bar-right_menu" @click="onMenuOpen">
            <svg class="Icon Icon--hamburger" viewBox="0 0 24 18">
              <line fill="none" stroke-miterlimit="10" x1="0" y1="1.5" x2="24" y2="1.5"/>
              <line fill="none" stroke-miterlimit="10" x1="0" y1="8.5" x2="24" y2="8.5"/>
              <line fill="none" stroke-miterlimit="10" x1="0" y1="15.5" x2="24" y2="15.5"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="mobile-header__overlay" :class="{ active: isMenuOpened }">
        <div class="mobile-header__menu" :class="{ 'has-active-folder': isFolderOpened }">
          <div class="mobile-header__menu-main">
            <nav class="mobile-header__menu-main_links">
              <button class="mobile-header__menu-main_item mobile-header__menu-main_folder" @click="onFolderOpen">
                <span class="mobile-header__menu-main_folder-label">Courses</span>
              </button>

              <a href="https://www.rapidtraining.co.uk/motorcycle-tours" class="mobile-header__menu-main_item">
                Motorcycle Tours
              </a>

              <a href="https://www.rapidtraining.co.uk/hints-and-tips" class="mobile-header__menu-main_item">
                Hints and Tips
              </a>

              <a href="/" class="mobile-header__menu-main_item" :class="{ active: $router.history.current.path == '/' }">
                Booking
              </a>

              <a href="https://www.rapidtraining.co.uk/latest-news" class="mobile-header__menu-main_item">
                Latest News
              </a>

              <a href="https://www.rapidtraining.co.uk/about" class="mobile-header__menu-main_item">
                About
              </a>

              <a href="/enquiry" class="mobile-header__menu-main_item" :class="{ active: $router.history.current.path == '/enquiry' }">
                Enquiries
              </a>
            </nav>
          </div>

          <div class="mobile-header__menu-second">
            <div class="mobile-header__menu-second_links" :class="{ active: isFolderOpened }">
              <button class="mobile-header__menu-main_item mobile-header__menu-main_back" @click="onFolderClose">
                <span class="mobile-header__menu-main_item-label">Back</span>
              </button>

              <a href="https://www.rapidtraining.co.uk/bikemaster" class="mobile-header__menu-main_item">
                Bikemaster™
              </a>

              <a href="https://www.rapidtraining.co.uk/roadmaster" class="mobile-header__menu-main_item">
                Roadmaster™
              </a>

              <a href="https://www.rapidtraining.co.uk/bespoke-courses" class="mobile-header__menu-main_item">
                Bespoke
              </a>
            </div>
          </div>
        </div>

        <button class="mobile-header__overlay-close" @click="onMenuClose">
          <svg class="mobile-header__overlay-close_icon" viewBox="0 0 16 16">
            <line fill="none" stroke-miterlimit="10" x1="1.4" y1="1.4" x2="14.4" y2="14.4"/>
            <line fill="none" stroke-miterlimit="10" x1="1.4" y1="14.4" x2="14.4" y2="1.4"/>
          </svg>
        </button>

        <div class="mobile-header__overlay-back"></div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'BookHeader',

    data: () => ({
      isMenuOpened: false,
      isFolderOpened: false,
    }),

    methods: {
      onMenuOpen() {
        this.isMenuOpened = true
      },

      onMenuClose() {
        this.isMenuOpened = false
      },

      onFolderOpen() {
        this.isFolderOpened = true
      },

      onFolderClose() {
        this.isFolderOpened = false
      },
    },
  }
</script>

<style lang="scss" scoped>
  .book-header {
    background: #fff;

    &__inner {
      display: flex;
      align-items: center;
      text-align: center;
      justify-content: center;
      max-width: 1920px;
      margin: 0 auto;
      padding: 10px 67.5px;
      box-sizing: border-box;

      &-left {
        flex: 1 1 0px;
        display: flex;
        align-items: center;
        min-width: 0;
        max-width: 100%;

        &_tagline {
          padding: 20px 7.5px;
          max-width: 40em;
          font-family: FuturaPTDemi, Arial, Helvetica, sans-serif;
          font-weight: 600;
          font-size: 14px;
          font-style: normal;
          letter-spacing: 0.08em;
          line-height: 1.5em;
          text-transform: none;
          color: #cc2828;
          box-sizing: border-box;
          flex: 0 0 auto;
        }
      }

      &-center {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        text-align: center;
        justify-content: center;
        min-width: 0;
        max-width: 100%;

        @media (max-width: 1680px) {
          &-center {
            flex: 1 1 0;
          }
        }

        &_logo {
          padding: 20px 7.5px;
          line-height: 0;
          box-sizing: border-box;
          flex: 0 0 auto;

          &-img {
            width: 100%;
            max-width: 211px;
          }
        }
      }

      &-right {
        flex: 1 1 0px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
        justify-content: flex-end;
        min-width: 0;
        max-width: 100%;
      }
    }

    &__nav {
      font-family: FuturaPTHeavy, Arial, Helvetica, sans-serif;
      font-weight: 700;
      font-style: normal;
      font-size: 12px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      padding: 20px 7.5px;
      box-sizing: border-box;
      white-space: normal;
      line-height: 1em;
      display: block;
      flex: 0 1 auto;
      max-width: 100%;
      text-align: right;

      &-inner {
        margin: -0.618em -0.6em;
      }
    }

    &__item {
      margin: 0 0.6em;
      padding: 0.618em 0;
      color: #0d0d0d;
      display: inline-block;
      vertical-align: middle;
      transition: color 170ms ease-in-out, background-color 170ms ease-in-out;

      &.active {
        color: #e90707;
      }

      &-folder {
        position: relative;

        &_title {
          display: inline;
          color: #0d0d0d;
        }

        &_box {
          background-color: rgba(93, 91, 91, 0.55);
          box-sizing: border-box;
          position: absolute;
          top: 100%;
          left: -99999px;
          z-index: 1002;
          margin-left: -1em;
          padding: 1em;
          opacity: 0;
          white-space: nowrap;
          text-align: left;
          transform: translate3d(0, 10px, 0);
          transition: left 0s 0.2s linear, transform 0s 0.2s linear, opacity 0.2s ease-out;

          &-item {
            color: rgba(253, 252, 252, 0.99);
            display: block;
            padding: 0.382em;
            transition: color 170ms ease-in-out, background-color 170ms ease-in-out;

            &:hover {
              color: #e90707;
            }
          }
        }

        &:hover {
          .book-header__item-folder_box {
            background-color: rgba(93,91,91,.55);
            -webkit-font-smoothing: antialiased;
            left: 0;
            opacity: 1;
            transform: translate3d(0,0,0);
            transition: left 0s linear,transform .2s ease-out,opacity .2s ease-out;
          }
        }
      }

      &:hover {
        color: #e90707;
      }
    }
  }

  .mobile-header {
    display: none;

    &__bar {
      background: #fff;
      padding: 8px 12px;
      box-sizing: border-box;
      width: 100%;
      display: flex;
      -webkit-box-align: center;
      align-items: center;
      text-align: center;
      -webkit-box-pack: center;
      justify-content: center;

      &-left {
        display: flex;
        -webkit-box-flex: 1;
        flex: 1 1 0px;
        text-align: left;
        -webkit-box-pack: start;
        justify-content: flex-start;
        -webkit-box-align: center;
        align-items: center;
        min-width: 0;
        max-width: 100%;
      }

      &-center {
        flex: 0 0 auto;
        -webkit-box-flex: 1;
        display: flex;
        text-align: center;
        -webkit-box-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        align-items: center;
        min-width: 0;
        max-width: 100%;

        &_logo {
          line-height: 0;
          padding: 8px;
          box-sizing: border-box;
          -webkit-box-flex: 1;
          flex: 0 0 auto;

          img {
            width: 175px;
            border: 0;
          }
        }
      }

      &-right {
        -webkit-box-flex: 1;
        flex: 1 1 0px;
        display: flex;
        text-align: right;
        -webkit-box-pack: end;
        justify-content: flex-end;
        -webkit-box-align: center;
        align-items: center;
        min-width: 0;
        max-width: 100%;

        &_menu {
          user-select: none;
          stroke: #cc2929;
          padding: 8px;
          line-height: 0;
          background-color: transparent;
          text-transform: inherit;
          letter-spacing: inherit;
          outline: 0;
          border: 0;
          box-sizing: border-box;
          -webkit-box-flex: 1;
          flex: 0 0 auto;
          -webkit-appearance: button;
          cursor: pointer;
          overflow: visible;

          svg {
            stroke-width: 1;
            width: 24px;
            height: 18px;
          }
        }
      }
    }

    &__overlay {
      position: fixed;
      top: 0;
      left: -99999px;
      bottom: 0;
      z-index: 1010;
      width: 100%;
      transition: left 0s 525ms, background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;

      &-close {
        outline: 0;
        border: 0;
        padding: 0;
        background-color: transparent;
        text-transform: inherit;
        letter-spacing: inherit;
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
        padding: 22px;
        line-height: 0;
        stroke: transparent;
        -webkit-transition: stroke 350ms cubic-bezier(.55,0,.1,1),background-color 350ms cubic-bezier(.55,0,.1,1);
        -moz-transition: stroke 350ms cubic-bezier(.55,0,.1,1),background-color 350ms cubic-bezier(.55,0,.1,1);
        -ms-transition: stroke 350ms cubic-bezier(.55,0,.1,1),background-color 350ms cubic-bezier(.55,0,.1,1);
        -o-transition: stroke 350ms cubic-bezier(.55,0,.1,1),background-color 350ms cubic-bezier(.55,0,.1,1);
        transition: stroke 350ms cubic-bezier(.55,0,.1,1),background-color 350ms cubic-bezier(.55,0,.1,1);

        &_icon {
          stroke-width: 1;
          display: block;
          width: 16px;
          height: 16px;
          overflow: hidden;
        }
      }

      &.active {
        left: 0;
        background-color: #fff;
        -webkit-transition: left 0s,background-color 350ms cubic-bezier(.55,0,.1,1);
        -moz-transition: left 0s,background-color 350ms cubic-bezier(.55,0,.1,1);
        -ms-transition: left 0s,background-color 350ms cubic-bezier(.55,0,.1,1);
        -o-transition: left 0s,background-color 350ms cubic-bezier(.55,0,.1,1);
        transition: left 0s,background-color 350ms cubic-bezier(.55,0,.1,1);

        .mobile-header__menu {
          transform: translate3d(0,0,0);
          transition: transform 350ms cubic-bezier(.55,0,.1,1);
        }

        .mobile-header__overlay-close {
          background-color: #000;
          stroke: #fff;
          -webkit-transition: stroke 350ms cubic-bezier(.55,0,.1,1) 175ms,background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;
          -moz-transition: stroke 350ms cubic-bezier(.55,0,.1,1) 175ms,background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;
          -ms-transition: stroke 350ms cubic-bezier(.55,0,.1,1) 175ms,background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;
          -o-transition: stroke 350ms cubic-bezier(.55,0,.1,1) 175ms,background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;
          transition: stroke 350ms cubic-bezier(.55,0,.1,1) 175ms,background-color 350ms cubic-bezier(.55,0,.1,1) 175ms;
        }
      }
    }

    &__menu {
      position: absolute;
      top: 0;
      bottom: 0;
      z-index: 2;
      width: 80%;
      width: calc(100% - 60px );
      overflow: hidden;
      background-color: #cc2929;
      -webkit-font-smoothing: antialiased;
      -webkit-transform: translate3d(-100%,0,0);
      -moz-transform: translate3d(-100%,0,0);
      -ms-transform: translate3d(-100%,0,0);
      -o-transform: translate3d(-100%,0,0);
      transform: translate3d(-100%,0,0);
      -webkit-transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      -webkit-transition: transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      -moz-transition: transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      -ms-transition: transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      -o-transition: transform 350ms cubic-bezier(.55,0,.1,1) 175ms;
      transition: transform 350ms cubic-bezier(.55,0,.1,1) 175ms;

      &-main {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        position: absolute;
        top: 0;
        bottom: 0;
        z-index: 3;
        width: 100%;
        padding: 36px;
        overflow: auto;
        -webkit-transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1);
        transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1);
        -webkit-transition: transform 350ms cubic-bezier(.55,0,.1,1);
        -moz-transition: transform 350ms cubic-bezier(.55,0,.1,1);
        -ms-transition: transform 350ms cubic-bezier(.55,0,.1,1);
        -o-transition: transform 350ms cubic-bezier(.55,0,.1,1);
        transition: transform 350ms cubic-bezier(.55,0,.1,1);

        &_item {
          font-family: FuturaPTBold;
          font-weight: 700;
          font-style: normal;
          font-size: 24px;
          letter-spacing: .04em;
          line-height: 1em;
          text-transform: none;
          color: #fff;
          box-sizing: border-box;
          display: block;
          position: relative;
          width: 100%;
          padding-bottom: .618em;
          text-align: left;
          outline: 0;

          &:first-child {
            padding-top: 1em;
          }
        }

        &_folder {
          &:after {
            content: '\00BB';
            font-family: sans-serif;
            color: rgba(252,252,252,.4);
            margin-left: 10px;
          }

          &-label {
            font-family: FuturaPTBold;
            font-weight: 700;
            font-style: normal;
            font-size: 24px;
            letter-spacing: .04em;
            line-height: 1em;
            text-transform: none;
            color: #fff;
            text-align: left;
          }
        }

        &_back {
          padding-bottom: 38.8px;
          padding-top: 0 !important;
          box-sizing: border-box;
          display: block;
          position: relative;
          width: 100%;
          text-align: left;
          outline: 0;
          border: 0;
          background-color: transparent;

          &:before {
            content: '\00AB';
            font-family: sans-serif;
            color: rgba(252,252,252,.4);
            margin-right: 10px;
          }
        }
      }

      &-second {
        &_links {
          -webkit-box-sizing: border-box;
          -moz-box-sizing: border-box;
          box-sizing: border-box;
          position: absolute;
          top: 0;
          bottom: 0;
          width: 100%;
          padding: 36px;
          overflow: auto;
          -webkit-transform: translate3d(100%,0,0);
          -moz-transform: translate3d(100%,0,0);
          -ms-transform: translate3d(100%,0,0);
          -o-transform: translate3d(100%,0,0);
          transform: translate3d(100%,0,0);
          -webkit-transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1);
          transition: -webkit-transform 350ms cubic-bezier(.55,0,.1,1);
          -webkit-transition: transform 350ms cubic-bezier(.55,0,.1,1);
          -moz-transition: transform 350ms cubic-bezier(.55,0,.1,1);
          -ms-transition: transform 350ms cubic-bezier(.55,0,.1,1);
          -o-transition: transform 350ms cubic-bezier(.55,0,.1,1);
          transition: transform 350ms cubic-bezier(.55,0,.1,1);

          &.active {
            z-index: 2;
            -webkit-transform: translate3d(0,0,0);
            -moz-transform: translate3d(0,0,0);
            -ms-transform: translate3d(0,0,0);
            -o-transform: translate3d(0,0,0);
            transform: translate3d(0,0,0);
          }
        }
      }

      &.has-active-folder {
        .mobile-header__menu-main {
          -webkit-transform: translate3d(-100%,0,0);
          -moz-transform: translate3d(-100%,0,0);
          -ms-transform: translate3d(-100%,0,0);
          -o-transform: translate3d(-100%,0,0);
          transform: translate3d(-100%,0,0);
        }
      }
    }
  }

  @media (max-width: 768px) {
    .book-header {
      display: none;
    }

    .mobile-header {
      display: block;
    }
  }
</style>
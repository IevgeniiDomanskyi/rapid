<template>
  <div>
    <div class="step6">
      <v-form
        v-model="valid"
        ref="form"
        lazy-validation
      >
        <v-radio-group
          v-model="selectedPlan"
          row
          class="book-radio-group"
        >
          <div
            v-for="item in plans"
            :key="item.value"
          >
            <v-radio
              :value="item.value"
              :label="item.label + ' £' + coursePrice"
              :color="$colors.text_inverse"
            />

            <v-select
              v-if="item.value == 1 && selectedPlan == 1"
              v-model="selectedInstalment"
              :items="instalments"
              outlined
              dense
              class="step6-select"
            />
          </div>
        </v-radio-group>

        <div class="mt-2">
          <i>* No payment will be taken at this stage.</i>
        </div>
      </v-form>
    </div>

    <div class="step6-bottom">
      <book-button
        :loading="isLoading"
        label="Next"
        color="red"
        @click="onNextStep"
      />
    </div>
  </div>
</template>

<script>
  import { mapState, mapMutations, mapActions } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'RapidStep6',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    data: (vm) => ({
      valid: true,

      isLoading: false,
    }),

    computed: {
      ...mapState({
        plan: state => state.book.options.plan,
        instalment: state => state.book.options.instalment,
        voucherCode: state => state.book.options.voucher,
        voucher: state => state.vouchers.item,
      }),

      selectedPlan: {
        get() {
          return this.plan
        },

        set(val) {
          this.bookStepSet({
            options: {
              plan: val,
            },
          })
        },
      },

      selectedInstalment: {
        get() {
          return this.instalment
        },

        set(val) {
          this.bookStepSet({
            options: {
              instalment: val,
            },
          })
        },
      },

      plans() {
        return [
          { value: 0, label: 'One-off payment' },
          //{ value: 1, label: 'Pay By Instalments' },
        ]
      },

      coursePrice() {
        let price = 0
        const course = this.courseById(this.options.course)
        if (course.levels) {
          for (const level of course.levels) {
            if (level.id == this.options.level) {
              price = level.price
            }
          }
        }

        if (this.voucher.id) {
          let check = true
          for (const ex of this.voucher.excluded) {
            if (ex.id == this.options.level) {
              check = false
            }
          }

          if (check) {
            if (this.voucher.type == 1) {
              price = Math.round(price * this.voucher.amount / 100)
            } else {
              price = Math.round(price - this.voucher.amount)
            }
          }

          if (price < 0) {
            price = 0
          }
        }

        return price
      },

      instalments() {
        return [
          { value: 2, text: ('2 instalments of £' + (Math.round(this.coursePrice / 2))) },
          { value: 3, text: ('3 instalments of £' + (Math.round(this.coursePrice / 3))) },
          { value: 4, text: ('4 instalments of £' + (Math.round(this.coursePrice / 4))) },
        ]
      },
    },

    created() {
      /* this.bookStep({
        step: 7,
        options: {
          plan: this.plan,
          instalment: this.instalment,
        },
      })

      return */

      if ( !! this.voucherCode) {
        this.vouchersGet({
          code: this.voucherCode
        })
      }
    },

    methods: {
      ...mapMutations([
        'messagesSet',
        'bookStepSet',
      ]),

      ...mapActions([
        'bookStep',
        'vouchersGet',
      ]),

      onNextStep() {
        this.isLoading = true

        this.bookStep({
          step: 7,
          options: {
            plan: this.plan,
            instalment: this.instalment,
          },
        }).then(data => {
          this.isLoading = false
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import "../../sass/_variables.scss";
  @import "../../sass/book.scss";

  .step6 {
    &-select {
      margin-top: 5px;
      width: 210px;
    }

    &-bottom {
      text-align: right;
      padding-top: 30px;
    }
  }

  @media (max-width: 768px) {
    .step6 {
      &-bottom {
        text-align: center;
        padding-top: 0;
      }
    }
  }
</style>
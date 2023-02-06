<template>
  <div>
    <div class="step5 text-center">
      <p class="text-left mb-4">
        <b>Your message:</b><br />
        {{ message }}
      </p>

      <book-button
        :loading="isBookLoading"
        label="Send Enquiry"
        color="red"
        @click="onEnquiry"
      />
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'RapidStep5Enquiry',

    mixins: [RapidBook],

    components: {
      BookButton,
    },

    computed: {
      ...mapState({
        message: state => state.book.options.message,
      }),
    },
  }
</script>
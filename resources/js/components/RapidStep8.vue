<template>
  <div>
    <div class="step8 text-center">
      <book-button
        :loading="isBookLoading"
        label="Book Now"
        color="red"
        @click="onBook"
      />
    </div>
  </div>
</template>

<script>
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'RapidStep8',

    mixins: [RapidBook],

    components: {
      BookButton,
    },
  }
</script>
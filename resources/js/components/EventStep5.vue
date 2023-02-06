<template>
  <div>
    <div class="step8 text-center">
      <book-button
        :loading="isBookLoading"
        label="Book Now"
        color="red"
        @click="onEvent"
      />
    </div>
  </div>
</template>

<script>
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'EventStep5',

    mixins: [RapidBook],

    components: {
      BookButton,
    },
  }
</script>
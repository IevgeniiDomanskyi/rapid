<template>
  <div>
    <div class="step8 text-center">
      <book-button
        :loading="isBookLoading"
        label="Book Now"
        color="red"
        @click="onTrackDay"
      />
    </div>
  </div>
</template>

<script>
  import BookButton from './BookButton'
  import RapidBook from '../mixins/rapid-book'

  export default {
    name: 'TrackDayStep5',

    mixins: [RapidBook],

    components: {
      BookButton,
    },
  }
</script>
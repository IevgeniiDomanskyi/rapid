<template>
  <v-card
    :width="width"
    :dark="inverse"
    :class="{ 'app-card_inverse': inverse }"
    class="app-card"
  >
    <v-card-title
      v-if="isTitleSlotVisible"
      class="headline"
    >
      <slot name="title" />
    </v-card-title>

    <v-card-text>
      <slot />
    </v-card-text>

    <v-card-actions
      v-if="isActionsSlotVisible"
    >
      <slot name="actions" />
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'AppCard',

    props: {
      width: {
        type: [Number, String],
        default: null,
      },

      inverse: {
        type: Boolean,
        default: false,
      },
    },

    computed: {
      isTitleSlotVisible() {
        return !! this.$slots.title
      },

      isActionsSlotVisible() {
        return !! this.$slots.actions
      },
    },
  }
</script>

<style lang="scss">
  @import '../../sass/_variables.scss';

  .app-card {
    &.v-card {
      padding: 1.5rem;
    }

    .v-dialog>.v-card>.v-card__subtitle,
    .v-dialog>.v-card>.v-card__text

    .v-sheet {
      .v-card {
        border-radius: 3px;
      }
    }

    .v-card__title,
    .v-card__text,
    .v-card__actions,
    .v-dialog > .v-card > .v-card__subtitle,
    .v-dialog > .v-card > .v-card__text {
      padding: 0 !important;
    }
  }

  .app-card_inverse {
    &.v-card {
      background: $panel;
    }
  }
</style>
<template>
  <div>
    <h2>Apartment Detail</h2>
    <div>
      <p><strong>Address:</strong> {{ apartment.address }}</p>
      <p><strong>Floors:</strong> {{ apartment.number_floors }}</p>
      <p><strong>Rooms:</strong> {{ apartment.number_rooms }}</p>
      <!-- Add other fields -->

      <button @click="showConfirmationPopup(true)">Approve</button>
      <button @click="showConfirmationPopup(false)">Delete</button>
    </div>

    <!-- Include ConfirmationPopup component -->
    <confirmation-popup v-if="showPopup" @confirm="confirmAction" />
  </div>
</template>

<script>
import ConfirmationPopup from './ConfirmationPopup.vue';

export default {
  props: {
    apartment: Object,
  },
  data() {
    return {
      showPopup: false,
    };
  },
  methods: {
    showConfirmationPopup(approve) {
      // Open the popup and set the action (approve or delete)
      this.showPopup = true;
      this.$emit('action-selected', approve);
    },
    confirmAction(confirmed) {
      if (confirmed) {
        // Perform the action (approve or delete)
        // Call API or emit an event to handle this action
      }
      this.showPopup = false; // Close the popup
    },
  },
  components: {
    ConfirmationPopup,
  },
};
</script>

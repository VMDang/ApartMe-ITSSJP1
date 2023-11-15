<template>
  <div>
    <h2>Apartment List</h2>
    <ul>
      <li v-for="apartment in apartments" :key="apartment.id">
        {{ apartment.address }} - <button @click="showDetails(apartment)">View Details</button>
      </li>
    </ul>

    <!-- Include ConfirmationPopup component -->
    <confirmation-popup v-if="selectedApartment" @confirm="confirmAction" />
  </div>
</template>

<script>
import ConfirmationPopup from './ConfirmationPopup.vue';

export default {
  data() {
    return {
      apartments: [], // Populate with data from API
      selectedApartment: null,
    };
  },
  methods: {
    showDetails(apartment) {
      this.selectedApartment = apartment;
    },
    confirmAction(confirmed) {
      if (confirmed) {
        // Perform action (approve or delete) based on admin's decision
        // Call API or emit an event to handle this action
      }
      this.selectedApartment = null; // Close the popup
    },
  },
  created() {
    // Fetch apartment data from API and update 'apartments' array
  },
  components: {
    ConfirmationPopup,
  },
};
</script>

<!-- src/components/ApartmentDetail.vue -->

<script>
export default {
  props: {
    apartment: Object,
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    acceptRequest() {
      this.$emit("accept");
      this.closeModal();
    },
    deleteRequest() {
      this.$emit("delete");
      this.closeModal();
    },
  },
};
</script>

<template>
    <div class="apartment-detail-modal" v-if="apartment">
      <div class="modal-content">
        <button @click="closeModal" class="close-button">&times;</button>
        <h2>Apartment Details</h2>
        <div>
          <strong>ID:</strong> {{ apartment.id }}<br>
          <strong>Address:</strong> {{ apartment.address }}<br>
          <strong>Owner Email:</strong> {{ apartment.owner_email }}<br>
          <strong>Number of Floors:</strong> {{ apartment.number_floors }}<br>
          <strong>Number of Rooms:</strong> {{ apartment.number_rooms }}<br>
          <strong>Area:</strong> {{ apartment.area }}<br>
          <strong>Apartment Type ID:</strong> {{ apartment.apartment_type_id }}<br>
        </div>

        <!-- Nút "Chấp nhận" -->
        <button @click="acceptRequest" class="action-button accept-button">Accept</button>

        <!-- Nút "Xóa" -->
        <button @click="deleteRequest" class="action-button delete-button">Delete</button>
      </div>
    </div>
  </template>

  <style scoped>
  .action-button {
    padding: 10px;
    margin-top: 10px;
    cursor: pointer;
  }

  .accept-button {
    background-color: green;
    color: white;
    margin-right: 10px;
  }

  .delete-button {
    background-color: red;
    color: white;
  }

  .apartment-detail-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    position: relative; /* Để định vị tuyệt đối các phần tử con */
  }

  .close-button {
    background-color: #ff6666; /* Màu đỏ nhạt */
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
  }
  </style>

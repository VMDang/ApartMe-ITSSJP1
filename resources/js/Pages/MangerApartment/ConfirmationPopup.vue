<template>
  <div class="popup">
    <div class="popup-content">
      <p>Do you want to proceed with this action?</p>
      <button @click="confirmAction(true)">Yes</button>
      <button @click="confirmAction(false)">No</button>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    confirmAction(confirmed) {
      this.$emit('confirm', confirmed);
    },
  },
};
</script>

<style scoped>
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-content {
  background: white;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
}
</style>

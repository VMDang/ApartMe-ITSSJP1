<template>
    <div class="flex mb-4">
        <div class="w-full"><NavBar /></div>
    </div>
    <!-- Two columns -->
    <div class="flex mb-4">
      <div class="w-1/4 bg-gray-400">
        <SideBar />
      </div>
      <div class="w-3/4 bg-gray-500">
        <div class="flex">
            <header class="bg-white w-full" v-if="$slots.pageHeader">
              <div>
                <slot name="pageHeader" />
              </div>
            </header>
        </div>
        <div class="flex">
            <div class="bg-white w-full">
                <slot name="pageMainContent"/>
            </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import NavBar from '@/Components/NavBar.vue';
import SideBar from '@/Components/SideBar.vue';
</script>
<!-- SidebarItem.vue -->
<template>
    <div role="button" tabindex="0"
        class="flex items-center w-full p-3 rounded-lg text-start leading-tight transition-all hover:bg-blue-50 hover:bg-opacity-80 focus:bg-blue-50 focus:bg-opacity-80 active:bg-blue-50 active:bg-opacity-80 hover:text-blue-900 focus:text-blue-900 active:text-blue-900 outline-none">
        <div class="grid place-items-center mr-4">
            <svg :xmlns="icon.xmlns" :viewBox="icon.viewBox" :fill="icon.fill" :aria-hidden="icon.ariaHidden"
                class="h-5 w-5">
                <path :fill-rule="icon.fillRule" :d="icon.path"></path>
            </svg>
        </div>
        {{ label }}
    </div>
</template>

<script>
export default {
    props: {
        icon: Object,
        label: String,
    },
};
</script>

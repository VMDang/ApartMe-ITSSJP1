<script setup>
import { usePage } from '@inertiajs/vue3';
import SideBarItem from './SideBarItem.vue';
</script>

<!-- Sidebar.vue -->
<template>
    <nav class="relative flex flex-col bg-clip-border bg-white text-gray-700 h-[calc(100vh-2rem)] w-full p-4 shadow-xl shadow-blue-gray-900/5">
        <SideBarItem :key="sidebarItems[0].label" :icon="sidebarItems[0].icon" :label="sidebarItems[0].label" 
                     :href="route('dashboard')" :active="route().current('dashboard')" /> 
        <SideBarItem :key="sidebarItems[1].label" :icon="sidebarItems[1].icon" :label="sidebarItems[1].label" 
                     v-if="usePage().props.apartment !== null"
                     :href="route('selectApartment.show')"
                     :active="route().current('selectApartment.show')"/>
        <SideBarItem :key="sidebarItems[2].label" :icon="sidebarItems[2].icon" :label="sidebarItems[2].label" />
    </nav>
</template>

<script>
export default {
    components: {
        SideBarItem,
    },
    data() {
        return {
            sidebarItems: [
                { 
                    icon: { xmlns: 'http://www.w3.org/2000/svg', 
                            viewBox: '0 0 24 24', fill: 'currentColor', 
                            ariaHidden: 'true', fillRule: 'evenodd', 
                            path: 'M2.25 2.25a.75.75 0 000 1.5H3v10.5a3 3 0 003 3h1.21l-1.172 3.513a.75.75 0 001.424.474l.329-.987h8.418l.33.987a.75.75 0 001.422-.474l-1.17-3.513H18a3 3 0 003-3V3.75h.75a.75.75 0 000-1.5H2.25zm6.04 16.5l.5-1.5h6.42l.5 1.5H8.29zm7.46-12a.75.75 0 00-1.5 0v6a.75.75 0 001.5 0v-6zm-3 2.25a.75.75 0 00-1.5 0v3.75a.75.75 0 001.5 0V9zm-3 2.25a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5z'
                    },
                    label: "Dashboard"
                },
                { 
                    icon: { xmlns: "http://www.w3.org/2000/svg", 
                            viewBox: "0 0 24 24", fill: "currentColor", 
                            ariaHidden: 'true', 
                            fillRule: 'evenodd', 
                            path: 'M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z' 
                    },
                    label: "Selected Item"
                }, 
                { 
                    icon: { xmlns: "http://www.w3.org/2000/svg",
                            viewBox: "0 0 24 24", fill: "currentColor",
                            ariaHidden: 'true',
                            fillRule: 'evenodd',
                            path: 'M6.912 3a3 3 0 00-2.868 2.118l-2.411 7.838a3 3 0 00-.133.882V18a3 3 0 003 3h15a3 3 0 003-3v-4.162c0-.299-.045-.596-.133-.882l-2.412-7.838A3 3 0 0017.088 3H6.912zm13.823 9.75l-2.213-7.191A1.5 1.5 0 0017.088 4.5H6.912a1.5 1.5 0 00-1.434 1.059L3.265 12.75H6.11a3 3 0 012.684 1.658l.256.513a1.5 1.5 0 001.342.829h3.218a1.5 1.5 0 001.342-.83l.256-.512a3 3 0 012.684-1.658h2.844z'  
                    },
                    label: "Register Request From Owner"
                }
            ],
        };
    },
};
</script>

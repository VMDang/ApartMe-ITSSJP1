<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import SelectApartment from "@/Pages/SelectApartment.vue";

const apartment = usePage().props.apartment;

</script>

<template>
    <!-- <Head title="Dashboard" /> -->

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template> -->

        <div class="py-12" v-if="apartment !== null">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <header>
                            <h1 class="text-2xl text-gray-900 font-extrabold">Selected Apartment</h1>
                        </header>
                    </div>
                    <div class="mt-1 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <section>
                            <header>
                                <h2 class="text-lg font-bold text-gray-900">Address: {{apartment.address}} </h2>
                                <div class="text-gray-900">Area: {{apartment.area}} m&#178;</div>
                                <div class="text-gray-900">Number Floors: {{apartment.number_floors }}</div>
                                <div class="text-gray-900">Number Rooms: {{apartment.number_rooms }}</div>
                            </header>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12" v-if="apartment === null">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <header>
                            <h1 class="text-2xl text-gray-900 font-extrabold">Admin Dashboard</h1>
                        </header>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

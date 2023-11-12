<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia'
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineProps({
    owner: {
        type: Object,
    },
    tenants: {
        type: Array,
    },
});

const owner = usePage().props.owner;
const tenants = usePage().props.tenants;

const redirectFollowApartmentSelected = (apartmentID) => {
    Inertia.get(route('selectApartment.store', {id: apartmentID}));
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Select an Apartment</h2>
        </template>


        <div class="py-8" >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                    <header>
                        <h1 class="text-2xl text-gray-900 font-extrabold">Apartment Owner</h1>
                    </header>
                </div>
                <div class="mt-1 p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Address: {{owner.address}} </h2>
                            <div class="text-gray-900">Area: {{owner.area}} m&#178;</div>
                            <div class="text-gray-900">Number Floors: {{ owner.number_floors }}</div>
                            <div class="text-gray-900">Number Rooms: {{ owner.number_rooms }}</div>
                        </header>
                        <PrimaryButton class="mt-2" @click="() => redirectFollowApartmentSelected(owner.id)"> Go to this apartment </PrimaryButton>
                    </section>
                </div>
            </div>
        </div>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                    <header>
                        <h1 class="text-2xl text-gray-900 font-extrabold">Apartment Tenant</h1>
                    </header>
                </div>
                <div class="mt-1 p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-for="tenant in tenants" :key="tenant.id">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Address: {{tenant.address}} </h2>
                            <div class="text-gray-900">Area: {{tenant.area}} m&#178;</div>
                            <div class="text-gray-900">Number Floors: {{tenant.number_floors }}</div>
                            <div class="text-gray-900">Number Rooms: {{tenant.number_rooms }}</div>
                        </header>
                        <PrimaryButton class="mt-2" @click="() => redirectFollowApartmentSelected(tenant.id)"> Go to this apartment </PrimaryButton>
                    </section>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    address: '',
    area: '',
    number_floors: '',
    number_rooms: '',
    apartment_type_id: 0,
});

const submit = () => {
    form.apartment_type_id = document.getElementById('apartment_type_id').value;
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const apartment_types = usePage().props.apartment_types;

</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Phone" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="mt-4">
                <InputLabel for="address" value="Apartment Address" />

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address"
                    required
                    autocomplete="address"
                />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div class="mt-4">
                <InputLabel for="apartment_type_id" value="Apartment Type" />

                <select id="apartment_type_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="" disabled selected>Select an Apartment type</option>

                    <option v-for="apartment_type in apartment_types" :value="apartment_type.id">{{ apartment_type.name }}</option>
                </select>

                <InputError class="mt-2" :message="form.errors.apartment_type_id" />
            </div>

            <div class="mt-4">
                <InputLabel for="area" value="Area (m&#178;)" />

                <TextInput
                    id="area"
                    type="number"
                    class="mt-1 block w-full"
                    v-model="form.area"
                    required
                    autocomplete="area"
                />

                <InputError class="mt-2" :message="form.errors.area" />
            </div>

            <div class="mt-4 flex">
                <div class="w-1/2 mr-2">
                    <InputLabel for="number_floors" value="Number Floors" />

                    <TextInput
                        id="number_floors"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.number_floors"
                        required
                        autocomplete="number_floors"
                    />

                    <InputError class="mt-2" :message="form.errors.number_floors" />
                </div>

                <div class="w-1/2">
                    <InputLabel for="number_rooms" value="Number Rooms" />

                    <TextInput
                        id="number_rooms"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.number_rooms"
                        required
                        autocomplete="number_rooms"
                    />

                    <InputError class="mt-2" :message="form.errors.number_rooms" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

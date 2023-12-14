<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import {Inertia} from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";

const errors = usePage().props.errors;
console.log(errors);

const formRef = ref();
const labelCol = {
    style: {
        width: '150px',
    },
};
const wrapperCol = {
    span: 14,
};

const formState = reactive({
    name: '',
    email: '',
    phone: '',
    rooms: undefined,
    password: '',
    password_confirmation: '',
});

const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            console.log(toRaw(formState))
            Inertia.post(route('tenant-accounts.store'), toRaw(formState))
        })
        .catch(error => {
            console.log('error', error);
        });
};

const resetForm = () => {
    formRef.value.resetFields();
};

const options = ref(
    usePage().props.rooms.map((room) => ({
        value: room.id,
        label: room.name,
    }))
);


const validatePass = async (_rule, value) => {
    if (value === '') {
        return Promise.reject('Please input the password');
    } else {
        if (formState.password_confirmation !== '') {
            formRef.value.validateFields('password_confirmation');
        }
        return Promise.resolve();
    }
};
const validatePass2 = async (_rule, value) => {
    if (value === '') {
        return Promise.reject('Please input the password again');
    } else if (value !== formState.password) {
        return Promise.reject("Password confirmation don't match!");
    } else {
        return Promise.resolve();
    }
};

const rules = {
    name: [
        {
            required: true,
            message:  'Please input Room name',
            trigger: 'change',
        },
        {
            min: 2,
            max: 255,
            message:  'Length should be 2 to 255',
            trigger: 'change',
        }
    ],
    email: [
        {
            type: 'email',
            required: true,
            message:  'Please input Email',
            trigger: 'change',
        },
        {
            max: 255,
            message:  'Length max is 255',
            trigger: 'change',
        }
    ],
    phone: [
        {
            required: true,
            message:  'Please input Phone number',
            trigger: 'change',
        },
        {
            min:3,
            max: 255,
            message:  'Length should be 3 to 255',
            trigger: 'change',
        }
    ],
    rooms: [
        {
            required: true,
            message:  'Please select one or more rooms',
            trigger: 'change',
        },
    ],
    password: [
        {
            min: 8,
            message:  'Length min is 8',
            trigger: 'change',
        },
        {
            required: true,
            validator: validatePass,
            trigger: 'change',
        },
    ],
    password_confirmation: [
        {
            validator: validatePass2,
            trigger: 'change',
        },
    ],
}

</script>

<template>
    <Head title="Create Room" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="Create account tenant"
                    sub-title="Create account tenant and Add tenant to rooms"
                    @back="() => Inertia.get(route('tenant-accounts.index'))"
                />
            </div>

            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-form
                    :rules = "rules"
                    ref="formRef"
                    :model="formState"
                    :label-col="labelCol"
                    :wrapper-col="wrapperCol"
                    layout="horizontal"
                    autocomplete="off"
                    style="max-width: 600px"
                >
                    <a-form-item ref="name" label="Full name" name="name" has-feedback  >
                        <a-input v-model:value="formState.name" allow-clear/>
                        <InputError class="mt-2" :message="errors.name" />
                    </a-form-item>

                    <a-form-item ref="email" label="Email" name="email" has-feedback >
                        <a-input type="email" v-model:value="formState.email" allow-clear/>
                        <InputError class="mt-2" :message="errors.email" />
                    </a-form-item>

                    <a-form-item ref="phone" label="Phone" name="phone" has-feedback>
                        <a-input v-model:value="formState.phone" />
                        <InputError class="mt-2" :message="errors.phone" />
                    </a-form-item>

                    <a-form-item label="Rooms" name="rooms" has-feedback>
                        <a-select
                            :options="options"
                            mode="multiple"
                            v-model:value="formState.rooms"
                            placeholder="Please select the room that owns the invoice"
                            show-search
                            allow-clear
                        />
                        <InputError class="mt-2" :message="errors.rooms" />
                    </a-form-item>

                    <a-form-item ref="password" label="Password" name="password" has-feedback >
                        <a-input-password v-model:value="formState.password" />
                        <InputError class="mt-2" :message="errors.password" />
                    </a-form-item>

                    <a-form-item ref="password_confirmation" label="Password Confirm" name="password_confirmation" has-feedback>
                        <a-input-password v-model:value="formState.password_confirmation" />
                    </a-form-item>

                    <a-form-item :wrapper-col="{ span: 28, offset: 14 }">
                        <a-space>
                            <a-button @click="resetForm">Reset</a-button>
                            <a-button type="primary" @click="onSubmit">Create</a-button>
                        </a-space>
                    </a-form-item>
                </a-form>
            </div>
        </div>

    </AuthenticatedLayout>

</template>

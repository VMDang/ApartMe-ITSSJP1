<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";
const errors = usePage().props.errors;
const formRef = ref();
const labelCol = {
    span: 5,
};
const wrapperCol = {
    span: 13,
};
const formState = reactive({
    name: invoice.name,
    room: invoice.room,
    date: undefined,
    note: invoice.description,
    total: invoice.total,
});
const rules = {
    name: [
        {
            required: true,
            message: 'Please input Invoice name',
            trigger: 'change',
        }
    ],
    room: [
        {
            required: true,
            message: 'Please select Room',
            trigger: 'change',
        },
    ],
    date: [
        {
            required: true,
            message: 'Please pick a date',
            trigger: 'change',
            type: 'object',
        },
    ],
    total: [
        {
            required: true,
            message: 'Please input total amount',
            trigger: 'change',
            pattern: /^[1-9]\d*$/, // Chỉ cho phép nhập số nguyên dương
        }
    ],
};
const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            console.log('values', formState, toRaw(formState));
        })
        .catch(error => {
            console.log('error', error);
        });
};
const resetForm = () => {
    formRef.value.resetFields();
};
</script>

<template>
    <Head title="Edit Invoices" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="Edit Invoice"
                           sub-title="Edit a Invoice" />
        </div>
        <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
            <a-form ref="formRef" :model="formState" :rules="rules" :label-col="labelCol" :wrapper-col="wrapperCol">
                <a-form-item ref="name" label="Invoice name" name="name">
                    <a-input v-model:value="formState.name" />
                </a-form-item>
                <a-form-item label="Room" name="room">
                    <a-select v-model:value="formState.room" placeholder="Please select the room that owns the invoice">
                        <a-select-option value="1">P 101</a-select-option>
                        <a-select-option value="2">P 102</a-select-option>
                        <a-select-option value="3">P 103</a-select-option>
                        <a-select-option value="4">P 201</a-select-option>
                        <a-select-option value="5">P 202</a-select-option>
                        <a-select-option value="6">P 203</a-select-option>
                        <a-select-option value="7">P 301</a-select-option>
                        <a-select-option value="8">P 302</a-select-option>
                    </a-select>
                </a-form-item>
                <a-form-item label="Payment batch" required name="date">
                    <a-date-picker v-model:value="formState.date" show-time type="date" placeholder="Pick a date"
                                   style="width: 100%" />
                </a-form-item>
                <a-form-item ref="total" label="Total amount" name="total">
                    <a-input v-model:value="formState.total" v-money="moneyConfig"/>
                </a-form-item>
                <a-form-item label="Note" name="note">
                    <a-textarea v-model:value="formState.note" />
                </a-form-item>
                <a-form-item :wrapper-col="{ span: 14, offset: 4 }">
                    <a-button type="primary" @click="onSubmit">Edit</a-button>
                    <a-button style="margin-left: 10px" @click="resetForm">Reset</a-button>
                </a-form-item>
            </a-form>
        </div>

    </AuthenticatedLayout>
</template>

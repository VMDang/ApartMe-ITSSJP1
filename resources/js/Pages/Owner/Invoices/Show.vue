<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";
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
    <Head title="Invoice Information" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="Invoice Information"
                           sub-title="Invoice Information" />
        </div>
        <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
            <a-descriptions title="Invoice Info" bordered>
                <a-descriptions-item label="Invoice Name" :span="3">{{invoice.name}}</a-descriptions-item>

                <a-descriptions-item label="Room" :span="3">{{invoice.room}}</a-descriptions-item>
                <a-descriptions-item label="Payment batch" :span="3">{{date}}</a-descriptions-item>
                <a-descriptions-item label="Status" :span="3">
                    <a-badge status="processing" text="Unpaid" />
                </a-descriptions-item>
                <a-descriptions-item label="Total Amount" :span="3">{{invoice.total}}</a-descriptions-item>
                <a-descriptions-item label="Note">
                   {{invoice.description}}
                </a-descriptions-item>
            </a-descriptions>
        </div>

    </AuthenticatedLayout>
</template>

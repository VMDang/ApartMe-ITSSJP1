<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from "vue";
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";
import dayjs from "dayjs";

const errors = usePage().props.errors;
const formRef = ref();
const labelCol = {
    span: 5,
};
const wrapperCol = {
    span: 13,
};
const formState = reactive({
    name: "",
    room: undefined,
    deadline: undefined,
    total: "",
    description: "",
});

const options = ref(
    usePage().props.rooms.map((room) => ({
        value: room.id,
        label: room.name,
    }))
);

const rules = {
    name: [
        {
            required: true,
            message: "Please input Invoice name",
            trigger: "change",
        },
    ],
    room: [
        {
            required: true,
            message: "Please select Room",
            trigger: "change",
        },
    ],
    deadline: [
        {
            required: true,
            message: "Please pick a date",
            trigger: "change",
            type: "object",
        },
    ],
    description: [
        {
            max: 255,
            message: "Description max 255 character",
            trigger: "change",
        },
    ],
};
const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            Inertia.post(route("invoices.store"), toRaw(formState));
        })
        .catch((error) => {
            console.log("error", error);
        });
};
const resetForm = () => {
    formRef.value.resetFields();
};

const disabledDate = (current) => {
    return current && current < dayjs().endOf("day");
};
</script>

<template>
    <Head title="Create Invoices" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                    "
                    title="Create Invoice"
                    sub-title="Create a Invoice"
                    @back="() => Inertia.get(route('invoices.index'))"
                />
            </div>

            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-form
                    ref="formRef"
                    :model="formState"
                    :rules="rules"
                    :label-col="labelCol"
                    :wrapper-col="wrapperCol"
                >
                    <a-form-item
                        ref="name"
                        label="Invoice name"
                        name="name"
                        has-feedback
                    >
                        <a-input v-model:value="formState.name" />
                        <InputError class="mt-2" :message="errors.name" />
                    </a-form-item>
                    <a-form-item label="Room" name="room" has-feedback>
                        <a-select
                            :options="options"
                            v-model:value="formState.room"
                            placeholder="Please select the room that owns the invoice"
                            show-search
                        />
                        <InputError class="mt-2" :message="errors.room" />
                    </a-form-item>
                    <a-form-item
                        label="Deadline"
                        required
                        name="deadline"
                        has-feedback
                    >
                        <a-date-picker
                            v-model:value="formState.deadline"
                            type="date"
                            placeholder="Pick a date"
                            style="width: 100%"
                            allow-clear
                            :disabled-date="disabledDate"
                            :format="'DD/MM/YYYY'"
                        />
                        <InputError class="mt-2" :message="errors.deadline" />
                    </a-form-item>
                    <a-form-item
                        ref="total"
                        label="Total amount"
                        name="total"
                        :rules="[
                            {
                                required: true,
                                type: 'number',
                                min: 0,
                                max: 1000000000,
                            },
                        ]"
                        has-feedback
                    >
                        <a-input-number
                            type="number"
                            class="w-full"
                            v-model:value="formState.total"
                        />
                        <InputError class="mt-2" :message="errors.total" />
                    </a-form-item>
                    <a-form-item label="Description" name="description">
                        <a-textarea v-model:value="formState.description" />
                    </a-form-item>
                    <a-form-item :wrapper-col="{ span: 28, offset: 15 }">
                        <a-button @click="resetForm">Reset</a-button>
                        <a-button class="ml-4" type="primary" @click="onSubmit"
                            >Create</a-button
                        >
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from "vue";
import { Inertia } from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";

const errors = usePage().props.errors;
const formRef = ref();
const labelCol = {
    style: {
        width: "150px",
    },
};
const wrapperCol = {
    span: 14,
};
const room = usePage().props.room;

const formState = reactive({
    name: room.name,
    status: `${room.status ? "Active" : "Lock"}`,
    floor: room.floor,
    area: room.area,
});

const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            Inertia.put(
                route("rooms.update", { room: room.id }),
                toRaw(formState)
            );
        })
        .catch((error) => {
            console.log("error", error);
        });
};

const resetForm = () => {
    formRef.value.resetFields();
};
const rules = {
    name: [
        {
            required: true,
            message: "Please input Room name",
        },
    ],
    floor: [
        {
            required: true,
            message: "Please input floor",
        },
    ],

    status: [
        {
            required: true,
            message: "Please select status",
        },
    ],
};

</script>

<template>
    <Head title="Edit room" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 2px solid rgb(221,222,225); border-radius: 10px"
                    title="Edit room"
                    sub-title="Edit a room"
                    @back="() => Inertia.get(route('rooms.index'))"
                />
            </div>

            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-form
                    :rules="rules"
                    :model="formState"
                    :label-col="labelCol"
                    :wrapper-col="wrapperCol"
                    layout="horizontal"
                    ref="formRef"
                    style="max-width: 600px"
                >
                    <a-form-item ref="name" label="Room name" name="name" has-feedback>
                        <a-input v-model:value="formState.name" allow-clear/>
                        <InputError class="mt-2" :message="errors.name" />
                    </a-form-item>

                    <a-form-item ref="floor" label="Floor" name="floor" has-feedback>
                        <a-input v-model:value="formState.floor" allow-clear/>
                        <InputError class="mt-2" :message="errors.floor" />
                    </a-form-item>

                    <a-form-item
                        ref="area"
                        label="Area (m&#178)"
                        name="area"
                        :rules="[{ required: true, type: 'number', min: 0 }]"
                        has-feedback
                    >
                        <a-input-number type="number" v-model:value="formState.area"/>
                        <InputError class="mt-2" :message="errors.area" />
                    </a-form-item>

                    <a-form-item label="Status" name="status" has-feedback>
                        <a-select
                            defaultActiveFirstOption
                            ref="status"
                            v-model:value="formState.status"
                            placeholder="Please select an active status"
                            defaultValue="{}"
                        >
                            <a-select-option value="Active"
                                >Active</a-select-option
                            >
                            <a-select-option value="Lock">Lock</a-select-option>
                        </a-select>
                        <InputError class="mt-2" :message="errors.status" />
                    </a-form-item>

                    <a-form-item :wrapper-col="{ span: 28, offset: 14 }">
                        <a-space>
                            <a-button @click="resetForm">Reset</a-button>
                            <a-button type="primary" @click="onSubmit"
                                >Edit</a-button
                            >
                        </a-space>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

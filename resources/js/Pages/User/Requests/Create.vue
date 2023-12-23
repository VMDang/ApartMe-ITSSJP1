<script setup>

import {Head, usePage} from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {reactive, ref, toRaw} from "vue";
import InputError from "@/Components/InputError.vue";

const errors = usePage().props.errors;

const formRef = ref();
const labelCol = {
    style: {
        width: '250px',
    },
};
const wrapperCol = {
    span: 20,
};

const formState = reactive({
    title: '',
    content: '',
    receivers: undefined,
});

const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            Inertia.post(route('requests.store'), toRaw(formState))
        })
        .catch(error => {
            console.log('error', error);
        });
};

const resetForm = () => {
    formRef.value.resetFields();
};

const options = ref(
    usePage().props.users.map((user) => ({
        value: user.id,
        label: `${user.name} (${user.email})`,
    }))
);

const rules = {
    title: [
        {
            required: true,
            message:  'Please input title',
            trigger: 'change',
        }
    ],
    receivers: [
        {
            required: true,
            message:  'Please select one or more receivers',
            trigger: 'change',
        },
    ],
    content: [
        {
            required: true,
            message:  'Please input content',
            trigger: 'change',
        }
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
                    title="Create request"
                    @back="() => Inertia.get(route('requests.sent'))"
                />
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a-form
                :rules = "rules"
                ref="formRef"
                :model="formState"
                :label-col="labelCol"
                :wrapper-col="wrapperCol"
                layout="horizontal"
                autocomplete="off"
                style="width: 1000px"
            >
                <a-form-item ref="title" label="Title" name="title" has-feedback  >
                    <a-input v-model:value="formState.title" allow-clear/>
                    <InputError class="mt-2" :message="errors.title" />
                </a-form-item>

                <a-form-item label="Receivers" name="receivers" has-feedback>
                    <a-select
                        :options="options"
                        mode="multiple"
                        v-model:value="formState.receivers"
                        placeholder="Select the receivers"
                        show-search
                        allow-clear
                    />
                    <InputError class="mt-2" :message="errors.receivers" />
                </a-form-item>

                <a-form-item ref="content" label="Content" name="content" has-feedback>
                    <a-textarea style="height: 200px" v-model:value="formState.content" allow-clear/>
                    <InputError class="mt-2" :message="errors.content" />
                </a-form-item>

                <a-form-item :wrapper-col="{ span: 28, offset: 21 }">
                    <a-space>
                        <a-button @click="resetForm">Reset</a-button>
                        <a-button type="primary" @click="onSubmit">Create</a-button>
                    </a-space>
                </a-form-item>
            </a-form>
        </div>
    </AuthenticatedLayout>
</template>

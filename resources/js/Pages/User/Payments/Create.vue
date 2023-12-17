<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import {CheckCircleFilled, CloseCircleFilled, SyncOutlined} from "@ant-design/icons-vue";
import InputError from "@/Components/InputError.vue";

const invoiceReceived = usePage().props.invoice

const invoice = ref({
    name: invoiceReceived.name,
    total: invoiceReceived.total,
    description: invoiceReceived.description,
    deadline: new Date(invoiceReceived.deadline).toLocaleDateString('en-GB'),
    status: `${invoiceReceived.status === 1 ? "Done" : invoiceReceived.status === 0 ? "Not Done" : "" }`,
    room: invoiceReceived.room,
})

const errors = usePage().props.errors;
const formRef = ref();

const paymentMethod = () => {
    if (invoiceReceived.payment == null) {
        return undefined;
    } else {
        return invoiceReceived.payment.payment_method
    }
}

const totalPayment = () => {
    if (invoiceReceived.payment == null) {
        return invoiceReceived.total;
    } else {
        return invoiceReceived.payment.total
    }
}

const messagePayment = () => {
    if (invoiceReceived.payment == null) {
        return undefined;
    } else {
        return invoiceReceived.payment.message
    }
}

const buttonSubmitContent = () => {
    if (invoiceReceived.payment == null) {
        return 'Payment'
    }
    return 'Update'
}

const formState = reactive({
    payment_method: paymentMethod(),
    total: totalPayment(),
    message: messagePayment(),
});

const options = [
    {
        value: 'CARD',
        label: 'CARD'
    },
    {
        value: 'BANK-TRANSFER',
        label: 'BANK-TRANSFER'
    },
    {
        value: 'CASH',
        label: 'CASH'
    },
]

const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            Inertia.post(route('payments.store', { invoice: invoiceReceived.id }), toRaw(formState))
        })
        .catch(error => {
            console.log('error', error);
        });
};

const resetForm = () => {
    formRef.value.resetFields();
};
const rules = {
    payment_method: [
        {
            required: true,
            message:  'Please select a Payment method',
        }
    ],
}

const paymentBy = () => {
    if (invoiceReceived.payment == null) {
        return usePage().props.auth.user.id
    }
    return invoiceReceived.payment.payment_by
}
</script>

<template>
    <Head title="Payment Invoice" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="Payment Invoice"
                               @back="() => Inertia.get(route('invoices.index'))"
                />
            </div>
            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-descriptions title="Invoice Information" bordered>
                    <a-descriptions-item label="Invoice Name" :span="3"><strong>{{invoice.name}}</strong></a-descriptions-item>
                    <a-descriptions-item label="Room" :span="3">{{invoice.room.name}}</a-descriptions-item>
                    <a-descriptions-item label="Deadline" :span="3">
                        <span class="text-red-600">{{invoice.deadline}}</span>
                    </a-descriptions-item>
                    <a-descriptions-item label="Status" :span="3">
                        <a-badge status="processing" >
                            <a-tooltip title="Done"
                                       v-if="invoice.status === 'Done'"
                                       style="color: #1890ff"
                            >
                                <check-circle-filled class="text-green-700" style="font-size: x-large"  />
                            </a-tooltip>
                            <a-tooltip title="Not Done"
                                       v-if="invoice.status === 'Not Done' && invoiceReceived.payment === null"
                                       style="color: #e80101"
                            >
                                <close-circle-filled class="text-rose-600" style="font-size: x-large"/>
                            </a-tooltip>
                            <a-tooltip title="Waiting approve"
                                        v-if="invoice.status === 'Not Done' && invoiceReceived.payment != null">
                                <sync-outlined style="font-size: x-large"/>
                            </a-tooltip>
                        </a-badge>
                    </a-descriptions-item>
                    <a-descriptions-item label="Total Amount" :span="3">
                        <span class="text-amber-900">
                            <strong style="font-size: large"> +{{invoice.total}}$ </strong>
                        </span>
                    </a-descriptions-item>
                    <a-descriptions-item label="Description">
                        {{invoice.description}}
                    </a-descriptions-item>
                </a-descriptions>
                <hr>
            </div>

            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-descriptions title="Form Payment"/>
                <a-form
                    :rules = "rules"
                    :model="formState"
                    :disabled="usePage().props.auth.user.id !== paymentBy() || invoice.status === 'Done'"
                    layout="inline"
                    ref="formRef"
                >
                    <a-form-item ref="payment_method" label="Payment Method" name="payment_method" has-feedback >
                        <a-select
                            style="width: 220px"
                            :options="options"
                            v-model:value="formState.payment_method"
                            placeholder="Please select the room that owns the invoice"
                            show-search
                            allow-clear
                        />
                        <InputError class="mt-2" :message="errors.payment_method" />
                    </a-form-item>
                    <a-form-item ref="total" label="Total" name="total" :rules="[{ required: true, type: 'number', min: formState.total}]" has-feedback >
                        <a-input-number style="width: 200px" type="number" v-model:value="formState.total" allow-clear/>
                        <InputError class="mt-2" :message="errors.total" />
                    </a-form-item>

                    <a-form-item ref="message" label="Message" name="message" has-feedback>
                        <a-textarea style="width: 300px" v-model:value="formState.message" allow-clear/>
                        <InputError class="mt-2" :message="errors.message" />
                    </a-form-item>

                    <a-form-item>
                        <a-space>
                            <a-button @click="resetForm">Reset</a-button>
                            <a-button type="primary" class="bg-red-600" @click="onSubmit"> {{ buttonSubmitContent() }} </a-button>
                        </a-space>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

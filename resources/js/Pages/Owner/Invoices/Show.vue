<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import {CheckCircleFilled, CloseCircleFilled, SyncOutlined} from "@ant-design/icons-vue";

const invoiceReceived = usePage().props.invoice

const invoice = ref({
    name: invoiceReceived.name,
    total: invoiceReceived.total,
    description: invoiceReceived.description,
    deadline: new Date(invoiceReceived.deadline).toLocaleDateString('en-GB'),
    status: `${invoiceReceived.status === 1 ? "Done" : invoiceReceived.status === 0 ? "Not Done" : "" }`,
    room: invoiceReceived.room,
})
</script>

<template>
    <Head title="Invoice Information" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="Invoice Information"
                               sub-title="Invoice Information" @back="() => Inertia.get(route('invoices.index'))"
                />
            </div>
            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-descriptions title="" bordered>
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
                                <sync-outlined style="font-size: x-large" />
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
            <div v-if="invoiceReceived.payment != null" class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-descriptions title="Payment Information" bordered>
                    <a-descriptions-item label="Payment Method" :span="3"><strong>{{invoiceReceived.payment.payment_method}}</strong></a-descriptions-item>
                    <a-descriptions-item label="Status" :span="3">
                        <a-badge status="processing" >
                            <a-tooltip title="Paid"
                                       v-if="invoiceReceived.payment.status === 1"
                                       style="color: #1890ff"
                            >
                                <check-circle-filled class="text-green-700" style="font-size: x-large"  />
                            </a-tooltip>
                            <a-tooltip title="Not Done"
                                       v-if="invoiceReceived.payment.status === 0"
                                       style="color: #e80101"
                            >
                                <close-circle-filled class="text-rose-600" style="font-size: x-large"/>
                            </a-tooltip>
                        </a-badge>
                    </a-descriptions-item>
                    <a-descriptions-item label="Total Payment" :span="3">
                        <span class="text-amber-900">
                            <strong style="font-size: large"> +{{invoiceReceived.payment.total}}$ </strong>
                        </span>
                    </a-descriptions-item>
                    <a-descriptions-item label="Message" :span="3">
                        {{invoiceReceived.payment.message}}
                    </a-descriptions-item>
                </a-descriptions>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

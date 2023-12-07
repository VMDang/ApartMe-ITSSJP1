<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { reactive, ref, toRaw } from 'vue';
import { Inertia } from "@inertiajs/inertia";

const invoiceReceived = usePage().props.invoice

const invoice = ref({
    name: invoiceReceived.name,
    total: invoiceReceived.total,
    description: invoiceReceived.description,
    deadline: new Date(invoiceReceived.deadline).toLocaleDateString('en-GB'),
    status: `${invoiceReceived.status === 1 ? "Done" : invoice.status === 0 ? "Not Done" : "" }`,
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
                    <a-descriptions-item label="Invoice Name" :span="3">{{invoice.name}}</a-descriptions-item>

                    <a-descriptions-item label="Room" :span="3">{{invoice.room.name}}</a-descriptions-item>
                    <a-descriptions-item label="Deadline" :span="3">{{invoice.deadline}}</a-descriptions-item>
                    <a-descriptions-item label="Status" :span="3">
                        <a-badge status="processing" > {{invoice.status}} </a-badge>
                    </a-descriptions-item>
                    <a-descriptions-item label="Total Amount" :span="3">{{invoice.total}}</a-descriptions-item>
                    <a-descriptions-item label="Note">
                        {{invoice.description}}
                    </a-descriptions-item>
                </a-descriptions>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

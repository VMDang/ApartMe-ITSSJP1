<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";
import {CheckCircleFilled, CloseCircleFilled, SyncOutlined} from "@ant-design/icons-vue";

const registration = usePage().props.registration
console.log(registration)
</script>

<template>
    <Head title="Detail Registration"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="Registration detail"
                               @back="() => Inertia.get(route('registrations.index'))"
                />
            </div>

            <div class="max-w-7xl mt-8 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-descriptions title="Apartment and Owner Information" bordered>
                    <a-descriptions-item style="font-size: large" label="Apartment address" :span="3">
                        <strong>{{registration.address}}</strong>
                    </a-descriptions-item>
                    <a-descriptions-item label="Apartment type" >{{registration.apartment_type.name}}</a-descriptions-item>
                    <a-descriptions-item label="Status" >
                        <a-badge status="processing" >
                            <a-tooltip title="Accept"
                                       v-if="registration.approve_status === 1"
                                       style="color: #1890ff"
                            >
                                <check-circle-filled class="text-green-700" style="font-size: large"  />
                            </a-tooltip>
                            <a-tooltip title="Denied"
                                       v-if="registration.approve_status === 2"
                                       style="color: #e80101"
                            >
                                <close-circle-filled class="text-rose-600" style="font-size: large"/>
                            </a-tooltip>
                            <a-tooltip title="Prepare approve"
                                       v-if="registration.approve_status === 0">
                                <sync-outlined style="font-size: large" />
                            </a-tooltip>
                        </a-badge>
                    </a-descriptions-item>
                    <a-descriptions-item label="Register day" :span="3">{{new Date(registration.created_at).toLocaleDateString('en-GB')}}</a-descriptions-item>
                    <a-descriptions-item label="Number floors" >{{registration.number_floors}}</a-descriptions-item>
                    <a-descriptions-item label="Number rooms" >{{registration.number_rooms}}</a-descriptions-item>
                    <a-descriptions-item label="Area" >{{registration.area}}</a-descriptions-item>

                    <a-descriptions-item style="font-size: large" label="Owner name" :span="3">
                        <strong>{{registration.name}}</strong>
                    </a-descriptions-item>
                    <a-descriptions-item label="Email">{{registration.owner_email}}</a-descriptions-item>
                    <a-descriptions-item label="Phone" :span="2">{{registration.phone}}</a-descriptions-item>

                    <a-descriptions-item label="Approve reason">{{registration.approve_reason}}</a-descriptions-item>
                </a-descriptions>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

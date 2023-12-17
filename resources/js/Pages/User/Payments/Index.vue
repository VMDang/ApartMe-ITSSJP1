<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    SyncOutlined,
    PlusCircleOutlined,
    EyeOutlined, CheckCircleFilled, CheckOutlined, ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import { Inertia } from "@inertiajs/inertia";
import useState from "ant-design-vue/lib/_util/hooks/useState.js";
import {Modal} from "ant-design-vue";

const [payments, setPayments] = useState(null);

const newPayments = usePage().props.payments.map((payment) => {
    const status = payment.invoice.status === 1 ? 'Done' : 'Waiting Approve'
    return {
        invoice_id: payment.invoice.id,
        invoice_name: payment.invoice.name,
        content: `Tenant ${payment.payment_by.name} in Room ${payment.invoice.room.name} has paid this invoice`,
        total: payment.payment_info.total,
        status: status,
    }
})

setPayments(newPayments);

const state = reactive({
    searchText: '',
    searchedColumn: '',
});

const columns = [
    {
        name: "Content",
        dataIndex: "template",
        key: "template",
    },
    {
        title: "Total",
        key: "total",
        align: "center",
        dataIndex: "total",
        sorter: (a, b) => a.total - b.total,
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        align: "center",
        filters: [
            {
                text: "Done",
                value: "Done",
            },
            {
                text: "Waiting Approve",
                value: "Waiting Approve",
            },
        ],
        onFilter: (value, record) => record.status.indexOf(value) === 0,
    },
    {
        title: "Action",
        key: "action",
        align: "center",
    },
];


const value = ref("");

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setPayments(newPayments);
    } else {
        searchValue = searchValue.toLowerCase();
        const paymentsSearch = newPayments.filter(
            (payment) =>
                payment.invoice_name.toString().toLowerCase().includes(searchValue) ||
                payment.content.toString().toLowerCase().includes(searchValue) ||
                payment.total.toString().toLowerCase().includes(searchValue)
        );
        setPayments(paymentsSearch);
    }
};

const showApproveConfirm = (record) => {
    Modal.confirm({
        title: "Are you sure approve this payment?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            null,
            `Invoice name: ${record.invoice_name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            Inertia.post(route("payments.approve", { invoice: record.invoice_id }))
        },
        onCancel() {},
    });
};

</script>

<template>
    <Head title="Room list" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px ; background-color: #fff;"
                    title="Payment management"
                    sub-title="List payment in apartment selected"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="mt-4 pb-4 float-right">
                    <a-space direction="vertical" clearIcon>
                        <a-input-search
                            v-model:value="value"
                            placeholder="input search text"
                            enter-button="Search"
                            style="width: 300px"
                            @search="onSearch"
                            allow-clear
                        >
                        </a-input-search>
                    </a-space>
                </div>
                <a-table :columns="columns" :data-source="payments">
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'template'">
                            <a-card size="small">
                                <a-card-meta :title="record.invoice_name" :description="record.content">
                                    <template #avatar>
                                        <a-avatar size="large" src="./assets/img/user.png" />
                                    </template>
                                </a-card-meta>
                            </a-card>
                        </template>
                        <template v-if="column.key === 'total'">
                        <span class="text-amber-900">
                            <strong style="font-size: x-large"> +{{record.total}}$ </strong>
                        </span>
                        </template>
                        <template v-f="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                    />
                                </a-tooltip>
                            </div>
                        </template>
                        <template v-if="column.key === 'status'">
                            <a-tooltip title="Done"
                                       v-if="record.status === 'Done'"
                                       style="color: #1890ff"
                            >
                                <check-circle-filled class="text-green-700" style="font-size: x-large"  />
                            </a-tooltip>
                            <a-tooltip title="Waiting Approve"
                                       v-if="record.status === 'Waiting Approve'"
                            >
                                <sync-outlined style="font-size: x-large"/>
                            </a-tooltip>
                        </template>
                        <template v-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                        @click="
                                        Inertia.get(
                                            route('invoices.show', {
                                                invoice: record.invoice_id,
                                            })
                                        )
                                    "
                                    />
                                </a-tooltip>
                                <a-tooltip v-if="record.status === 'Waiting Approve' && usePage().props.role === 'OWNER'" title="Approve">
                                    <check-outlined
                                        :style="{ fontSize: 19, color: '#1890ff', }"
                                        @click="showApproveConfirm(record)"
                                    />
                                </a-tooltip>
                            </div>
                        </template>
                    </template>
                </a-table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

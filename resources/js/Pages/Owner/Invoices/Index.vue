<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { createVNode, reactive, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    HomeOutlined,
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import useState from "ant-design-vue/lib/_util/hooks/useState.js";

const [invoices, setInvoices] = useState([null
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },
    // {
    //     name: "Invoice 1",
    //     room: "P 101",
    //     paymentbatch: "2023-12-01",
    //     total: 1000,
    //     status: "Paid",
    // },
    // {
    //     name: "Invoice 2",
    //     room: "P 102",
    //     paymentbatch: "2023-12-01",
    //     total: 1500,
    //     status: "Unpaid",
    // },

]);

const newInvoices = usePage().props.invoices.map((invoice) => {
    const textStatus =invoice.status === 1 ? "Active" : invoice.status === 0 ? "Lock" : "";

    if (invoice.rooms && Array.isArray(invoice.rooms)) {
        return {
            ...invoice,
            status: textStatus,
        };
    }

    return { ...invoice, status: textStatus };
});

setInvoices(newInvoices);

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setInvoices(newInvoices);
    } else {
        searchValue = searchValue.toLowerCase();
        const invoicesSearch = newInvoices.filter(
            (invoice) =>
                invoice.name.toString().toLowerCase().includes(searchValue) ||
                invoice.room.toString().toLowerCase().includes(searchValue) ||
                invoice.status.toString().toLowerCase().includes(searchValue)
                )
        setInvoicess(invoicesSearchs);
    }
};


const state = reactive({
    searchText: "",
    searchedColumn: "",
    deleteConfirmVisible: false,
    deleteTarget: null,
});
const searchInput = ref();

const columns = [
    {
        name: "Name",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Room",
        dataIndex: "room",
        key: "room",
        sorter: (a, b) => a.room.length - b.room.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Payment Batch",
        dataIndex: "paymentbatch",
        key: "paymentbatch",
        sorter: (a, b) => a.paymentbatch - b.paymentbatch,
    },
    {
        title: "Total",
        key: "total",
        dataIndex: "total",
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        filters: [
            {
                text: "Paid",
                value: "Paid",
            },
            {
                text: "Unpaid",
                value: "Unpaid",
            },
        ],
        onFilter: (value, record) => record.status.indexOf(value) === 0,
    },
    {
        title: "Actions",
        key: "actions",
        slots: { customRender: "actions" },
    },
];

const handleDelete = (record) => {
    state.deleteTarget = record;
    state.deleteConfirmVisible = true;
};

const confirmDelete = () => {
    // Your delete logic here
    const index = invoices.findIndex((invoice) => invoice.name === state.deleteTarget.name);
    if (index !== -1) {
        invoices.splice(index, 1);
        setInvoices([...invoices]);
        notification.success({
            message: "Success",
            description: "Invoice deleted successfully.",
        });
    } else {
        notification.error({
            message: "Error",
            description: "Unable to delete invoice.",
        });
    }
    state.deleteConfirmVisible = false;
    state.deleteTarget = null;
};

const cancelDelete = () => {
    state.deleteConfirmVisible = false;
    state.deleteTarget = null;
};

</script>

<template>
    <Head title="Invoices List" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="List Invoices"
                    sub-title="List Invoices selected"
                />
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="m-4 ml-0 float-left">
                <a-button type="primary" @click=" this.$inertia.visit(route('invoices.create'))">
                    <template #icon>
                        <plus-circle-outlined />
                    </template>
                    Add Invoices
                </a-button>
            </div>

            <div class="pb-4 float-right">
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

            <a-table :columns="columns" :data-source="invoices">
                <template #actions="{ text, record }">
          <span>
            <a-tooltip title="View">
              <a @click="this.$inertia.visit(route('invoices.show'))">
                <eye-outlined />
              </a>
            </a-tooltip>
            <a-divider type="vertical" />
            <a-tooltip title="Edit">
              <a @click="this.$inertia.visit(route('invoices.edit'))">
                <edit-outlined />
              </a>
            </a-tooltip>
            <a-divider type="vertical" />
            <a-tooltip title="Delete">
              <a @click="handleDelete(record)">
                <delete-outlined />
              </a>
            </a-tooltip>
          </span>
                </template>

                <template #headerCell="{ column }">
                    <template v-if="column.key === 'name'">
            <span style="color: #1890ff">
              <home-outlined />
              Invoices Name
            </span>
                    </template>
                </template>

                <template
                    #customFilterDropdown="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
                >
                    <div style="padding: 8px">
                        <a-input
                            ref="searchInput"
                            :placeholder="`Search ${column.dataIndex}`"
                            :value="selectedKeys[0]"
                            style="width: 188px; margin-bottom: 8px; display: block"
                            @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
                            @pressEnter="handleSearch(selectedKeys, confirm, column.dataIndex)"
                        />
                        <a-button
                            type="primary"
                            size="small"
                            style="width: 90px; margin-right: 8px"
                            @click="handleSearch(selectedKeys, confirm, column.dataIndex)"
                        >
                            <template #icon><search-outlined /></template>
                            Search
                        </a-button>
                        <a-button size="small" style="width: 90px" @click="handleReset(clearFilters)">
                            Reset
                        </a-button>
                    </div>
                </template>
            </a-table>

            <!-- Delete Confirmation Modal -->
            <a-modal
                title="Confirm Delete"
                :visible="state.deleteConfirmVisible"
                @ok="confirmDelete"
                @cancel="cancelDelete"
            >
                <p>Are you sure you want to delete this invoice?</p>
            </a-modal>
        </div>
    </AuthenticatedLayout>
</template>

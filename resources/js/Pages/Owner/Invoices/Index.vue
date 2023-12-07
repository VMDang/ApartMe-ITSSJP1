<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { createVNode, reactive, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    DeleteOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    EyeOutlined,
    PlusCircleOutlined,
    SearchOutlined,
    CheckCircleFilled,
    CloseCircleFilled
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import useState from "ant-design-vue/lib/_util/hooks/useState.js";
import { Inertia } from "@inertiajs/inertia";

const [invoices, setInvoices] = useState(null);

const state = reactive({
    searchText: "",
    searchedColumn: "",
    deleteConfirmVisible: false,
    deleteTarget: null,
});
const searchInput = ref();
const value = ref("");

const newInvoices = usePage().props.rooms.flatMap((room) => {
    if (room.invoices.length >= 1) {
        return room.invoices.map((invoice) => {
            // const formattedDeadline = new Date(invoice.deadline).toLocaleDateString('en-GB');

            return {
                room_name: room.name,
                status_new:
                    invoice.status === 1
                        ? "Done"
                        : invoice.status === 0
                        ? "Not Done"
                        : "",
                ...invoice,
                // deadline: formattedDeadline,
            };
        });
    }
    return [];
});

setInvoices(newInvoices);

const handleSearch = (selectedKeys, confirm, dataIndex) => {
    confirm();
    state.searchText = selectedKeys[0];
    state.searchedColumn = dataIndex;
};
const handleReset = (clearFilters) => {
    clearFilters({
        confirm: true,
    });
    state.searchText = "";
};

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setInvoices(newInvoices);
    } else {
        searchValue = searchValue.toLowerCase();
        const invoicesSearch = newInvoices.filter(
            (invoice) =>
                invoice.name.toString().toLowerCase().includes(searchValue) ||
                invoice.room_name
                    .toString()
                    .toLowerCase()
                    .includes(searchValue) ||
                invoice.description
                    .toString()
                    .toLowerCase()
                    .includes(searchValue) ||
                invoice.total.toString().toLowerCase().includes(searchValue) ||
                invoice.deadline
                    .toString()
                    .toLowerCase()
                    .includes(searchValue) ||
                invoice.status_new
                    .toString()
                    .toLowerCase()
                    .includes(searchValue)
        );
        setInvoices(invoicesSearch);
    }
};

const columns = [
    {
        name: "Name",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
        customFilterDropdown: true,
        onFilter: (value, record) =>
            record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Room",
        dataIndex: "room_name",
        key: "room_name",
        sorter: (a, b) => a.room.length - b.room.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Description",
        dataIndex: "description",
        key: "description",
    },
    {
        title: "Deadline",
        key: "deadline",
        dataIndex: "deadline",
        sorter: (a, b) => new Date(a.deadline) - new Date(b.deadline),
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
        key: "status_new",
        dataIndex: "status_new",
        align: "center",
        filters: [
            {
                text: "Done",
                value: "Done",
            },
            {
                text: "Not Done",
                value: "Not Done",
            },
        ],
        onFilter: (value, record) => record.status.indexOf(value) === 0,
    },
    {
        title: "Action",
        key: "action",
        align: "center",
        // slots: { customRender: "actions" },
    },
];

const showDeleteConfirm = (record) => {
    Modal.confirm({
        title: "Are you sure delete this invoice?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
            `Invoice name: ${record.name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            Inertia.delete(route("invoices.destroy", { invoice: record.id }), {
                onSuccess: () => {
                    openNotification(
                        "success",
                        "Successfully Deleted ",
                        `Invoice ${record.name} has been successfully deleted`
                    );
                },
                onError: () => {
                    openNotification(
                        "error",
                        "Somethings went wrong",
                        "An error occurred while updating the database"
                    );
                },
            });
        },
        onCancel() {},
    });
};

const openNotification = (type, message, description) => {
    notification[type]({
        message: message,
        description: description,
    });
};
</script>

<template>
    <Head title="Invoices List" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                    "
                    sub-title="List Invoices in Apartment Selected"
                    title="List Invoices"
                />
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="m-4 ml-0 float-left">
                <a-button
                    type="primary"
                    @click="Inertia.get(route('invoices.create'))"
                >
                    <template #icon>
                        <plus-circle-outlined />
                    </template>
                    Add Invoices
                </a-button>
            </div>

            <div class="pb-4 float-right">
                <a-space clearIcon direction="vertical">
                    <a-input-search
                        v-model:value="value"
                        allow-clear
                        enter-button="Search"
                        placeholder="input search text"
                        style="width: 300px"
                        @search="onSearch"
                    >
                    </a-input-search>
                </a-space>
            </div>

            <a-table :columns="columns" :data-source="invoices">
                <template #headerCell="{ column }">
                    <template v-if="column.key === 'name'">
                        <span style="color: #1890ff"> Invoices Name </span>
                    </template>
                </template>

                <template
                    #customFilterDropdown="{
                        setSelectedKeys,
                        selectedKeys,
                        confirm,
                        clearFilters,
                        column,
                    }"
                >
                    <div style="padding: 8px">
                        <a-input
                            ref="searchInput"
                            :placeholder="`Search ${column.dataIndex}`"
                            :value="selectedKeys[0]"
                            style="
                                width: 188px;
                                margin-bottom: 8px;
                                display: block;
                            "
                            @change="
                                (e) =>
                                    setSelectedKeys(
                                        e.target.value ? [e.target.value] : []
                                    )
                            "
                            @pressEnter="
                                handleSearch(
                                    selectedKeys,
                                    confirm,
                                    column.dataIndex
                                )
                            "
                        />
                        <a-button
                            size="small"
                            style="width: 90px; margin-right: 8px"
                            type="primary"
                            @click="
                                handleSearch(
                                    selectedKeys,
                                    confirm,
                                    column.dataIndex
                                )
                            "
                        >
                            <template #icon>
                                <search-outlined />
                            </template>
                            Search
                        </a-button>
                        <a-button
                            size="small"
                            style="width: 90px"
                            @click="handleReset(clearFilters)"
                        >
                            Reset
                        </a-button>
                    </div>
                </template>
                <template #customFilterIcon="{ filtered }">
                    <search-outlined
                        :style="{ color: filtered ? '#108ee9' : undefined }"
                    />
                </template>

                <template #bodyCell="{ column, record }">
                    <template v-if="column.key === 'status_new'">
                        <span
                            v-if="record.status_new === 'Done'"
                            style="color: #1890ff"
                        >
                            <check-circle-filled class="text-green-700" style="font-size: x-large"  />
                        </span>
                        <span
                            v-if="record.status_new === 'Not Done'"
                            style="color: #e80101"
                        >
                            <close-circle-filled class="text-rose-600" style="font-size: x-large"/>
                        </span>
                    </template>
                    <template v-if="column.key === 'total'">
                        <span class="text-amber-900">
                            <strong style="font-size: x-large"> +{{record.total}}$ </strong>
                        </span>
                    </template>
                    <template v-if="column.key === 'action'">
                        <div class="flex gap-3 justify-center">
                            <a-tooltip title="Detail">
                                <eye-outlined
                                    :style="{ fontSize: 19 }"
                                    @click="
                                        Inertia.get(
                                            route('invoices.show', {
                                                invoice: record.id,
                                            })
                                        )
                                    "
                                />
                            </a-tooltip>
                            <a-tooltip title="Edit">
                                <edit-outlined
                                    :style="{
                                        fontSize: 19,
                                        color: '#1890ff',
                                    }"
                                    @click="
                                        Inertia.get(
                                            route('invoices.edit', {
                                                invoice: record.id,
                                            })
                                        )
                                    "
                                />
                            </a-tooltip>
                            <a-tooltip title="Delete">
                                <delete-outlined
                                    :style="{
                                        fontSize: 19,
                                        color: '#e80101',
                                    }"
                                    @click="showDeleteConfirm(record)"
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

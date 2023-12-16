<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input, Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";

import {
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    FormOutlined,
} from "@ant-design/icons-vue";
import useState from "ant-design-vue/es/_util/hooks/useState.js";
import {format} from "date-fns";
import Create from "@/Pages/Owner/Invoices/Create.vue";

const [tenants, setTenants] = useState(null);


const newTenants = usePage().props.tenants.map((tenant) => {
    const formattedDate = format(new Date(tenant.created_at), 'yyyy MMMM do')
    return { ...tenant, created_at: formattedDate}
});
setTenants(newTenants)

const state = reactive({
    searchText: '',
    searchedColumn: '',
});
const searchInput = ref();

const columns = [
    {
        title: "",
        dataIndex: "icon",
        key: "icon",
        align: "center",
    },
    {
        title: "Full name",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "",
        dataIndex: "icon2",
        key: "icon2",
        align: "center",
    },
    {
        title: "Rent date",
        dataIndex: "created_at",
        key: "created_at"
    },
    {
        title: "Action",
        key: "action",
        align: "center",
    },
];

const value = ref("");

const handleSearch = (selectedKeys, confirm, dataIndex) => {
    confirm();
    state.searchText = selectedKeys[0];
    state.searchedColumn = dataIndex;
};
const handleReset = clearFilters => {
    clearFilters({
        confirm: true,
    });
    state.searchText = '';
};

const showDeleteConfirm = (record) => {
    Modal.confirm({
        title: "Are you sure you want to delete this tenant?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
                `Tenant name: ${record.name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",

        onCancel() {},
        onOk(){
            Inertia.delete(route("tenant-accounts.destroy", { user: record.id }))
        },
    });
};

const showDetailsModal = (record) => {
    Modal.info({
        title: "Details of the tenant's account",
        icon: createVNode(EyeOutlined),
        content: createVNode(
            "div",
            record,
            [
                createVNode("table", { class: "custom-table" }, [
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Full Name"),
                        createVNode("td", { class: "data-column" }, record.name),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Phone Number"),
                        createVNode("td", { class: "data-column" }, record.phone),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Email"),
                        createVNode("td", { class: "data-column" }, record.email),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Rooms"),
                        createVNode("td", { class: "data-column" }, [
                            record.rooms.map((room) => {
                                return [
                                    createVNode("a", { class: "text-blue-600",
                                        onclick: () => {
                                            Inertia.get(route("rooms.show", { room: room.id }))
                                        }},
                                    room.name),
                                    createVNode("br")
                                ]
                            })
                        ])
                    ]),
                ])
            ]
        ),
        closable: true,
        okText: "Close",
        onOk() {},
    });
};


const colorList = ['#f56a00', '#7265e6', '#ffbf00', '#00a2ae'];
let currentColorIndex = 0
const getColor = (index) => {
    const color = colorList[currentColorIndex];
    currentColorIndex = (currentColorIndex + 1) % colorList.length;
    return color;
}

</script>

<template>
    <Head title="List Tenants" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="Tenant Management"
                    sub-title="List tenants in apartment"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="Inertia.get(route('tenant-accounts.create'))"
                    >
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Create Tenant
                    </a-button>
                </div>
                <div class="pb-4 float-right">
                    <a-space direction="vertical" clearIcon>
                        <a-input-search
                            v-model:value="value"
                            placeholder="Input search text"
                            enter-button="Search"
                            style="width: 300px"
                            @search="handleSearch"
                            allow-clear
                        >
                        </a-input-search>
                    </a-space>
                </div>
                <a-table :columns="columns" :data-source="tenants">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span style="color: #0a43d3"> Full Name </span>
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
                    <template #customFilterIcon="{ filtered }">
                        <search-outlined :style="{ color: filtered ? '#108ee9' : undefined }" />
                    </template>

                    <template #bodyCell="{index, column, record }" :key="index">
                        <template v-if="column.key === 'icon'">
                            <a-avatar size="large" src="./assets/img/user.png" />
                        </template>
                        <template v-if="column.key === 'name'">
                            <span class="underline ">
                                <strong class="text-blue-700 font-bold"> {{record.name}} </strong>
                            </span>
                        </template>
                        <template v-if="column.key === 'icon2'">
                            <a-avatar size="large" src="./assets/img/home.png" />
                        </template>
                        <template v-if="column.key === 'created_at'">
                            {{record.created_at}} - Now
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                        @click="showDetailsModal(record)"
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
<style >
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border-radius: 10px;
}

.custom-table td {
    border: 1px solid #ddd;
    padding: 8px;
    border-radius: 8px;
}

.label-column {
    font-weight: bold;
    color: #333;
}

.data-column {
    color: #666;
}

.label-column-reply{
    font-weight: bold;
    color: #333;
    padding: 10px;
}
@import url('https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css');


</style>


<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { createVNode, reactive, ref, defineProps, defineComponent} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input, Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

import {
    HomeOutlined,
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    InfoCircleOutlined,
    FormOutlined
} from "@ant-design/icons-vue";

const props = defineProps({
    facilities: Array
});

const components = defineComponent({
    AuthenticatedLayout: AuthenticatedLayout
})

var facilities = props.facilities;
console.log(facilities);

const state = reactive({
    searchText: '',
    searchedColumn: '',
});

const searchInput = ref();

const columns = [{
    title: "Name",
    dataIndex: "name",
    key: "name",
    sorter: (a, b) => a.name.length - b.name.length,
    sortDirections: ["descend", "ascend"],
    customFilterDropdown: true,
    onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
}, {
    title: "Cost",
    dataIndex: "cost",
    key: "cost",
    sorter: (a, b) => a.cost - b.cost,
}, {
    title: "Description",
    dataIndex: "description",
    key: "description",
    sorter: (a, b) => a.description.length - b.description.length,
    sortDirections: ["descend", "ascend"],
}, {
    title: "Action",
    key: "action",
    align: "center",
}
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
        title: "Are you sure you want to delete?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
            `Facility name: ${record.name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onCancel() { },
        onOk(){
            const id = record.id;
            const url = route("facility.destroy", { id: id });
            Inertia.delete(url);
            window.location.reload();
        }
    });
};
const showDetailsModal = (record) => {
    console.log("showDetailsModal called with record:", record);
    Modal.info({
        title: "Detail facility",
        icon: createVNode(EyeOutlined),
        content: createVNode(
            "div",
            null,
            [
                createVNode("table", { class: "custom-table" }, [
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Name"),
                        createVNode("td", { class: "data-column" }, record.name),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Cost"),
                        createVNode("td", { class: "data-column" }, record.cost),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Description"),
                        createVNode("td", { class: "data-column" }, record.description),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Room id"),
                        createVNode("td", { class: "data-column" }, record.room_id),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column" }, "Created at"),
                        createVNode("td", { class: "data-column" }, record.created_at),
                    ]),
                ])
            ]
        ),
        okText: "Close",
        onOk() { },
    });
};
const showReplyModal = (record) => {
    console.log("showReplyModal called with record:", record);
    // State để lưu trữ giá trị nhập từ người dùng
    const formData = reactive({
        name: "",
        cost: "",
        description: "",
        room_id: ""
    });
    Modal.info({
        title: "New facility",
        icon: createVNode(FormOutlined),
        content: createVNode(
            "div",
            null,
            [
                createVNode("table", { class: "custom-table-reply" }, [
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column-reply" }, "Name"),
                        createVNode("td", { class: "data-column-reply" }, [
                            // Sử dụng Input để người dùng nhập vào
                            createVNode(Input, {
                                onChange: (e) => (formData.name = e.target.value),
                            }),
                        ]),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column-reply" }, "Cost"),
                        createVNode("td", { class: "data-column-reply" }, [
                            createVNode(Input, {
                                onChange: (e) => (formData.cost = e.target.value),
                            }),
                        ]),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column-reply" }, "Description"),
                        createVNode("td", { class: "data-column-reply" }, [
                            createVNode(Input, {
                                onChange: (e) => (formData.description = e.target.value),
                            }),
                        ]),
                    ]),
                    createVNode("tr", null, [
                        createVNode("td", { class: "label-column-reply" }, "Room"),
                        createVNode("td", { class: "data-column-reply" }, [
                            createVNode(Input, {
                                onChange: (e) => (formData.room_id = e.target.value),
                            }),
                        ]),
                    ]),
                ]),
            ]
        ),
        okText: "Create",
        onOk() {
            Inertia.post(route("facility.store"), {
            name: formData.name,
            description: formData.description,
            cost: formData.cost,
            room_id: formData.room_id,
                onFinish: () => {
                    formData.reset('name', 'description', 'cost', 'room_id');
                }
            });
            console.log("Form Data:", formData);
        },
    });
};

</script>

<template>
    <Head title="Facility list" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px" title="List of facilities" />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button type="primary" @click="showReplyModal(record)">
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Create facility
                    </a-button>
                </div>
                <div class="pb-4 float-right">
                    <a-space direction="vertical" clearIcon>
                        <a-input-search v-model:value="value" placeholder="Input search text" enter-button="Search"
                            style="width: 300px" @search="handleSearch" allow-clear>
                        </a-input-search>
                    </a-space>
                </div>
                <a-table :columns="columns" :data-source="facilities">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span>
                                Name
                            </span>
                        </template>
                    </template>

                    <template #customFilterDropdown="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }">
                        <div style="padding: 8px">
                            <a-input ref="searchInput" :placeholder="`Search ${column.dataIndex}`" :value="selectedKeys[0]"
                                style="width: 188px; margin-bottom: 8px; display: block"
                                @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
                                @pressEnter="handleSearch(selectedKeys, confirm, column.dataIndex)" />
                            <a-button type="primary" size="small" style="width: 90px; margin-right: 8px"
                                @click="handleSearch(selectedKeys, confirm, column.dataIndex)">
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

                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'name'">

                        </template>

                        <template v-else-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined :style="{ fontSize: 19 }" @click="showDetailsModal(record)" />
                                </a-tooltip>
                                <a-tooltip title="Edit">
                                    <form-outlined :style="{
                                        fontSize: 19,
                                        color: '#1890ff',
                                    }" @click="showReplyModal(record)" />
                                </a-tooltip>
                                <a-tooltip title="Delete">
                                    <delete-outlined :style="{
                                        fontSize: 19,
                                        color: '#e80101',
                                    }" @click="showDeleteConfirm(record)" />
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

.label-column-reply {
    font-weight: bold;
    color: #333;
    padding: 10px;
}
</style>

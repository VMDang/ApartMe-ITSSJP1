<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { createVNode, reactive, ref, defineProps, defineComponent } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input, Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";

import {
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    FormOutlined,
} from "@ant-design/icons-vue";
import useState from "ant-design-vue/es/_util/hooks/useState.js";

const [facilities, setFacilities] = useState(null);

const newFacilities = usePage().props.rooms.flatMap((room) => {
    if (room.facilities.length >= 1) {
        return room.facilities.map((facility) => {
            return {
                room_name: room.name,
                ...facility,
            };
        });
    }
    return [];
});

setFacilities(newFacilities)

const state = reactive({
    searchText: "",
    searchedColumn: "",
});

const searchInput = ref();

const columns = [
    {
        title: "Name Facility",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Cost",
        dataIndex: "cost",
        key: "cost",
        sorter: (a, b) => a.cost - b.cost,
    },
    {
        title: "Room",
        dataIndex: "room_name",
        key: "room_name",
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
const handleReset = (clearFilters) => {
    clearFilters({
        confirm: true,
    });
    state.searchText = "";
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
        onCancel() {},
        onOk() {
            const id = record.id;
            const url = route("facility.destroy", { id: id });
            Inertia.delete(url);
            window.location.reload();
        },
    });
};
const showDetailsModal = (record) => {
    Modal.info({
        title: "Detail facility",
        icon: createVNode(EyeOutlined),
        content: createVNode("div", null, [
            createVNode("table", { class: "custom-table" }, [
                createVNode("tr", null, [
                    createVNode("td", { class: "label-column" }, "Name"),
                    createVNode("td", { class: "data-column" }, record.name),
                ]),
                createVNode("tr", null, [
                    createVNode("td", { class: "label-column" }, "Cost"),
                    createVNode("td", { class: "data-column" }, `${record.cost}$`),
                ]),
                createVNode("tr", null, [
                    createVNode("td", { class: "label-column" }, "Room"),
                    createVNode("td", { class: "data-column" }, [
                        createVNode("a", { class: "text-blue-600",
                                onclick: () => {
                                    Inertia.get(route("rooms.show", { room: record.id }))
                                }},
                            record.room_name),
                    ])
                ]),
                createVNode("tr", null, [
                    createVNode("td", { class: "label-column" }, "Description"),
                    createVNode(
                        "td",
                        { class: "data-column" },
                        record.description
                    ),
                ]),
            ]),
        ]),
        closable: true,
        okText: "Close",
        onOk() {},
    });
};

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setFacilities(newFacilities);
    } else {
        searchValue = searchValue.toLowerCase();
        const facilitiesSearch = newFacilities.filter(
            (invoice) =>
                invoice.name.toString().toLowerCase().includes(searchValue) ||
                invoice.room_name
                    .toString()
                    .toLowerCase()
                    .includes(searchValue) ||
                invoice.description
                    .toString()
                    .toLowerCase()
                    .includes(searchValue)
        );
        setFacilities(facilitiesSearch);
    }
};

</script>

<template>
    <Head title="Facilities" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                    "
                    title="Facilities Management"
                    sub-title="List facilities in aprtment selected"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button type="primary" @click="Inertia.get(route('facilities.create'))">
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Create facility
                    </a-button>
                </div>
                <div class="pb-4 float-right">
                    <a-space direction="vertical" clearIcon>
                        <a-input-search
                            v-model:value="value"
                            placeholder="Input search text"
                            enter-button="Search"
                            style="width: 300px"
                            @search="onSearch"
                            allow-clear
                        >
                        </a-input-search>
                    </a-space>
                </div>
                <a-table :columns="columns" :data-source="facilities">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span style="color: #0a43d3"> Name Facility </span>
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
                                            e.target.value
                                                ? [e.target.value]
                                                : []
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
                                type="primary"
                                size="small"
                                style="width: 90px; margin-right: 8px"
                                @click="
                                    handleSearch(
                                        selectedKeys,
                                        confirm,
                                        column.dataIndex
                                    )
                                "
                            >
                                <template #icon><search-outlined /></template>
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
                        <template v-if="column.key === 'name'">
                            <span class="underline ">
                                <strong class="text-blue-600 font-bold"> {{record.name}} </strong>
                            </span>
                        </template>

                        <template v-if="column.key === 'cost'">
                            <span class="text-amber-900">
                                <strong style="font-size: medium"> {{record.cost}}$ </strong>
                            </span>
                        </template>

                        <template v-if="column.key === 'room_name'">
                            <span>
                                <a-tag :color="'geekblue'">
                                    {{ record.room_name }}
                                </a-tag>
                            </span>
                        </template>

                        <template v-else-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                        @click="showDetailsModal(record)"
                                    />
                                </a-tooltip>
                                <a-tooltip title="Edit">
                                    <edit-outlined
                                        :style="{
                                            fontSize: 19,
                                            color: '#1890ff',
                                        }"
                                        @click="Inertia.get(route('facilities.edit', { facility: record.id }))"
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

<style>
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

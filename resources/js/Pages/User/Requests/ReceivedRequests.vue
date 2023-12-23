<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input, Modal } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";

import {
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    FormOutlined, CloseCircleFilled, CheckCircleFilled, SyncOutlined,
} from "@ant-design/icons-vue";
import useState from "ant-design-vue/es/_util/hooks/useState.js";

const [requests, setRequests] = useState(null);
const [detail, setDetail] = useState(null);

const state = reactive({
    searchText: '',
    searchedColumn: '',
});
const searchInput = ref();

const newRequests = usePage().props.requests.map((request) => {
    return {
        ...request,
        created_at: new Date(request.created_at).toLocaleDateString('en-GB')
    }
})

setRequests(newRequests);

const columns = [
    {
        title: "Title",
        dataIndex: "title",
        key: "title",
        customFilterDropdown: true,
        onFilter: (value, record) => record.title.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Sender",
        dataIndex: "sender",
        key: "sender",
    },
    {
        title: "Date created",
        dataIndex: "created_at",
        key: "content",
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
        setRequests(newRequests);
    } else {
        searchValue = searchValue.toLowerCase();
        const requestsSearch = newRequests.filter(
            (request) =>
                request.title.toString().toLowerCase().includes(searchValue) ||
                request.created_at.toString().toLowerCase().includes(searchValue) ||
                request.users.some((sender) =>
                    sender.name.toLowerCase().includes(searchValue)
                )
        );
        setRequests(requestsSearch);
    }
};

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

const open = ref(false);
const openDetailModal = (record) => {
    setDetail(record)
    open.value = true;
};

</script>

<template>
    <Head title="Message list" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px; background-color: white;"
                    title="List requests received"

                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="Inertia.get(route('requests.create'))"
                    >
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Create message
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
                <a-table :columns="columns" :data-source="requests">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'title'">
                            <span style="color: #0a43d3"> Title </span>
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

                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'title'">
                            <span class="text-blue-600 ">
                                {{record.title}}
                            </span>
                        </template>
                        <template v-if="column.key === 'sender'">
                            <span>
                                <a-tag
                                    v-for="sender in record.users"
                                    :color="'geekblue'"
                                >
                                    {{ sender.name }}
                                </a-tag>
                            </span>
                        </template>
                        <template v-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                        @click="openDetailModal(record)"
                                    />
                                </a-tooltip>
                            </div>
                        </template>
                    </template>
                </a-table>
                <a-modal v-model:open="open" title="Detail request mail">
                    <a-descriptions title="" bordered>
                        <a-descriptions-item label="Title" :span="3"><strong>{{detail.title}}</strong></a-descriptions-item>

                        <a-descriptions-item label="Sender" :span="3">
                            <span>
                                <a-tag
                                    v-for="r in detail.users"
                                    :color="'geekblue'"
                                >
                                    {{ r.name }} ({{ r.email }})
                                </a-tag>
                            </span>
                        </a-descriptions-item>
                        <a-descriptions-item label="Date created" :span="3">
                            {{ detail.created_at }}
                        </a-descriptions-item>

                        <a-descriptions-item label="Content">
                            {{ detail.content }}
                        </a-descriptions-item>
                    </a-descriptions>
                </a-modal>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Inertia} from "@inertiajs/inertia";
import {EyeOutlined, SearchOutlined, SmileOutlined} from "@ant-design/icons-vue";
import {reactive, ref} from "vue";

const room = ref(usePage().props.room)
room.value.status = room.value.status === 1 ? "Active" : room.value.status === 0 ? "Lock" : ""

const tenant = usePage().props.room.users.map((user) => {
    return { ...user, status: user.status === 1 ? "Active" : user.status === 0 ? "Lock" : ""}
})

const state = reactive({
    searchText: '',
    searchedColumn: '',
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
        title: "Email",
        dataIndex: "email",
        key: "email",
        sorter: (a, b) => a.email.length - b.email.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Phone",
        dataIndex: "phone",
        key: "phone",
        sorter: (a, b) => a.phone.length - b.phone.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        filters: [
            {
                text: "Active",
                value: "Active",
            },
            {
                text: "Lock",
                value: "Lock",
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

</script>
<template>
    <Head title="Show room" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="Detail room"
                    sub-title="Room information and list teanant"
                    @back="() => Inertia.get(route('rooms.index'))"
                >
                    <a-descriptions size="small" :column="3">
                        <a-descriptions-item label="Room name">{{ room.name }}</a-descriptions-item>
                        <a-descriptions-item label="Status"> {{room.status}} </a-descriptions-item>
                        <a-descriptions-item label="Apartment">{{ room.apartment.address }}</a-descriptions-item>
                        <a-descriptions-item label="Floor"> {{ room.floor }} </a-descriptions-item>
                        <a-descriptions-item label="Area (m&#178)"> {{ room.area }} </a-descriptions-item>
                    </a-descriptions>
                </a-page-header>
            </div>

            <div class="max-w-7xl mt-16 mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-table :columns="columns" :data-source="tenant">
                    <template #headerCell="{ column }" >
                        <template v-if="column.key === 'name'">
                            <span style="color: #1890ff">
                                <smile-outlined />
                                Name
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
                    <template #customFilterIcon="{ filtered }">
                        <search-outlined :style="{ color: filtered ? '#108ee9' : undefined }" />
                    </template>

                    <template #bodyCell="{ column, record }">
                        <template v-if="column.key === 'name'">
                            <a @click="">
                                {{ record.name }}
                            </a>
                        </template>
                        <template v-else-if="column.key === 'status'">
                            <span
                                v-if="record.status === 'Active'"
                                style="color: #1890ff"
                            >
                                Active
                            </span>
                            <span
                                v-if="record.status === 'Lock'"
                                style="color: #e80101"
                            >
                                Lock
                            </span>
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined
                                        :style="{ fontSize: 19 }"
                                        @click=""
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

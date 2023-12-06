<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    HomeOutlined,
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    EditOutlined,
    ExclamationCircleOutlined,
    SearchOutlined
} from "@ant-design/icons-vue";
import { Modal, notification } from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";
import useState from "ant-design-vue/lib/_util/hooks/useState.js";

const [rooms, setRooms] = useState(null);

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
        title: "Floor",
        dataIndex: "floor",
        key: "floor",
        sorter: (a, b) => a.floor.length - b.floor.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Area",
        dataIndex: "area",
        key: "area",
        sorter: (a, b) => a.area - b.area,
    },
    {
        title: "Tenants",
        key: "tenants",
        dataIndex: "tenants",
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

const newRooms = usePage().props.rooms.map((room) => {
    const textStatus = room.status === 1 ? "Active" : room.status === 0 ? "Lock" : "";

    if (room.users && Array.isArray(room.users)) {
        return {
            ...room,
            tenants: room.users.map((user) => user.name),
            status: textStatus,
        };
    }

    return { ...room, status: textStatus };
});

setRooms(newRooms);

const value = ref("");

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setRooms(newRooms);
    } else {
        searchValue = searchValue.toLowerCase();
        const roomsSearch = newRooms.filter(
            (room) =>
                room.name.toString().toLowerCase().includes(searchValue) ||
                room.floor.toString().toLowerCase().includes(searchValue) ||
                room.area.toString().toLowerCase().includes(searchValue) ||
                room.status.toString().toLowerCase().includes(searchValue) ||
                room.users.some((tenant) =>
                    tenant.name.toLowerCase().includes(searchValue)
                )
        );
        setRooms(roomsSearch);
    }
};

const openNotification = (type, message, description) => {
    notification[type]({
        message: message,
        description: description,
    });
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

const showDeleteConfirm = (record) => {
    Modal.confirm({
        title: "Are you sure delete this room?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
            `Room name: ${record.name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            Inertia.delete(route("rooms.destroy", { room: record.id }), {
                onSuccess: () => {
                    openNotification(
                        "success",
                        "Successfully Deleted ",
                        `Room ${record.name} has been successfully deleted`
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
</script>

<template>
    <Head title="Room list" />

    <AuthenticatedLayout>
        <!--        <template #header>-->
        <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
        <!--                Room list-->
        <!--            </h2>-->
        <!--        </template>-->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px ; background-color: #fff;"
                    title="List rooms"
                    sub-title="List rooms in apartment selected"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="Inertia.get(route('rooms.create'))"
                    >
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Add room
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
                <a-table :columns="columns" :data-source="rooms">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span style="color: #1890ff">
                                <home-outlined />
                                Room name
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
                            <a @click="Inertia.get(route('rooms.show', {room: record.id}))">
                                {{ record.name }}
                            </a>
                            </template>
                        <template v-else-if="column.key === 'tenants'">
                                <span>
                                    <a-tag
                                        v-for="tenant in record.tenants"
                                        :color="'geekblue'"
                                    >
                                        {{ tenant }}
                                    </a-tag>
                                </span>
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
                                            @click="
                                                Inertia.get(
                                                    route('rooms.show', {
                                                        room: record.id,
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
                                                    route('rooms.edit', {
                                                        room: record.id,
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

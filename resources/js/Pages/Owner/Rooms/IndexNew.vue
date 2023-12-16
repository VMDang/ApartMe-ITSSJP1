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
import { Inertia } from "@inertiajs/inertia";
import useState from "ant-design-vue/lib/_util/hooks/useState.js";

const [rooms, setRooms] = useState(null);

const state = reactive({
    searchText: "",
    searchedColumn: "",
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
        onFilter: (value, record) =>
            record.name.toString().toLowerCase().includes(value.toLowerCase()),
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
    const textStatus =
        room.status === 1 ? "Active" : room.status === 0 ? "Lock" : "";

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
const handleReset = (clearFilters) => {
    clearFilters({
        confirm: true,
    });
    state.searchText = "";
};

const title = `Apartment: ${rooms.value[0].apartment.address}`;

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
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                        background-color: #fff;
                    "
                    :title="title"
                    sub-title="List rooms in apartment"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex justify-between items-center">
                    <div class="m-4">
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

                    <div class="pb-4">
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
                </div>

                <div>
                    <a-list
                        :grid="{ gutter: 16, column: 3 }"
                        :data-source="rooms"
                    >
                        <template #renderItem="{ item }">
                            <a-list-item>
                                <a-card
                                    :title="item.name"
                                    class="shadow-md mb-4"
                                    head-style="text-align: center; font-size: x-large; font-weight:700; color: #0a43d3"
                                >
                                    <p><b>Floor :</b> {{ item.floor }}</p>
                                    <p><b>Area :</b> {{ item.area }} (m&#178)</p>
                                    <p>
                                        <b>Tenants : </b>
                                        <span>
                                            <a-tag
                                                v-for="tenant in item.tenants"
                                                :color="'geekblue'"
                                            >
                                                {{ tenant }}
                                            </a-tag>
                                        </span>
                                    </p>
                                    <p>
                                        <b>Status : </b>
                                        <span
                                            v-if="item.status === 'Active'"
                                            style="color: #1890ff"
                                        >
                                            Active
                                        </span>
                                        <span
                                            v-if="item.status === 'Lock'"
                                            style="color: #e80101"
                                        >
                                            Lock
                                        </span>
                                    </p>
                                    <template #actions>
                                        <a-tooltip title="Detail">
                                            <eye-outlined
                                                :style="{ fontSize: 19 }"
                                                @click="
                                                    Inertia.get(
                                                        route('rooms.show', {
                                                            room: item.id,
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
                                                            room: item.id,
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
                                                @click="showDeleteConfirm(item)"
                                            />
                                        </a-tooltip>
                                    </template>
                                </a-card>
                            </a-list-item>
                        </template>
                    </a-list>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

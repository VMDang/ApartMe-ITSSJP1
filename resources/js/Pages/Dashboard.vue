<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import {createVNode, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {
    CheckCircleFilled,
    CloseCircleFilled,
    DeleteOutlined,
    EditOutlined,
    EyeOutlined,
    FileDoneOutlined, SearchOutlined
} from "@ant-design/icons-vue";
import useState from "ant-design-vue/es/_util/hooks/useState.js";
import {format} from "date-fns";
import {Modal} from "ant-design-vue";

const [rooms, setRooms] = useState(null);
const [invoices, setInvoices] = useState(null);

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

const newInvoices = usePage().props.invoices.flatMap((room) => {
    if (room.invoices.length >= 1) {
        return room.invoices.map((invoice) => {
            return {
                room_name: room.name,
                status_new:
                    invoice.status === 1
                        ? "Done"
                        : invoice.status === 0
                            ? "Not Done"
                            : "",
                ...invoice,
            };
        });
    }
    return [];
});

setInvoices(newInvoices);

const columnsInvoice = [
    {
        name: "Name",
        dataIndex: "name",
        key: "name",
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Total",
        key: "total",
        align: "center",
        dataIndex: "total",
    },
    {
        title: "Status",
        key: "status_new",
        dataIndex: "status_new",
        align: "center",
    },
    {
        title: "Action",
        key: "action",
        align: "center",
    },
];

const [tenants, setTenants] = useState(null);

const newTenants = usePage().props.tenants.map((tenant) => {
    const formattedDate = format(new Date(tenant.created_at), 'yyyy MMMM do')
    return { ...tenant, created_at: formattedDate}
});
setTenants(newTenants)

const columnsTenant = [
    {
        title: "",
        dataIndex: "icon",
        key: "icon",
        align: "center",
    },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
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

</script>

<template>
     <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                        background-color: #ffffff;
                    "
                    title="Dashboard"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-row>
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="15"
                        :lg="15"
                        class="left-column mt-8 shadow-md"
                    >
                        <a-descriptions class="ml-4 mt-4" title="Apartment Management"/>
                        <a-list
                            :grid="{  column: 2 }"
                            :data-source="rooms"
                        >
                            <template #renderItem="{ item }">
                                <a-list-item>
                                    <a-card
                                        :title="item.name"
                                        class="shadow-md mb-4"
                                        head-style="text-align: center; font-size: large; font-weight:500; color: #0a43d3"
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
                                        </template>
                                    </a-card>
                                </a-list-item>
                            </template>
                        </a-list>

                    </a-col>

                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="9"
                        :lg="9"
                        class="mt-8 shadow-md"
                    >
                        <a-row class="ml-4" :xs="12" :sm="24" :md="9" :lg="9">
                            <a-descriptions class="mt-4" title="Invoices management"/>
                            <a-table style="width: 430px" :columns="columnsInvoice" :data-source="invoices">
                                <template #headerCell="{ column }">
                                    <template v-if="column.key === 'name'">
                                        <span style="color: #0a43d3"> Invoices Name </span>
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
                                    <template  v-if="column.key === 'name'">
                                        <span class="text-blue-600"> {{ record.name }} </span>
                                    </template>
                                    <template v-if="column.key === 'status_new'">
                                        <a-tooltip title="Done"
                                                   v-if="record.status_new === 'Done'"
                                                   style="color: #1890ff"
                                        >
                                            <check-circle-filled class="text-green-700" style="font-size: large"  />
                                        </a-tooltip>
                                        <a-tooltip title="Not Done"
                                                   v-if="record.status_new === 'Not Done'"
                                                   style="color: #e80101"
                                        >
                                            <close-circle-filled class="text-rose-600" style="font-size: large"/>
                                        </a-tooltip>
                                    </template>
                                    <template v-if="column.key === 'total'">
                                        <span class="text-amber-900">
                                            <strong style="font-size: large"> +{{record.total}}$ </strong>
                                        </span>
                                    </template>
                                    <template v-if="column.key === 'action'">
                                        <div class="flex gap-3 justify-center">
                                            <a-tooltip title="Detail">
                                                <eye-outlined
                                                    :style="{ fontSize: 19 }"
                                                    @click=" Inertia.get(route('invoices.show', {invoice: record.id,}))"
                                                />
                                            </a-tooltip>
                                        </div>
                                    </template>
                                </template>

                            </a-table>
                        </a-row>
                        <a-row class="ml-4">
                            <a-descriptions title="Tenants management"/>
                            <a-table :columns="columnsTenant" :data-source="tenants">
                                <template #headerCell="{ column }">
                                    <template v-if="column.key === 'name'">
                                        <span style="color: #0a43d3"> Name </span>
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
                                        <a-avatar size="small" src="./assets/img/user.png" />
                                    </template>
                                    <template v-if="column.key === 'name'">
                                        <span class="text-blue-600 ">
                                            {{record.name}}
                                        </span>
                                    </template>
                                    <template v-if="column.key === 'icon2'">
                                        <a-avatar size="small" src="./assets/img/home.png" />
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
                                        </div>
                                    </template>
                                </template>
                            </a-table>
                        </a-row>
                    </a-col>
                </a-row>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.custom-table td {
    border: 1px solid #ddd;
    padding: 8px;
    border-radius: 8px;
}
</style>

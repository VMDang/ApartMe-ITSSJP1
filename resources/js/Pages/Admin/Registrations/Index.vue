<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";
import {createVNode, reactive, ref, toRaw} from "vue";
import useState from "ant-design-vue/es/_util/hooks/useState.js";
import {
    EyeOutlined, HomeOutlined, SearchOutlined, SmileOutlined,
    DeleteOutlined, CheckCircleFilled, CloseCircleFilled, SyncOutlined, CheckOutlined, ExclamationCircleOutlined,
} from "@ant-design/icons-vue";
import {Modal} from "ant-design-vue";

const [registrations, setRegistrations] = useState(null);

const newRegistrations = usePage().props.registrations.map((registration) => {
    const textStatus = registration.approve_status === 1 ? "Accept" : registration.approve_status === 2 ? "Denied" : "Prepare";
    return {
        ...registration,
        status: textStatus,
    }
})

setRegistrations(newRegistrations);

const columns = [
    {
        title: "Address",
        dataIndex: "address",
        key: "address",
        customFilterDropdown: true,
        onFilter: (value, record) => record.address.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Apartment type",
        dataIndex: "apartment_type",
        key: "apartment_type",
        filters: [
            {
                text: "Apartment",
                value: "Apartment",
            },
            {
                text: "Mini Apartment",
                value: "Mini Apartment",
            },
            {
                text: "Motel",
                value: "Motel",
            },
            {
                text: "Whole House",
                value: "Whole House",
            },
        ],
        onFilter: (value, record) => record.apartment_type.name.indexOf(value) === 0,
    },
    {
        title: "Owner",
        key: "name",
        dataIndex: "name",
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Owner email",
        key: "owner_email",
        dataIndex: "owner_email",
        customFilterDropdown: true,
        onFilter: (value, record) => record.owner_email.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        align: "center",
        filters: [
            {
                text: "Prepare",
                value: "Prepare",
            },
            {
                text: "Accept",
                value: "Accept",
            },
            {
                text: "Denied",
                value: "Denied",
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

const value = ref("");

const onSearch = (searchValue) => {
    if (searchValue === "") {
        setRegistrations(newRegistrations);
    } else {
        searchValue = searchValue.toLowerCase();
        const registrationsSearch = newRegistrations.filter(
            (registration) =>
                registration.address.toString().toLowerCase().includes(searchValue) ||
                registration.apartment_type.name.toString().toLowerCase().includes(searchValue) ||
                registration.name.toString().toLowerCase().includes(searchValue) ||
                registration.owner_email.toString().toLowerCase().includes(searchValue) ||
                registration.status.toString().toLowerCase().includes(searchValue)
        );
        setRegistrations(registrationsSearch);
    }
};

const state = reactive({
    searchText: '',
    searchedColumn: '',
});

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
const showApproveConfirm = (record) => {
    open.value = true;
    formState.id = record.id;
};

const labelCol = {
    style: {
        width: '130px',
    },
};
const wrapperCol = {
    span: 20,
};

const onSubmit = () => {
    formRef.value
        .validate()
        .then(() => {
            Inertia.put(route('registrations.update', { registration: formState.id }), toRaw(formState))
        })
        .catch(error => {
            console.log('error', error);
        });
};
const resetForm = () => {
    formRef.value.resetFields();
};

const formRef = ref();
const errors = usePage().props.errors;

const formState = reactive({
    id: undefined,
    approve_status: undefined,
    approve_reason: '',
});

const options = ref([
    {
        value: 'Accept',
        label: 'Accept',
    },
    {
        value: 'Denied',
        label: 'Denied',
    },
])

const rules = {
    approve_status: [
        {
            required: true,
            message: 'Please select a status',
            trigger: 'change',
        },
    ],
    approve_reason: [
        {
            max: 255,
            message: 'Approve reason max length is 255',
            trigger: 'change',
        },
    ],
}
const showDeleteConfirm = (record) => {
    Modal.confirm({
        title: "Are you sure delete this room?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
            `Apartment address: ${record.address}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            Inertia.delete(route('registrations.destroy', {registration: record.id}));
        },
        onCancel() {},
    });
};
</script>

<template>
    <Head title="List Registration"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px ; background-color: #fff;"
                    title="Registrations Management"
                    sub-title="List rergistrations in system"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex justify-between items-end">
                    <div class="m-4"></div>
                    <div class="mt-4 pb-4 float-right">
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
                <a-table :columns="columns" :data-source="registrations">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'address'">
                            <span style="color: #1890ff">
                                <home-outlined/>
                                Apartment address
                            </span>
                        </template>
                        <template v-if="column.key === 'name'">
                            <span style="color: #1890ff">
                                <smile-outlined/>
                                Owner name
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
                        <template v-if="column.key === 'address'">
                            <span class="font-semibold"> {{ record.address }} </span>
                        </template>
                        <template v-if="column.key === 'name'">
                            <span class="font-semibold"> {{ record.name }} </span>
                        </template>
                        <template v-if="column.key === 'apartment_type'">
                            {{ record.apartment_type.name }}
                        </template>
                        <template v-if="column.key === 'status'">
                            <a-tooltip title="Done"
                                       v-if="record.status === 'Accept'"
                                       style="color: #1890ff"
                            >
                                <check-circle-filled class="text-green-700" style="font-size: large"  />
                            </a-tooltip>
                            <a-tooltip title="Denied"
                                       v-if="record.status === 'Denied'"
                                       style="color: #e80101"
                            >
                                <close-circle-filled class="text-rose-600" style="font-size: large"/>
                            </a-tooltip>
                            <a-tooltip title="Preapre Approve"
                                       v-if="record.status === 'Prepare'"
                                       style="color: #e80101"
                            >
                                <sync-outlined style="font-size: large"/>
                            </a-tooltip>
                        </template>
                        <template v-if="column.key === 'action'">
                            <div class="flex gap-3 justify-center">
                                <a-tooltip title="Detail">
                                    <eye-outlined :style="{ fontSize: 19 }"
                                        @click=" Inertia.get(route('registrations.show', {registration: record.id}))"
                                    />
                                </a-tooltip>
                                <a-tooltip v-if="record.status === 'Prepare'" title="Approve">
                                    <check-outlined
                                        :style="{ fontSize: 19, color: '#1890ff', }"
                                        @click="showApproveConfirm(record)"
                                    />
                                </a-tooltip>
                                <a-tooltip v-if="record.status !== 'Accept'" title="Delete">
                                    <delete-outlined :style="{ fontSize: 19, color: '#e80101' }"
                                                  @click="showDeleteConfirm(record)"
                                    />
                                </a-tooltip>
                            </div>
                        </template>
                    </template>
                </a-table>
                <a-modal v-model:open="open" title="Modal approve registration" :footer="null">
                    <a-form
                        :rules = "rules"
                        ref="formRef"
                        :model="formState"
                        :label-col="labelCol"
                        :wrapper-col="wrapperCol"
                        layout="horizontal"
                        autocomplete="off"
                    >
                        <a-form-item label="Approve" name="approve_status" has-feedback>
                            <a-select
                                :options="options"
                                v-model:value="formState.approve_status"
                                placeholder="Please select a status approve"
                                show-search
                                allow-clear
                            />
                        </a-form-item>
                        <a-form-item ref="approve_reason" label="Approve reason" name="approve_reason" has-feedback  >
                            <a-textarea v-model:value="formState.approve_reason"/>
                        </a-form-item>
                        <a-form-item :wrapper-col="{ span: 20, offset: 16 }">
                            <a-space>
                                <a-button @click="resetForm">Reset</a-button>
                                <a-button type="primary" @click="onSubmit">Approve</a-button>
                            </a-space>
                        </a-form-item>
                    </a-form>
                </a-modal>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

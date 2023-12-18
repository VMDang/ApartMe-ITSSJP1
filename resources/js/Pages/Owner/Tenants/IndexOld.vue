<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref, defineProps} from "vue";
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
    FormOutlined,
} from "@ant-design/icons-vue";

const props = defineProps({
    tenants: Array
})

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

    },
    {
        title: "Full name",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Phone Number",
        dataIndex: "phone",
        key: "phone",
    },
    {
        title: "Email",
        dataIndex: "email",
        key: "email",
        sorter: (a, b) => a.email - b.email,
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
        title: "Are you sure you want to delete?",
        icon: createVNode(ExclamationCircleOutlined),
        content: createVNode(
            "div",
            { style: "color:red;" },
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",

        onCancel() {},
        onOk(){
        },
    });
};

const showDetailsModal = (record) => {

  Modal.info({
    title: "Details of the tenant's account",
    icon: createVNode(EyeOutlined),
    content: createVNode(
      "div",
      null,
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
        ])
      ]
    ),
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

const showRegisterModal = () => {
  const formData = reactive({
    fullName: "",
    email: "",
    phone: "",
    password: "",
    status: "1",
  });

  Modal.info({
    title: "Register an Account",
    icon: createVNode(FormOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table-reply" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Full Name"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                onChange: (e) => (formData.fullName = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Phone Number"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                onChange: (e) => (formData.phone = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Email"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                onChange: (e) => (formData.email = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Password"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input.Password, {
                onChange: (e) => (formData.password = e.target.value),
              }),
            ]),
          ]),

        ]),
      ]
    ),

    okText: "Register",
    onOk() {
    },
  });
};
</script>

<template>
    <Head title="List Tenants" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="List of Tenant"

                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="showRegisterModal()"
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
                            <a-avatar size="large" :style="{ backgroundColor: getColor(index), verticalAlign: 'middle' }">
                                {{ record.name }}
                            </a-avatar>
<!--                            <a-avatar src="./assets/img/profile.png" />-->
                        </template>
                        <template v-if="column.key === 'name'">
                            <span class="underline ">
                                <strong class="text-blue-700 font-bold"> {{record.name}} </strong>
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

</style>

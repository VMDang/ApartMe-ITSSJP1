
<template>
  <Head title="Rental History" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <a-page-header style="border: 1px solid rgb(221,222,225); border-radius: 10px; background-color: #ffffff;"
          title="List Rental History" />
      </div>

      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="pb-4 float-right" style="margin-top: 23px">
          <a-space direction="vertical" clearIcon>
            <a-input-search v-model:value="value" placeholder="Input search text" enter-button="Search"
              style="width: 300px" @search="handleSearch" allow-clear></a-input-search>
          </a-space>
        </div>
        <a-table :columns="columns" :data-source="history">
          <template #headerCell="{ column }">
            <template v-if="column.key === 'avatar'">
              <span>Avatar</span>
            </template>
            <template v-if="column.key === 'name'">
              <span>Room name</span>
            </template>
          </template>


          <template #customFilterIcon="{ filtered }">
            <search-outlined :style="{ color: filtered ? '#108ee9' : undefined }" />
          </template>

          <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'avatar'">
              <a-avatar size="large" src="./assets/img/home.png" />
            </template>
            <template v-if="column.key === 'date'">
              {{ record.date }}
            </template>

            <template v-else-if="column.key === 'action'">
              <div class="flex gap-3 justify-center">
                <a-tooltip title="Detail">
                  <eye-outlined :style="{ fontSize: 19 }" @click="showDetailsModal(record)" />
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

<script setup>
import { createVNode, reactive, ref, defineProps, defineComponent } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input, Modal } from "ant-design-vue";

import { h } from 'vue';
import { Tag } from 'ant-design-vue';


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
  history: Array
});

const getColorForTenant = (tenant) => {
  if (tenant === 'loser') {
    return 'volcano';
  } else if (tenant.length > 5) {
    return 'geekblue';
  } else {
    return 'green';
  }
};

const components = defineComponent({
  AuthenticatedLayout: AuthenticatedLayout
});

var history = [
  {
    id: 1,
    avatar: '',
    name: 'Room 1',
    tenant: ['VMDang', 'Hoan'],
    date: '2023 December 3rd - 2024 January 3rd',

  },
  {
    id: 2,
    avatar: '',
    name: 'Room 2',
    tenant: ['Khanh', 'Duong123'],
    date: '2023 December 3rd - 2023 September 3rd',

  },
  {
    id: 3,
    avatar: '',
    name: 'Room 3',
    tenant: ['Long789', 'Khanh'],
    date: '2023 December 3rd - 2023 November 10th',

  },
  {
    id: 4,
    avatar: '',
    name: 'Room 4',
    tenant: ['Hoan', 'Duong123'],
    date: '2023 December 3rd - 2024 January 3rd',
  },
  {
    id: 5,
    avatar: '',
    name: 'Room 4',
    tenant: ['Hoan', 'Duong123'],
    date: '2023 December 3rd - 2024 January 3rd',

  },
  {
    id: 6,
    avatar: '',
    name: 'Room 4',
    tenant: ['Hoan', 'Duong123'],
    date: '2023 December 3rd - 2024 January 3rd',

  },
  {
    id: 7,
    avatar: '',
    name: 'Room 4',
    tenant: ['Hoan', 'Duong123'],
    date: '2023 December 3rd - 2024 January 3rd',

  }

];

const state = reactive({
  searchText: '',
  searchedColumn: '',
});

const searchInput = ref();

const columns = [{
  title: "Avartar",
  dataIndex: "avatar",
  key: "avatar",
  align: "center",

}, {
  title: "Room Name",
  dataIndex: "name",
  key: "name",
  sorter: (a, b) => a.name.length - b.name.length,
  sortDirections: ["descend", "ascend"],
}, {
  title: "Rent date",
  dataIndex: "date",
  key: "date",
  sorter: (a, b) => a.date.length - b.date.length,
  sortDirections: ["descend", "ascend"],
}, {
  title: "Action",
  key: "action",
  align: "center",
}];

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
      `Room name: ${record.name}`
    ),
    okText: "Yes",
    okType: "danger",
    cancelText: "No",
    onCancel() { },
    onOk() {
    }
  });
};

const showDetailsModal = (record) => {
  console.log("showDetailsModal called with record:", record);

  const tenantTags = record.tenant.map(tenant =>
    createVNode(Tag, { color: getColorForTenant(tenant) }, () => tenant.toUpperCase())
  );

  Modal.info({
    title: "Detail History",
    icon: createVNode(EyeOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table" }, [
          // Room Name
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Room Name"),
            createVNode("td", { class: "data-column" }, record.name),
          ]),
          // Tenant
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Tenant"),
            createVNode("td", { class: "data-column" }, tenantTags),
          ]),
          // Rent Date
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Rent Date"),
            createVNode("td", { class: "data-column" }, record.date),
          ]),
          // Note
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Note"),
            createVNode("td", { class: "data-column" }, record.note),
          ]),
        ])
      ]
    ),
    okText: "Close",
    onOk() { },
  });
};


</script>

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

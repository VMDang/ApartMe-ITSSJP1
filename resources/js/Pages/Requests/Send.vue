<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Input } from "ant-design-vue";

import {
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    FormOutlined,
} from "@ant-design/icons-vue";
import { Modal} from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";


const messages = [
  {
    
    name: "101",
    title: "Gia han hop dong",
    content: "Toi muon gia han hop dong",
    fullname: "John Doe",
    phoneNumber: "123-456-7890",
    email: "john.doe@example.com",
  },
  {
    
    name: "102",
    title: "Gia han hop dong 2",
    content: "Toi muon gia han hop dong 2",
    fullname: "Jane Doe",
    phoneNumber: "987-654-3210",
    email: "jane.doe@example.com",
  },
];


const state = reactive({
    searchText: '',
    searchedColumn: '',
});
const searchInput = ref();

const columns = [
    {
        title: "Number",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        sortDirections: ["descend", "ascend"],
        customFilterDropdown: true,
        onFilter: (value, record) => record.name.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Title",
        dataIndex: "title",
        key: "title",
        sorter: (a, b) => a.title.length - b.title.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Content",
        dataIndex: "content",
        key: "content",
        sorter: (a, b) => a.content - b.content,
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
            `Number: ${record.name}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",

        onCancel() {},
    });
};

const showDetailsModal = (record) => {
  console.log("showDetailsModal called with record:", record);

  Modal.info({
    title: "Detail messages",
    icon: createVNode(EyeOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Full Name"),
            createVNode("td", { class: "data-column" }, record.fullname),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Phone Number"),
            createVNode("td", { class: "data-column" }, record.phoneNumber),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Email"),
            createVNode("td", { class: "data-column" }, record.email),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Title"),
            createVNode("td", { class: "data-column" }, record.title),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Content"),
            createVNode("td", { class: "data-column" }, record.content),
          ]),
        ])
      ]
    ),
    okText: "Close",
    onOk() {},
  });
};



const showReplyModal = (record) => {
  console.log("showReplyModal called with record:", record);

  // State để lưu trữ giá trị nhập từ người dùng
  const formData = reactive({
    fullName: "",
    phoneNumber: "",
    email: "",
    title: "",
    content: "",
  });

  Modal.info({
    title: "Create messages",
    icon: createVNode(FormOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table-reply" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Full Name"),
            createVNode("td", { class: "data-column-reply" }, [
              // Sử dụng Input để người dùng nhập vào
              createVNode(Input, {
                value: formData.fullName,
                onChange: (e) => (formData.fullName = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Phone Number"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                value: formData.phoneNumber,
                onChange: (e) => (formData.phoneNumber = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Email"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                value: formData.email,
                onChange: (e) => (formData.email = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Title"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                value: formData.title,
                onChange: (e) => (formData.title = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Content"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input.TextArea, {
                value: formData.content,
                onChange: (e) => (formData.content = e.target.value),
              }),
            ]),
          ]),
        ]),
      ]
    ),
    okText: "Close",
    onOk() {
      // Truy cập dữ liệu đã nhập từ người dùng
      console.log("Form Data:", formData);
    },
  });
};




</script>

<template>
    <Head title="Message list" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="List of sent messages"
  
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="showReplyModal(record)"
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
                            @search="handleSearch"
                            allow-clear
                        >
                        </a-input-search>
                    </a-space>
                </div>
                <a-table :columns="columns" :data-source="messages">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span >
                                
                                Number
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
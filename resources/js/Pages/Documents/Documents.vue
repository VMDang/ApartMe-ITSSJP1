<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import {createVNode, reactive, ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { message } from 'ant-design-vue';
import { Upload, Button } from 'ant-design-vue';
import { UploadOutlined } from '@ant-design/icons-vue';


import {
    PlusCircleOutlined,
    DeleteOutlined,
    EyeOutlined,
    ExclamationCircleOutlined,
    SearchOutlined,
    EditOutlined,
    FormOutlined,
} from "@ant-design/icons-vue";
import { Modal,Input} from "ant-design-vue";
import { Inertia } from "@inertiajs/inertia";


const Documents = [
  {
    
    number: "101",
    sender: "nguyen van a",
    name: "giay phep lai xe",
    desciption: "toi da nop giay phep",
    
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
        dataIndex: "number",
        key: "number",
        sorter: (a, b) => a.number.length - b.number.length,
        sortDirections: ["descend", "ascend"],
        customFilterDropdown: true,
        onFilter: (value, record) => record.number.toString().toLowerCase().includes(value.toLowerCase()),
    },
    {
        title: "Sender",
        dataIndex: "sender",
        key: "sender",
        sorter: (a, b) => a.sender.length - b.sender.length,
        sortDirections: ["descend", "ascend"],
    },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        sorter: (a, b) => a.name.length - b.name.length,
        
    },
    {
        title: "Desciption",
        dataIndex: "desciption",
        key: "desciption",
        sorter: (a, b) => a.desciption - b.desciption,
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
            `Number: ${record.number}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",

        onCancel() {},
    });
};

// Hàm tạo nút "Click to Upload"
const createUploadButton = (fileList, handleChange) => {
  const uploadButton = createVNode(Upload, {
    'v-model:file-list': fileList,
    name: "file",
    action: "https://www.mocky.io/v2/5cc8019d300000980a055e76",
    headers: { authorization: 'authorization-text' },
    onChange: handleChange,
  }, [
    createVNode(Button, {
      style: {
        backgroundColor: '#ffffff',
        color: '#000000',
        border: '1px solid #cccccc',
        borderRadius: '4px',
        cursor: 'pointer',
        transition: 'background-color 0.3s ease',
      },
    }, [
      createVNode(UploadOutlined),
      "Click to Upload",
    ]),
  ]);

  return uploadButton;
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
            createVNode("td", { class: "label-column" }, "Sender"),
            createVNode("td", { class: "data-column" }, record.sender),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Name"),
            createVNode("td", { class: "data-column" }, record.name),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Desciption"),
            createVNode("td", { class: "data-column" }, record.desciption),
          ]),
        // Thêm nút "Click to Upload"
        createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Upload File"),
            createVNode("td", { class: "data-column" }, [
              createUploadButton([], handleChange),
            ]),
          ]),
        ]),
      ]
    ),
    okText: "Close",
    onOk() {},
  });
};


const showEditModal = (record) => {
  console.log("showEditModal called with record:", record);

  // State để lưu trữ giá trị chỉnh sửa
  const formData = reactive({
    sender: record.sender,
    name: record.name,
    description: record.desciption,
  });

  Modal.confirm({
    title: "Edit messages",
    icon: createVNode(EditOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table-edit" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-edit" }, "Sender"),
            createVNode("td", { class: "data-column-edit" }, [
              // Sử dụng Input để người dùng chỉnh sửa
              createVNode(Input, {
                value: formData.sender,
                onChange: (e) => (formData.sender = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-edit" }, "Name"),
            createVNode("td", { class: "data-column-edit" }, [
              createVNode(Input, {
                value: formData.name,
                onChange: (e) => (formData.name = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-edit" }, "Desciption"),
            createVNode("td", { class: "data-column-edit" }, [
              createVNode(Input, {
                value: formData.description,
                onChange: (e) => (formData.description = e.target.value),
              }),
            ]),
          ]),
              // Thêm nút "Click to Upload"
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Upload File"),
            createVNode("td", { class: "data-column" }, [
              createUploadButton([], handleChange),
            ]),
          ]),
        ]),
      ]
    ),
    okText: "Save",
    onOk() {
      // Truy cập dữ liệu đã chỉnh sửa từ người dùng
      console.log("Form Data:", formData);
      // Thực hiện lưu trữ dữ liệu chỉnh sửa, ví dụ gửi dữ liệu về server
      // Gọi hàm để xử lý lưu dữ liệu
      saveEditedData(formData, record);
    },
  });
};


const showCreateDocumentModal = (record) => {
  // State để lưu trữ giá trị nhập từ người dùng và file đã upload
  const formData = reactive({
    sender: "",
    name: "",
    description: "",
  });

  const fileList = ref([]);
  const headers = {
    authorization: 'authorization-text',
  };

  Modal.info({
    title: "Create documents",
    icon: createVNode(FormOutlined),
    content: createVNode("div", null, [
      // Thêm các trường thông tin vào modal
      createVNode("table", { class: "custom-table-reply" }, [
        createVNode("tr", null, [
          createVNode("td", { class: "label-column-reply" }, "Sender"),
          createVNode("td", { class: "data-column-reply" }, [
            createVNode(Input, {
            //   value: formData.sender,
              onChange: (e) => (formData.sender = e.target.value),
            }),
          ]),
        ]),
        createVNode("tr", null, [
          createVNode("td", { class: "label-column-reply" }, "Name"),
          createVNode("td", { class: "data-column-reply" }, [
            createVNode(Input, {
            //   value: formData.name,
              onChange: (e) => (formData.name = e.target.value),
            }),
          ]),
        ]),
        createVNode("tr", null, [
          createVNode("td", { class: "label-column-reply" }, "Description"),
          createVNode("td", { class: "data-column-reply" }, [
            createVNode(Input, {
            //   value: formData.description,
              onChange: (e) => (formData.description = e.target.value),
            }),
          ]),
        ]),
        // Thêm phần upload file vào modal
        createVNode("tr", null, [
          createVNode("td", { class: "label-column-reply" }, "Upload File"),
          createVNode("td", { class: "data-column-reply" }, [
            createVNode(Upload, {
              'v-model:file-list': fileList,
              name: "file",
              action: "https://www.mocky.io/v2/5cc8019d300000980a055e76",
              headers: headers,
              onChange: handleChange,
            }, [
              createVNode(Button, {
                style: {
                  backgroundColor: '#ffffff',
                  color: '#000000',
                  border: '1px solid #cccccc',
                  borderRadius: '4px',
                  cursor: 'pointer',
                  transition: 'background-color 0.3s ease',
                },
                on: {
                  mouseenter: () => {
                    this.$refs.uploadButton.style.backgroundColor = '#096dd9';
                  },
                  mouseleave: () => {
                    this.$refs.uploadButton.style.backgroundColor = '#ffffff';
                  },
                },
                ref: 'uploadButton',
              }, [
                createVNode(UploadOutlined),
                "Click to Upload",
              ]),
            ]),
          ]),
        ]),
      ]),
    ]),
    okText: "Close",
    cancelText: "Create",
    onOk() {
      // Logic for Close button
      console.log("Form Data:", formData);
      console.log("Uploaded Files:", fileList.value);
    },
    onCancel() {
      // Logic for Cancel button
      console.log("Cancel button clicked");
    },
  });
};

const handleChange = info => {
  if (info.file.status !== 'uploading') {
    console.log(info.file, info.fileList);
  }
  if (info.file.status === 'done') {
    message.success(`${info.file.name} file uploaded successfully`);
  } else if (info.file.status === 'error') {
    message.error(`${info.file.name} file upload failed.`);
  }
};




</script>

<template>
    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px"
                    title="List Documents"
  
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="showCreateDocumentModal"
                    >
                        <template #icon>
                            <plus-circle-outlined />
                        </template>
                        Create Document
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
                <a-table :columns="columns" :data-source="Documents">
                    <template #headerCell="{ column }">
                        <template v-if="column.key === 'name'">
                            <span >
                                
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

                            </template>
                        
                        <template v-else-if="column.key === 'action'">
                                <div class="flex gap-3 justify-center">
                                  <a-tooltip title="Detail">
                                    <eye-outlined
                                      :style="{ fontSize: 19 }"
                                      @click="showDetailsModal(record)"
                                    />
                                  </a-tooltip>
                                  <a-tooltip title="Update">
                                    <edit-outlined
                                      :style="{ fontSize: 19 }"
                                      @click="showEditModal(record)"
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

.label-column-reply, .label-column-edit {
  font-weight: bold;
  color: #333;
  padding: 10px;
}

.upload-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px 16px;
  border: 1px solid #1890ff; /* Ant Design primary color */
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.upload-button:hover {
  background-color: #f0f5ff; /* Ant Design primary color lightened */
}
</style>
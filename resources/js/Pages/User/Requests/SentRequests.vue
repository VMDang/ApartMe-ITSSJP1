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
    requests: Array,
    user: Object,
})

const state = reactive({
    searchText: '',
    searchedColumn: '',
});
const searchInput = ref();

const columns = [
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
            `Number: ${record.id}`
        ),
        okText: "Yes",
        okType: "danger",
        cancelText: "No",

        onCancel() {
            console.log("Cancelled");
        },
        onOk() {
            const id = record.id;
            const url = route("requests.destroy", { id: id });
            Inertia.delete(url);
            window.location.reload();
            console.log("OK");
        },
    });
};
const showDetailsModal = (record, user) => {
  console.log("showDetailsModal called with record:", record, user);

  Modal.info({
    title: "Detail requests",
    icon: createVNode(EyeOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Full Name"),
            createVNode("td", { class: "data-column" }, user.name),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Phone Number"),
            createVNode("td", { class: "data-column" }, user.phone),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column" }, "Email"),
            createVNode("td", { class: "data-column" }, user.email),
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

const showCreateModal = (user) => {

  const formData = reactive({
    apartment_id: "", // cause role have not existed yet -> input
    user_id: user.id,
    is_owner: "1", // cause role have not existed yet -> auto 1
    title: "",
    content: "",
  });

  Modal.info({
    title: "Create requests",
    icon: createVNode(FormOutlined),
    content: createVNode(
      "div",
      null,
      [
        createVNode("table", { class: "custom-table-reply" }, [
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Request to Apartment"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input, {
                onChange: (e) => (formData.apartment_id = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Title"),
            createVNode("td", {rules : "[{ required: true, message: 'Please input Title!' }]", class: "data-column-reply" }, [
              createVNode(Input, {
                onChange: (e) => (formData.title = e.target.value),
              }),
            ]),
          ]),
          createVNode("tr", null, [
            createVNode("td", { class: "label-column-reply" }, "Content"),
            createVNode("td", { class: "data-column-reply" }, [
              createVNode(Input.TextArea, {
                onChange: (e) => (formData.content = e.target.value),
              }),
            ]),
          ]),
        ]),
      ]
    ),
    okText: "Send",
    onOk() {
        axios.post(route("requests.store"), {
            apartment_id: formData.apartment_id,
            title: formData.title,
            content: formData.content,
            user_id: formData.user_id,
            is_owner: formData.is_owner,
                onFinish: () => {
                    formData.reset('apaartment_id', 'title', 'content');
                }
            });
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
                    style="border: 1px solid rgb(221,222,225); border-radius: 10px; background-color: white;"
                    title="List of sent requests"

                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="m-4 ml-0 float-left">
                    <a-button
                        type="primary"
                        @click="showCreateModal(user)"
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
                <a-table :columns="columns" :data-source="requests">
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
                                      @click="showDetailsModal(record, user)"
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

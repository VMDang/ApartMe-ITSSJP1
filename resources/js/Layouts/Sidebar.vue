<template>
  <Authenticated-layout>
    <a-layout style="min-height: 100vh">
      <a-layout-sider
        class="sider-custom"
        style="overflow: auto; height: 100vh; position: fixed; zindex: 1000"
      >
        <!-- <div class="logo" /> -->
        <a-menu v-model:selectedKeys="selectedKeys" mode="inline">
          <a-menu-item key="1">
            <home-outlined />
            <a :href="route('rooms.index')" class="menu-link">Rooms</a>
          </a-menu-item>
          <a-menu-item key="2">
            <user-outlined />
            <a :href="route('tenant-accounts.index')" class="menu-link"
              >Tenants</a
            >
          </a-menu-item>
          <a-menu-item key="3">
            <build-outlined />
            <a :href="route('facility.index')" class="menu-link">Facilities</a>
          </a-menu-item>
          <a-menu-item key="4">
            <dollar-circle-outlined />
            <a :href="route('invoices.index')" class="menu-link">Invoices</a>
          </a-menu-item>
          <a-sub-menu key="sub2">
            <template #title>
              <message-outlined />
              <span>Requests</span>
            </template>
            <a-menu-item key="5">
              <a :href="route('requests.sent')" class="menu-link">Sent</a>
            </a-menu-item>
            <a-menu-item key="6">
              <a :href="route('requests.recv')" class="menu-link">Received</a>
            </a-menu-item>
          </a-sub-menu>
          <a-menu-item key="7">
            <history-outlined />
            <a :href="route('history.index')" class="menu-link">History</a>
          </a-menu-item>
          <a-menu-item key="8">
            <info-circle-outlined />
            <a :href="route('infomation.index')" class="menu-link"
              >Infomation</a
            >
          </a-menu-item>
        </a-menu>
      </a-layout-sider>
    </a-layout>
  </Authenticated-layout>
</template>
  
<script>
import { ref } from "vue";
import {
  UserOutlined,
  MessageOutlined,
  DollarCircleOutlined,
  HomeOutlined,
  BuildOutlined,
  MenuFoldOutlined,
  MenuUnfoldOutlined,
  HistoryOutlined,
  InfoCircleOutlined,
} from "@ant-design/icons-vue";

export default {
  components: {
    UserOutlined,
    MessageOutlined,
    DollarCircleOutlined,
    HomeOutlined,
    BuildOutlined,
    MenuFoldOutlined,
    MenuUnfoldOutlined,
    HistoryOutlined,
    InfoCircleOutlined,
  },
  setup() {
    const collapsed = ref(false);
    const selectedKeys = ref(["1"]);
    return { collapsed, selectedKeys };
  },
};
</script>
  
<style scoped>
/* Set the background color for the sidebar and ensure it fills the full height */
.sider-custom {
  background-color: white; /* Red background color for the sidebar */
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow-y: auto;
  margin-top: 64px;
}

/* Styles for the menu within the sidebar */
.sider-custom .ant-menu {
  background-color: #fff; /* Set the background color to white */
  color: rgba(0, 0, 0, 0.65); /* Dark text color for light background */
}

/* Highlight styling for selected or active menu items */
.sider-custom .ant-menu-item-selected,
.sider-custom .ant-menu-item-active {
  background-color: #e6f7ff; /* Light blue background for selected item */
  color: #1890ff; /* Blue text color for selected item */
}

/* Hover styling for menu items */
.sider-custom .ant-menu-item:hover {
  background-color: #f5f5f5; /* Light grey background for hover */
}

/* Logo styling */
.logo {
  height: 32px;
  margin: 16px;
  background: rgba(255, 255, 255, 0.2); /* Semi-transparent white background */
}

/* Link styling within the menu */
.menu-link {
  text-decoration: none;
  color: inherit; /* Inherit the text color from parent */
}

/* Hover effect for links */
.menu-link:hover {
  color: #1890ff; /* Blue color on hover */
}

/* Flexbox alignment for menu items */
.sider-custom .ant-menu-item {
  display: flex;
  align-items: center;
  overflow: hidden; /* Prevents text from wrapping */
  white-space: nowrap; /* Keeps text in a single line */
  text-overflow: ellipsis; /* Adds an ellipsis if the text overflows */
}

/* Adjust the space between the icon and text in menu items */
.sider-custom .ant-menu-item .anticon {
  margin-right: 10px; /* Space between the icon and text */
}

/* Other styles */
.margin-left-auto {
  margin-left: 100px;
}

.site-layout-background {
  background: #fff; /* White background for the content layout */
}
</style>

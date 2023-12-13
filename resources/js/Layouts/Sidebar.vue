<template>
  <a-layout style="min-height: 100vh; background-color: #ffffff;">
    <a-layout-sider
      :style="{ background: '#ffffff', color: '#333', borderRight: '1px solid #e8e8e8' }"
      :collapsed="collapsed"
      @collapse="handleCollapse"
    >
      <div class="logo" />
      <a-menu v-model:selectedKeys="selectedKeys" theme="light" mode="inline">
        <a-menu-item key="1">
          <a :href="route('rooms.index')" class="menu-link">
            <span class="menu-icon"><home-outlined /></span>
            <span>Rooms</span>
          </a>
        </a-menu-item>
        <a-menu-item key="2">
          <a :href="route('tenant-accounts.view')" class="menu-link">
            <span class="menu-icon"><user-outlined /></span>
            <span>Tenants</span>
          </a>
        </a-menu-item>
        <a-menu-item key="3">
          <a :href="route('facility.index')" class="menu-link">
            <span class="menu-icon"><build-outlined /></span>
            <span>Facilities</span>
          </a>
        </a-menu-item>
        <a-menu-item key="4">
          <a :href="route('invoices.index')" class="menu-link">
            <span class="menu-icon"><dollar-circle-outlined /></span>
            <span>Invoices</span>
          </a>
        </a-menu-item>
        <a-sub-menu key="sub2">
          <template #title>
            <span class="menu-icon"><message-outlined /></span>
            <span>Requests</span>
          </template>
          <a-menu-item key="5">
            <a :href="route('requests.sent')" class="menu-link menu-request ">Sent</a>
          </a-menu-item>
          <a-menu-item key="6">
            <a :href="route('requests.recv')" class="menu-link menu-request">Received</a>
          </a-menu-item>
        </a-sub-menu>
      </a-menu>
      <!-- Collapse button -->
      <button class="collapse-button" @click="toggleCollapse">
        <span v-if="collapsed"><menu-fold-outlined /></span>
        <span v-else><menu-unfold-outlined /></span>
      </button>
    </a-layout-sider>
  </a-layout>
</template>

<script>
import { ref } from 'vue';
import {
  UserOutlined,
  MessageOutlined,
  DollarCircleOutlined,
  HomeOutlined,
  BuildOutlined,
  MenuFoldOutlined,
    MenuUnfoldOutlined,
} from '@ant-design/icons-vue';

export default {
  components: {
    UserOutlined,
    MessageOutlined,
    DollarCircleOutlined,
    HomeOutlined,
    BuildOutlined,
    MenuFoldOutlined,
    MenuUnfoldOutlined,
  },
  data() {
    return {
      collapsed: false,
      selectedKeys: ['1'], // Default selected key
    };
  },
  methods: {
    handleCollapse(collapsed) {
      this.collapsed = collapsed;
    },
    toggleCollapse() {
      this.collapsed = !this.collapsed;
    },
  },
};
</script>

<style scoped>
.menu-link {
  text-decoration: none;
  color: inherit;
}

.menu-link:hover {
  text-decoration: none;
}

.menu-link.ant-menu-item-selected {
  background-color: #1890ff;
}

.menu-item {
  display: flex;
  align-items: center;
}

/* Style the icons for proper alignment */
.menu-icon {
  margin-right: 28px; /* Adjust margin as needed */
}

/* Style the collapse button */
.collapse-button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px;
  color: #333;
  font-size: 14px;
  position: absolute;
  bottom: 65px;
  left: 0;
  width: 100%;
  text-align: center;
}

.collapse-button:hover {
  color: #1890ff;
}

.menu-request {
  text-align: left;
  margin-left: 17px;
}
</style>

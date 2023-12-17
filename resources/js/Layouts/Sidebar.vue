<template>
    <a-layout style="min-height: 100vh">
        <a-layout-sider v-model:collapsed="collapsed" collapsible>
            <div class="logo" />
            <a-menu
                v-model:selectedKeys="selectedKeys"
                theme="dark"
                mode="inline"
            >
                <a-menu-item key="1">
                    <pie-chart-outlined />
                    <a :href="route('rooms.index')" class="menu-link"
                        >Rooms</a
                    >
                </a-menu-item>
                <a-menu-item key="2">
                    <pie-chart-outlined />
                    <a :href="route('tenant-accounts.index')" class="menu-link"
                        >Tenants</a
                    >
                </a-menu-item>
                <a-menu-item key="3">
                    <pie-chart-outlined />
                    <a :href="route('facilities.index')" class="menu-link"
                    >Facilities</a>
                </a-menu-item>
                <a-menu-item key="4">
                    <pie-chart-outlined />
                    <a :href="route('invoices.index')" class="menu-link"
                    >Invoices</a>
                </a-menu-item>
                <a-menu-item key="5">
                    <pie-chart-outlined />
                    <a :href="route('payments.index')" class="menu-link"
                    >Payments</a>
                </a-menu-item>
                <a-sub-menu key="sub2">
                    <template #title>
                        <span>
                            <user-outlined />
                            <span>Requests</span>
                        </span>
                    </template>
                    <a-menu-item key="5">
                        <a :href="route('requests.sent')" class="menu-link"
                            >Sent</a
                        >
                    </a-menu-item>
                    <a-menu-item key="6">
                        <a :href="route('requests.recv')" class="menu-link"
                            >Received</a
                        >
                    </a-menu-item>
                </a-sub-menu>
            </a-menu>
        </a-layout-sider>
    </a-layout>
</template>

<script >
import { ref } from "vue";

export default {
    data() {
        return {
            collapsed: false,
            selectedKeys: ["1"], // Default selected key
        };
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
</style>

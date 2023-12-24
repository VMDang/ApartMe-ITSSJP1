<template>
    <Authenticated-layout>
        <a-layout style="min-height: 100vh">
            <a-layout-sider
                class="sider-custom"
                style="
                    overflow: auto;
                    height: 100vh;
                    position: fixed;
                    z-index: 1000;
                "
            >
                <!-- <div class="logo" /> -->
                <a-menu v-model:selectedKeys="selectedKeys" mode="inline">
                    <a-menu-item v-if="currentRole === 'OWNER'" key="dashboard">
                        <pie-chart-outlined />
                        <a :href="route('dashboard')" class="menu-link"
                            >Dashboard</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole !== 'ADMIN'" key="apartments">
                        <info-circle-outlined />
                        <a :href="route('apartments.show')" class="menu-link"
                            >Apartment Detail</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole !== 'ADMIN'" key="rooms">
                        <home-outlined />
                        <a :href="route('rooms.index')" class="menu-link"
                            >Rooms</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole === 'ADMIN'" key="registrations">
                        <medicine-box-outlined />
                        <a :href="route('registrations.index')" class="menu-link"
                        >Registrations </a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole === 'ADMIN'" key="apartments">
                        <home-outlined />
                        <a :href="route('apartments.index')" class="menu-link"
                        >Apartments </a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole === 'OWNER'" key="tenant-accounts">
                        <user-outlined />
                        <a
                            :href="route('tenant-accounts.index')"
                            class="menu-link"
                            >Tenants</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole === 'OWNER'" key="facilities">
                        <build-outlined />
                        <a :href="route('facilities.index')" class="menu-link"
                            >Facilities</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole !== 'ADMIN'" key="invoices">
                        <dollar-circle-outlined />
                        <a :href="route('invoices.index')" class="menu-link"
                            >Invoices</a
                        >
                    </a-menu-item>
                    <a-menu-item v-if="currentRole !== 'ADMIN'" key="payments">
                        <file-done-outlined/>
                        <a :href="route('payments.index')" class="menu-link"
                        >Payments</a
                        >
                    </a-menu-item>
                    <a-sub-menu v-if="currentRole !== 'ADMIN'" key="sub2">
                        <template #title>
                            <message-outlined />
                            <span>Requests</span>
                        </template>
                        <a-menu-item key="requests.sent">
                            <a :href="route('requests.sent')" class="menu-link"
                                >Sent</a
                            >
                        </a-menu-item>
                        <a-menu-item key="requests.recv">
                            <a :href="route('requests.recv')" class="menu-link"
                                >Received</a
                            >
                        </a-menu-item>
                    </a-sub-menu>
                    <a-menu-item v-if="currentRole === 'OWNER'" key="history">
                        <history-outlined />
                        <a :href="route('history.index')" class="menu-link"
                            >History</a
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
    PieChartOutlined,
    FileDoneOutlined,
    MedicineBoxOutlined,
} from "@ant-design/icons-vue";
import {usePage} from "@inertiajs/vue3";

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
        PieChartOutlined,
        FileDoneOutlined,
        MedicineBoxOutlined,
    },
    setup() {
        const collapsed = ref(false);
        const getKeys = () => {
            const currentRoute = route().current()

            if (currentRoute.includes('rooms')) {
                return ['rooms'];
            } else if (currentRoute.includes('apartments')) {
                return ['apartments']
            } else if (currentRoute.includes('tenant-accounts')) {
                return ['tenant-accounts']
            } else if (currentRoute.includes('facilities')) {
                return ['facilities']
            } else if (currentRoute.includes('invoices')) {
                return ['invoices']
            } else if (currentRoute.includes('payments')) {
                return ['payments']
            } else if (currentRoute.includes('requests.sent')) {
                return ['requests.sent']
            } else if (currentRoute.includes('requests.recv')) {
                return ['requests.recv']
            }else if (currentRoute.includes('history')) {
                return ['history']
            } else if (currentRoute.includes('registrations')) {
                return ['registrations']
            } else if (currentRoute.includes('dashboard')) {
                return ['dashboard']
            }

        }

        const selectedKeys = getKeys()

        const currentRole = usePage().props.role;

        return { collapsed, selectedKeys, currentRole };
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
    background: rgba(
        255,
        255,
        255,
        0.2
    ); /* Semi-transparent white background */
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

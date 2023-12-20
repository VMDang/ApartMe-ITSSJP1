<template>
    <Head title="Apartment Detail"/>

    <Authenticated-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                        background-color: #ffffff;
                    "
                    title="Apartment Information"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-row :gutter="16">
                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="18"
                        :lg="18"
                        class="left-column"
                    >
                        <div class="container">
                            <div class="banner"></div>

                            <div class="thumbnails">
                                <div class="thumbnail image1"></div>
                                <div class="thumbnail image2"></div>
                                <div class="thumbnail image3"></div>
                                <div class="thumbnail image4"></div>
                            </div>
                        </div>

                        <!-- Phần nội dung văn bản -->
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <a-descriptions
                                title="Apartment Details"
                                bordered
                                :column="{
                                    xxl: 2,
                                    xl: 2,
                                    lg: 2,
                                    md: 2,
                                    sm: 1,
                                    xs: 1,
                                }"
                                class="header"
                            >
                                <a-descriptions-item label="Owner"> {{ owner.name }} </a-descriptions-item>
                                <a-descriptions-item label="Address"> {{ apartment.address }}</a-descriptions-item>
                                <a-descriptions-item label="Phone Number"> {{ owner.phone }} </a-descriptions-item>
                                <a-descriptions-item label="Email"> {{ owner.email }} </a-descriptions-item>
                                <a-descriptions-item label="Floor Number">{{ apartment.number_floors }}</a-descriptions-item>
                                <a-descriptions-item label="Room Number"> {{ apartment.number_rooms }} </a-descriptions-item>
                                <a-descriptions-item label="Area"> {{ apartment.area }}</a-descriptions-item>
                                <a-descriptions-item label="Apartment Type"> {{ apartment.apartment_type.name }} </a-descriptions-item>
                            </a-descriptions>
                        </div>

                        <!-- Button Container -->
                        <div class="buttons-container">
                            <a-button class="button">Cancel</a-button>
                            <a-button
                                type="primary"
                                class="button"
                                @click="isModalVisible = true"
                                >Update</a-button
                            >
                        </div>
                        <!-- Modal -->
                        <a-modal
                            title="Update Apartment Details"
                            v-model:open="isModalVisible"
                        >
                            <a-descriptions
                                bordered
                                :column="{
                                    xxl: 2,
                                    xl: 2,
                                    lg: 2,
                                    md: 2,
                                    sm: 1,
                                    xs: 1,
                                }"
                            >
                                <a-descriptions-item label="Owner"> {{ owner.name }} </a-descriptions-item>
                                <a-descriptions-item label="Address"> {{ apartment.address }}</a-descriptions-item>
                                <a-descriptions-item label="Phone Number"> {{ owner.phone }} </a-descriptions-item>
                                <a-descriptions-item label="Email"> {{ owner.email }} </a-descriptions-item>
                                <a-descriptions-item label="Floor Number">{{ apartment.number_floors }}</a-descriptions-item>
                                <a-descriptions-item label="Room Number"> {{ apartment.number_rooms }} </a-descriptions-item>
                                <a-descriptions-item label="Area"> {{ apartment.area }}</a-descriptions-item>
                                <a-descriptions-item label="Apartment Type"> {{ apartment.apartment_type.name }} </a-descriptions-item>
                            </a-descriptions>
                        </a-modal>
                    </a-col>

                    <a-col
                        :xs="24"
                        :sm="24"
                        :md="6"
                        :lg="6"
                        class="right-column"
                    >
                        <!-- Timeline component -->
                        <a-descriptions
                            title="Apartment Timeline"
                        ></a-descriptions>
                        <a-timeline>
                            <a-timeline-item
                                v-for="item in listData"
                                :key="item.title"
                                :color="item.color"
                            >
                                <span>{{ item.title }}</span>
                                <p>{{ item.description }}</p>
                                <span>{{ item.date }}</span>
                            </a-timeline-item>
                        </a-timeline>

                        <!-- Pagination -->
                        <a-pagination :default-current="1" :total="50" />
                    </a-col>
                </a-row>
            </div>
        </div>
    </Authenticated-layout>
</template>

<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";


const apartment = usePage().props.apartment
const owner = usePage().props.owner

const isModalVisible = ref(false);
const details = ref({
    owner: "John Doe",
    address: "123 Main St",
    phoneNumber: "123-456-7890",
    email: "john.doe@example.com",
    floorNumber: "5",
    roomNumber: "501",
    area: "120 sqm",
    apartmentType: "2BHK",
});

const listData = ref([
    {
        title: "Lease Signed",
        description: "Lease agreement signed by John Doe",
        date: "2022-01-15",
        color: "green",
    },
    {
        title: "Move-in",
        description: "Tenant moved into the apartment",
        date: "2022-01-20",
        color: "blue",
    },
    {
        title: "First Maintenance",
        description: "Routine maintenance and inspection",
        date: "2022-03-05",
        color: "orange",
    },
    {
        title: "Lease Renewed",
        description: "Lease agreement renewed for another year",
        date: "2023-01-15",
        color: "green",
    },
    // ... more events ...
]);

</script>

<style scoped>
.container {
    display: flex;
    /* Use flexbox to layout children */
    flex-direction: row;
    /* Align children horizontally */
    justify-content: center;
    /* Center children in the container */
    align-items: center;
    /* Align children vertically */
    gap: 16px;
    /* Space between the banner and the thumbnails */
}

.thumbnails {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    /* Two columns */
    gap: 8px;
    /* Space between thumbnails */
}

.thumbnail,
.banner:hover {
    border: 1px solid #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    /* Placeholder image */
}

.thumbnail {
    width: 200px;
    height: 100px;
    margin-top: 20px;
    position: relative;
    overflow: hidden;
}

.thumbnail::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    background-size: cover;
    pointer-events: none;
}

.thumbnail:hover::after {
    opacity: 1; /* When hovering, the overlay becomes visible */
    animation: sparkle 1s infinite alternate;
}

@keyframes sparkle {
    0% {
        transform: scale(1); /* Start with no scaling */
    }
    100% {
        transform: scale(1.1); /* Scale up the sparkles a bit */
    }
}
.image1 {
    background-image: url("../../../../../public/assets/img/ha1.jpg");
}

.image2 {
    background-image: url("../../../../../public/assets/img/ha2.jpg");
}

.image3 {
    background-image: url("../../../../../public/assets/img/ha3.jpg");
}

.image4 {
    background-image: url("../../../../../public/assets/img/ha4.jpg");
}

.banner {
    width: 400px;
    height: 225px;
    margin-top: 20px;
    background-image: url("../../../../../public/assets/img/banner.jpg");
}

.header {
    margin-top: 20px;
    margin-bottom: 20px;
}

.buttons-container {
    display: flex;
    justify-content: center;
    gap: 20px;
    /* Adjust the gap between the buttons */
    margin-top: 20px;
    /* Space above the buttons */
}

.button {
    width: 100px;
    /* Custom width */
    /* Removed margin-left as it's no longer needed */
}

.right-column {
    margin-top: 30px;
}
</style>

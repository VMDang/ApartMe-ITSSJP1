<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import ApartmentDetail from "./ApartmentDetail.vue";
import axios from "axios";

export default {
    props: {
        apartments: Array,
    },
    data() {
        return {
            searchTerm: "",
            localApartments: [], // Danh sách căn hộ
            selectedApartment: null, // Căn hộ được chọn để hiển thị chi tiết
            detailsModalVisible: false, // Trạng thái hiển thị modal
        };
    },
    computed: {
        // Lọc căn hộ theo điều kiện tìm kiếm
        filteredApartments() {
            return this.localApartments.filter(apartment =>
                apartment.address.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                apartment.owner_email.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },
    },
    mounted() {
        this.fetchApartments();
    },
    methods: {
        async fetchApartments() {
            try {
                this.localApartments = this.apartments;
            } catch (error) {
                console.error("Error fetching apartments:", error);
            }
        },
        searchApartments() {
            this.fetchApartments();
        },
        showDetails(apartment) {
            this.selectedApartment = apartment;
            this.detailsModalVisible = true;
        },

        closeDetailsModal() {
            this.selectedApartment = null;
            this.detailsModalVisible = false;
        },
    },
    components: {
        ApartmentDetail,
    },
    components: { AuthenticatedLayout },
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="apartment-list-container">
            <h1>Apartment List</h1>

            <!-- Tìm kiếm -->
            <div class="search-container">
                <input type="text" v-model="searchTerm" placeholder="Search by address or owner email"
                    class="search-input" />
                <button @click="searchApartments" class="search-button">Search</button>
            </div>

            <!-- Danh sách căn hộ -->
            <ul class="apartment-list">
                <li v-for="apartment in filteredApartments" :key="apartment.id" class="apartment-item">
                    <div>
                        <strong>Address:</strong> {{ apartment.address }}<br>
                        <strong>Email:</strong> {{ apartment.owner_email }}<br>
                        <strong>Area:</strong> {{ apartment.area }}<br>
                        <strong>Type:</strong> {{ apartment.apartment_type_id }}<br>
                        <!-- Thêm nút chi tiết cho từng căn hộ -->
                        <button @click="showDetails(apartment)" class="details-button">Details</button>
                    </div>
                    <hr>
                </li>
            </ul>
            <!-- Hiển thị modal chi tiết căn hộ -->
            <apartment-detail :apartment="selectedApartment" @close="closeDetailsModal" v-if="detailsModalVisible" />
        </div>
    </AuthenticatedLayout>
</template>

  <script>

  </script>

<style scoped>
.apartment-list-container {
    max-width: 80%;
    /* 80% màn hình */
    margin: auto;
}

.search-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-input {
    width: 75%;
    /* 75% của search-container */
}

.search-button {
    background-color: #ff6666;
    /* Màu đỏ nhạt */
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.apartment-list {
    list-style: none;
    padding: 0;
}

.apartment-item {
    margin-bottom: 20px;
    /* Tăng khoảng cách giữa các căn hộ */
}

.details-button {
    background-color: #ff6666;
    /* Màu đỏ nhạt */
    color: white;
    border: none;
    border-radius: 10%;
    /* Bo tròn nút */
    width: 60px;
    height: 30px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10px;
    /* Khoảng cách giữa nút và nội dung căn hộ */
}</style>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import useState from "ant-design-vue/es/_util/hooks/useState.js";
import {ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {EyeOutlined} from "@ant-design/icons-vue";

const [apartments, setApartments] = useState(null);

const apartmentsProps = usePage().props.apartments
setApartments(apartmentsProps)

const value = ref("");
const onSearch = (searchValue) => {
    if (searchValue === "") {
        setApartments(apartmentsProps);
    } else {
        searchValue = searchValue.toLowerCase();
        const apartmentsSearch = apartmentsProps.filter(
            (apartment) =>
                apartment.address.toString().toLowerCase().includes(searchValue) ||
                apartment.owner.name.toString().toLowerCase().includes(searchValue) ||
                apartment.apartment_type.name.toString().toLowerCase().includes(searchValue) ||
                apartment.area.toString().toLowerCase().includes(searchValue) ||
                apartment.number_floors.toString().toLowerCase().includes(searchValue) ||
                apartment.number_rooms.toString().toLowerCase().includes(searchValue)
        );
        setApartments(apartmentsSearch);
    }
};

</script>

<template>
    <Head title="List Apartments"/>

    <Authenticated-layout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <a-page-header
                    style="
                        border: 1px solid rgb(221, 222, 225);
                        border-radius: 10px;
                        background-color: #fff;
                    "
                    title="Apartments Management"
                    sub-title="List apartments management registration success"
                />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex justify-between items-end">
                    <div class="m-4"></div>
                    <div class="mt-4 pb-4 float-right">
                        <a-space direction="vertical" clearIcon>
                            <a-input-search
                                v-model:value="value"
                                placeholder="input search text"
                                enter-button="Search"
                                style="width: 300px"
                                @search="onSearch"
                                allow-clear
                            >
                            </a-input-search>
                        </a-space>
                    </div>
                </div>
                <div>
                    <a-list
                        :grid="{ gutter: 16, column: 3 }"
                        :data-source="apartments"
                    >
                        <template #renderItem="{ item }">
                            <a-list-item>
                                <a-card :title="item.address"
                                        class="shadow-md mb-4"
                                        head-style="text-align: center; font-size: x-large; font-weight:700;color: #0a43d3"
                                >
                                    <h5><b>Owner: </b> {{item.owner.name}} </h5>
                                    <p>Apartment type: {{item.apartment_type.name}}</p>
                                    <p>Area : {{ item.area }}</p>
                                    <p>Number floors : {{ item.number_floors }}</p>
                                    <p>Number rooms : {{ item.number_rooms }}</p>
                                    <template #actions>
                                        <a-tooltip title="Detail">
                                            <eye-outlined
                                                :style="{ fontSize: 19 }"
                                                @click="
                                                    Inertia.get(
                                                        route('apartments.show', {
                                                            apartment: item.id,
                                                        })
                                                    )
                                                "
                                            />
                                        </a-tooltip>
                                    </template>
                                </a-card>
                            </a-list-item>
                        </template>
                    </a-list>
                </div>
            </div>
        </div>
    </Authenticated-layout>
</template>

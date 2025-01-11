<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                داشبورد
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg transition-all duration-300 hover:shadow-2xl dark:hover:shadow-[#FF2D20]/20">
                    <MainDashboard
                        :words="words"
                        :users="users"
                        :chartData="chartData"
                        class="p-6"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MainDashboard from '@/Components/MainDashboard.vue';

defineProps({
    words: Array,
    users: Array,
    chartData: Object,
});
</script>

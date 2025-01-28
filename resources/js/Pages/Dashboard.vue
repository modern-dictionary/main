<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight rounded-lg">
                داشبورد
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg transition-all duration-300">
                    <MainDashboard
                        :words="words"
                        :users="users"
                        :chartData="chartData"
                        class="p-6 rounded-lg"
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

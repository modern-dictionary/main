<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

defineProps({
    teams: Array,
    currentUser: Object,
});

const loading = ref(false);
const message = ref('');
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight rounded-lg">داشبورد</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto text-white sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg transition-all duration-300" dir="rtl">
                    <div class="mb-5 p-5 lg:p-8 bg-gradient-to-br border-gray-700 rounded-lg slide-up">
                        <h1 class="text-2xl font-bold mb-4">لیست تیم‌ها</h1>
                        <div class="space-y-4">
                            <div v-for="team in teams" :key="team.id"
                                class="p-4 border border-gray-700 shadow rounded-md grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                                <!-- Team Name -->
                                <h2 class="text-lg sm:text-xl font-semibold col-span-1 sm:col-span-2 lg:col-span-1">
                                    {{ team.name }}
                                </h2>

                                <!-- Team Stats -->
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 col-span-1 sm:col-span-2 lg:col-span-3 gap-2 sm:gap-4">
                                    <div class="text-sm sm:text-base text-gray-400">
                                        تعداد اعضا: {{1+ team.users_count }}
                                    </div>
                                    <div class="text-sm sm:text-base text-gray-400">
                                        مالک تیم: {{ team.owner.name }}
                                    </div>
                                    <div class="text-sm sm:text-base text-gray-400">
                                        تعداد کلمات: {{ team.words_count }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-span-1 flex items-center justify-start lg:justify-end">
                                    <button
                                        v-if="isOwner(team)"
                                        class="bg-gray-400 w-full sm:w-40 text-center text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded text-sm sm:text-base cursor-not-allowed"
                                        disabled
                                    >
                                        مالک تیم
                                    </button>

                                    <button
                                        v-else-if="isMember(team)"
                                        @click="leaveTeam(team.id, currentUser.id)"
                                        class="bg-red-500 w-full sm:w-40 text-center text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded text-sm sm:text-base hover:bg-red-600"
                                    >
                                        خارج شدن
                                    </button>

                                    <button
                                        v-else
                                        @click="addMemberToTeam(team.id, currentUser.email)"
                                        class="bg-blue-500 w-full sm:w-40 text-center text-white px-3 py-1.5 sm:px-4 sm:py-2 rounded text-sm sm:text-base hover:bg-blue-600"
                                    >
                                        عضو شدن
                                    </button>
                                </div>

                                <p v-if="message" class="mt-2 text-green-400 col-span-1 sm:col-span-2 lg:col-span-5">
                                    {{ message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import axios from "axios";

export default {

    data() {
        return {
            loading: false,
            message: '',
        };
    },
    methods: {
        isOwner(team) {
            return team.owner.id === this.currentUser.id;
        },
        isMember(team) {
            return team.users.some(user => user.id === this.currentUser.id);
        },
        async addMemberToTeam(teamId) {
            try {
                const response = await axios.post(`/teams/${teamId}/join-request`);
                location.reload();
            } catch (error) {
                console.error('خطا در ارسال درخواست عضویت:', error);
            }
        },
        async leaveTeam(teamId, userId) {
            try {
                const response = await axios.delete(`/teams/${teamId}/leave-team`);
                location.reload();
            } catch (error) {
                console.error('خطا در خروج از تیم:', error);

            }
        },

    },
};
</script>

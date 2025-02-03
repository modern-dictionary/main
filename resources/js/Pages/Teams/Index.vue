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
                            <div v-for="team in teams" :key="team.id" class="p-4 border shadow rounded-md grid grid-cols-5 gap-4">
                                <h2 class="text-xl font-semibold">{{ team.name }}</h2>
                                <div class="text-lg text-gray-400">تعداد اعضا: {{1+ team.users_count }}</div>
                                <div class="text-lg text-gray-400">مالک تیم: {{ team.owner.name }}</div>
                                <div class="text-lg text-gray-400">تعداد کلمات: {{ team.words_count }}</div>

                                <div>
                                    <button
                                        v-if="isOwner(team)"
                                        class="bg-gray-400 w-40 text-center text-white px-4 py-2 rounded cursor-not-allowed"
                                        disabled
                                    >
                                        مالک تیم
                                    </button>

                                    <button
                                        v-else-if="isMember(team)"
                                        @click="leaveTeam(team.id, currentUser.id)"
                                        class="bg-red-500 w-40 text-center text-white px-4 py-2 rounded hover:bg-red-600"
                                    >
                                        خارج شدن
                                    </button>

                                    <button
                                        v-else
                                        @click="addMemberToTeam(team.id, currentUser.email)"
                                        class="bg-blue-500 w-40 text-center text-white px-4 py-2 rounded hover:bg-blue-600"
                                    >
                                         عضو شدن
                                    </button>

                                    <p v-if="message" class="mt-2 text-green-400">{{ message }}</p>
                                </div>
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

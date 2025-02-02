<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight rounded-lg">
                داشبورد
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto text-white sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg transition-all duration-300">
                  <div dir="rtl">
                      <div class="mb-5 p-5 lg:p-8 bg-gradient-to-br border-gray-700 transition duration-300 rounded-lg slide-up">
                        <div class="p-4">
                          <h1 class="text-2xl font-bold mb-4">لیست تیم‌ها</h1>
                          <div class="space-y-2">
                            <div v-for="team in teams" :key="team.id" class="p-4 border shadow rounded-md grid grid-cols-4">
                              <h2 class="text-xl font-semibold">{{ team.name }}</h2>
                              <div class="text-sm text-gray-600">تعداد اعضا: {{ team.users_count }}</div>
                              <div class="text-sm text-gray-600">مالک تیم: {{ team.owner.name }}</div>
                              <!-- دکمه درخواست عضویت -->
                              <button
                              @click="sendJoinRequest"
                              class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                              :disabled="loading"
                              >
                              {{ loading ? 'در حال ارسال...' : 'درخواست عضویت' }}
                            </button>
                            <p v-if="message" class="mt-2 text-green-600">{{ message }}</p>
                            </div>

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
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
  props: {
    teams: Array,
  },
  data() {
    return {
      loading: false,
      message: '',
    };
  },
  methods: {
    async sendJoinRequest() {
      this.loading = true;
      this.message = '';
      try {
        const response = await axios.post(`/teams/${this.team.id}/join-request`);
        this.message = response.data.message;
      } catch (error) {
        console.error('خطا در ارسال درخواست عضویت:', error);
        this.message = 'خطایی رخ داده است.';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<script setup>
import { onMounted } from "vue";
import { Chart, registerables } from "chart.js";

defineProps({
  chartData: {
    type: Object,
    required: true,
  },
  words: {
    type: Array,
    required: true,
    default: () => [],
  },
  users: {
    type: Array,
    required: true,
    default: () => [],
  },
});

const testData = {
  "1_week": { users: 10, words: 15, teams: 5 },
  "1_month": { users: 20, words: 30, teams: 10 },
  "6_months": { users: 50, words: 60, teams: 25 },
  "1_year": { users: 100, words: 120, teams: 50 },
};

Chart.register(...registerables);

// Chart instance variable
let chartInstance = null;

// Function to create the chart
const createChart = () => {
  const canvas = document.getElementById("dashboardChart");
  if (!canvas) {
    console.error("Canvas element not found");
    return;
  }
  const ctx = canvas.getContext("2d");

  // Destroy existing chart if it exists
  if (chartInstance) {
    chartInstance.destroy();
  }

  // Create a new chart
  chartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: Object.keys(testData), // Time intervals as labels
      datasets: [
        {
          label: "کاربران",
          data: Object.values(testData).map((item) => item.users),
          borderColor: "rgba(75, 192, 192, 1)",
          backgroundColor: "rgba(75, 192, 192, 0.2)",
          fill: true,
        },
        {
          label: "کلمات",
          data: Object.values(testData).map((item) => item.words),
          borderColor: "rgba(153, 102, 255, 1)",
          backgroundColor: "rgba(153, 102, 255, 0.2)",
          fill: true,
        },
        {
          label: "تیم‌ها",
          data: Object.values(testData).map((item) => item.teams),
          borderColor: "rgba(255, 159, 64, 1)",
          backgroundColor: "rgba(255, 159, 64, 0.2)",
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "top",
        },
        title: {
          display: true,
          text: "نمودار داده‌ها در بازه‌های زمانی",
        },
      },
    },
  });
};

// Initialize chart on component mount
onMounted(() => {
  createChart();
});
</script>

<template>
  <div dir="rtl">
    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
      <h1 class="mt-8 text-2xl font-medium text-gray-900 dark:text-white">
        به دیکشنری مدرن خوش آمدید!
      </h1>
      <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
        دیکشنری مدرن ما به عنوان یک ابزار جامع و کاربرپسند، به شما این امکان را می‌دهد که به راحتی و سرعت به معانی، مترادف‌ها و ترجمه‌های دقیق کلمات دسترسی پیدا کنید.
      </p>
    </div>

    <div>
      <h2 class="text-lg text-white p-6 font-bold mb-4">نمودار داده‌ها</h2>
      <div class="w-full max-w-4xl mx-auto">
        <canvas id="dashboardChart"></canvas>
      </div>
    </div>
    <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        <div class="text-white">
          <h1 class="text-xl font-bold mb-6">کلمات جدید</h1>
          <div class="pb-2 grid grid-cols-4">
          <div class="pr-12"><strong> کلمه </strong></div>
          <div><strong> معنی </strong></div>
          <div><strong> تلفظ </strong></div>
          <div><strong> توضیحات </strong></div>
          </div>
          <div v-if="words.length > 0" class="space-y-2 border rounded">
            <div
            v-for="(word, index) in words"
            :key="word.id"
            class="p-4 rounded shadow-sm grid grid-cols-4 text-white items-center"
            >
            <div class="flex items-center">
              <div class="ml-5">{{ index + 1 }}</div>
              <div>{{ word.word }}</div>
            </div>
            <div>{{ word.meaning }}</div>
            <div>{{ word.pronansiation }}</div>
            <div>{{ word.description }}</div>
            </div>
      </div>
    <p v-else class="text-white">هیچ کلمه ای یافت نشد</p>
        </div>

        <div class="text-white">
          <h1 class="text-xl font-bold mb-6">اعضای جدید</h1>
          <div class="pb-2 grid grid-cols-3 items-center">
          <div class="pr-12"><strong> اسم </strong></div>
          <div class="pr-12"><strong> ایمیل </strong></div>
          <div class="pr-10"><strong> تاریخ عضویت </strong></div>
          </div>
          <div v-if="users.length > 0" class="space-y-2 border rounded">
            <div
            v-for="(user, index) in users"
            :key="user.id"
            class="p-4 rounded shadow-sm grid grid-cols-3 text-white items-center"
            >
            <div class="flex items-center">
              <div class="ml-5">{{ index + 1 }}</div>
              <div>{{ user.name }}</div>
            </div>
            <div>{{ user.email }}</div>
            <div class="pr-10">{{ user.formatted_created_at }}</div>
            </div>
      </div>
        </div>
    </div>
</div>

</template>

<!-- <script>
export default {
  props: {
        words: {
          type: Array,
          required: true,
          default: () => [],
        },
        users: {
          type: Array,
          required: true,
          default: () => [],
        },
        chartData: {
          type: Object,
          required: true,
        },
      },
    };
</script> -->

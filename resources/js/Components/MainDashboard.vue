<script setup>
    import {
        onMounted
    } from "vue";
    import {
        Chart,
        registerables
    } from "chart.js";

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
        "1_week": {
            users: 10,
            words: 15,
            teams: 5
        },
        "1_month": {
            users: 20,
            words: 30,
            teams: 10
        },
        "6_months": {
            users: 50,
            words: 60,
            teams: 25
        },
        "1_year": {
            users: 100,
            words: 120,
            teams: 50
        },
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
                datasets: [{
                        label: " کاربران",
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
                        labels: {
                            color: 'white'
                        }
                    },
                    title: {
                        display: true,
                        text: "نمودار داده‌ها در بازه‌های زمانی",
                        color: 'white'
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            },
        });
    };

    // Initialize chart on component mount
    onMounted(() => {
        createChart();
    });
</script>

<style>
.slide-up {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<template>
    <div dir="rtl">
        <div
            class="mb-5 p-5 lg:p-8 bg-gradient-to-br from-gray-800/50 to-gray-700/50 border-b border-gray-700 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 rounded-lg slide-up">
            <h1 class="mt-8 text-2xl font-medium text-white">
                به دیکشنری مدرن خوش آمدید!
            </h1>
            <p class="mt-6 text-white leading-relaxed">
                دیکشنری مدرن ما به عنوان یک ابزار جامع و کاربرپسند، به شما این امکان را می‌دهد که به راحتی و سرعت به
                معانی، مترادف‌ها و ترجمه‌های دقیق کلمات دسترسی پیدا کنید.
            </p>
        </div>

        <div
            class="mb-5 p-5 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 rounded-lg slide-up" style="animation-delay: 0.2s">
            <h2 class="text-lg text-white p-6 font-bold mb-4">نمودار داده‌ها</h2>
            <div class="w-full max-w-4xl mx-auto">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        <div
            class="mb-5 p-5 bg-gradient-to-br from-gray-800/50 to-gray-700/50 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 lg:p-8 rounded-lg">
            <!-- Words Section -->
            <div
                class="mb-5 p-5 text-white hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 rounded-lg slide-up" style="animation-delay: 0.3s">
                <h1 class="text-xl font-bold mb-6">کلمات جدید</h1>

                <!-- Desktop Headers -->
                <div class="mb-2 p-2 hidden md:grid md:grid-cols-4 pb-2">
                    <div class="pr-4 lg:pr-8"><strong>کلمه</strong></div>
                    <div class="pr-4"><strong>معنی</strong></div>
                    <div class="pr-4"><strong>تلفظ</strong></div>
                    <div class="pr-4"><strong>توضیحات</strong></div>
                </div>

                <div v-if="words.length > 0" class="mb-2 p-2 space-y-2 border border-gray-700 rounded">
                    <!-- Mobile View -->
                    <div v-for="(word, index) in words" :key="word.id"
                        class="mb-5 p-5 rounded-lg shadow-sm md:hidden flex flex-col gap-2 transition duration-300 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 hover:bg-gray-700/50 slide-up"
                        :style="{ animationDelay: `${0.4 + (index * 0.1)}s` }">
                        <div class="flex items-center font-medium">
                            <span class="ml-2 text-white/70">{{ index + 1 }}.</span>
                            <span class="text-white">{{ word . word }}</span>
                        </div>
                        <div class="mb-2 p-2 grid gap-1 text-sm">
                            <div><span class="text-white/70">معنی:</span> <span
                                    class="text-white">{{ word . meaning }}</span></div>
                            <div><span class="text-white/70">تلفظ:</span> <span
                                    class="text-white">{{ word . pronansiation }}</span></div>
                            <div><span class="text-white/70">توضیحات:</span> <span
                                    class="text-white">{{ word . description }}</span></div>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div v-for="(word, index) in words" :key="word.id"
                        class="mb-5 p-5 hidden md:grid md:grid-cols-4 rounded-lg shadow-sm items-start gap-4 transition duration-300 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 hover:bg-gray-700/50 slide-up"
                        :style="{ animationDelay: `${0.4 + (index * 0.1)}s` }">
                        <div class="flex items-center">
                            <span class="ml-2 text-white/70">{{ index + 1 }}.</span>
                            <span class="truncate text-white">{{ word . word }}</span>
                        </div>
                        <div class="truncate pr-2 text-white">{{ word . meaning }}</div>
                        <div class="truncate pr-2 text-white">{{ word . pronansiation }}</div>
                        <div class="line-clamp-2 pr-2 text-white">{{ word . description }}</div>
                    </div>
                </div>
                <p v-else class="text-white">هیچ کلمه ای یافت نشد</p>
            </div>

            <!-- Users Section -->
            <div
                class="mb-5 p-5 text-white hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 rounded-lg slide-up" style="animation-delay: 0.3s">
                <h1 class="text-xl font-bold mb-6">اعضای جدید</h1>

                <!-- Desktop Headers -->
                <div class="mb-2 p-2 hidden md:grid md:grid-cols-3 pb-2">
                    <div class="pr-4 lg:pr-8"><strong>اسم</strong></div>
                    <div class="pr-4"><strong>ایمیل</strong></div>
                    <div class="pr-4"><strong>تاریخ عضویت</strong></div>
                </div>

                <div v-if="users.length > 0" class="mb-5 p-5 space-y-2 border border-gray-700 rounded">
                    <!-- Mobile View -->
                    <div v-for="(user, index) in users" :key="user.id"
                        class="mb-5 p-5 rounded-lg shadow-sm md:hidden flex flex-col gap-2 transition duration-300 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 hover:bg-gray-700/50 slide-up"
                        :style="{ animationDelay: `${0.4 + (index * 0.1)}s` }">
                        <div class="flex items-center font-medium">
                            <span class="ml-2 text-white/70">{{ index + 1 }}.</span>
                            <span class="text-white">{{ user . name }}</span>
                        </div>
                        <div class="grid gap-1 text-sm">
                            <div><span class="text-white/70">ایمیل:</span> <span
                                    class="text-white">{{ user . email }}</span></div>
                            <div><span class="text-white/70">تاریخ عضویت:</span> <span
                                    class="text-white">{{ user . formatted_created_at }}</span></div>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div v-for="(user, index) in users" :key="user.id"
                        class="mb-5 p-5 hidden md:grid md:grid-cols-3 rounded-lg shadow-sm items-start gap-4 transition duration-300 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 hover:bg-gray-700/50 slide-up"
                        :style="{ animationDelay: `${0.4 + (index * 0.1)}s` }">
                        <div class="flex items-center">
                            <span class="ml-2 text-white/70">{{ index + 1 }}.</span>
                            <span class="truncate text-white">{{ user . name }}</span>
                        </div>
                        <div class="truncate pr-2 text-white">{{ user . email }}</div>
                        <div class="truncate pr-2 text-white">{{ user . formatted_created_at }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

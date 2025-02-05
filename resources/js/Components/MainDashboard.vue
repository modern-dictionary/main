<script setup>
    import {
        onMounted,
        watch
    } from "vue";
    import {
        Chart,
        registerables
    } from "chart.js";

    const props = defineProps({
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

    Chart.register(...registerables);

    let chartInstance = null;

    const createChart = () => {
        if (!props.chartData || Object.keys(props.chartData).length === 0) {
            console.log('No chart data available');
            return;
        }

        const canvas = document.getElementById("dashboardChart");
        if (!canvas) {
            console.error("Canvas element not found");
            return;
        }
        const ctx = canvas.getContext("2d");

        if (chartInstance) {
            chartInstance.destroy();
        }

        // Sort dates and prepare data
        const sortedDates = Object.keys(props.chartData).sort();
        const datasets = {
            users: [],
            words: [],
            teams: []
        };

        sortedDates.forEach(date => {
            datasets.users.push(props.chartData[date].users);
            datasets.words.push(props.chartData[date].words);
            datasets.teams.push(props.chartData[date].teams);
        });

        // Format dates for display
        const formattedDates = sortedDates.map(date => {
            return new Date(date).toLocaleDateString('fa-IR', {
                month: 'short',
                day: 'numeric'
            });
        });

        chartInstance = new Chart(ctx, {
            type: "line",
            data: {
                labels: formattedDates,
                datasets: [
                    {
                        label: "کاربران",
                        data: datasets.users,
                        borderColor: "rgba(75, 192, 192, 1)",
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        fill: true,
                    },
                    {
                        label: "کلمات",
                        data: datasets.words,
                        borderColor: "rgba(153, 102, 255, 1)",
                        backgroundColor: "rgba(153, 102, 255, 0.2)",
                        fill: true,
                    },
                    {
                        label: "تیم‌ها",
                        data: datasets.teams,
                        borderColor: "rgba(255, 159, 64, 1)",
                        backgroundColor: "rgba(255, 159, 64, 0.2)",
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                        labels: {
                            color: 'white',
                            boxWidth: 15,
                            padding: 10
                        }
                    },
                    title: {
                        display: true,
                        text: "نمودار داده‌ها در ۳۰ روز گذشته",
                        color: 'white',
                        font: {
                            size: 14
                        }
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'white',
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'white',
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        beginAtZero: true
                    }
                }
            },
        });
    };

    // Watch for changes in chartData
    watch(() => props.chartData, (newValue) => {
        if (Object.keys(newValue).length > 0) {
            createChart();
        }
    }, { deep: true });

    onMounted(() => {
        if (Object.keys(props.chartData).length > 0) {
            createChart();
        }
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
            class="mb-5 p-5 lg:p-8 bg-gradient-to-br border-gray-700 transition duration-300 rounded-lg slide-up">
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
            <div class="w-full mx-auto" style="height: 300px">
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
                                    class="text-white">{{ word . pronunciation }}</span></div>
                            <div><span class="text-white/70">توضیحات:</span> <span
                                    class="text-white">{{ word.description.slice(0, 20) + '...' }}</span></div>
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
                        <div class="truncate pr-2 text-white">{{ word . pronunciation }}</div>
                        <div class="line-clamp-2 pr-2 text-white">{{ word.description.slice(0, 20) + '...' }}</div>
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
                        <div class="truncate text-white">{{ user . email }}</div>
                        <div class="truncate text-white">{{ user . formatted_created_at }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

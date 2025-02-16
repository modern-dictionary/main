<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";


const form = useForm({
    name: "",
    description: "",
});

const submit = () => {
    form.post(route("categories.store"));
};

const deleteCategory = (id) => {
    if (confirm("از حذف دسته بندی مورد نظر اطمینان دارید؟")) {
        axios.delete(route("categories.destroy", id))
        .then(() => {
                location.reload();
            });
    }
};

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    team: {
        type: Object,
        required: false
    }
});

const teamTitle = computed(() => {
    if (props.team) {
        return ` ${props.team.name}`;
    } else {
        return '';
    }
});
</script>

<template>
    <Head title="categories" />

    <AppLayout title="دسته بندی">

        <template #header dir="rtl">
            <div class="flex flex-col gap-4 sm:gap-6 md:grid md:grid-cols-2 lg:grid-cols-3 items-center">
                <!-- Title -->
                <h2 class="font-semibold text-xl dark:text-white text-black leading-tight rounded-lg">
                    {{ $t('categories') }} - {{ teamTitle }}
                </h2>

                <!-- Search Bar -->
                <div class="relative w-full lg:w-96 xl:w-[500px] mx-auto lg:mx-0 lg:justify-self-center">
                    <input
                        v-model="searchTerm"
                        @focus="openSearchModal"
                        type="text"
                        :placeholder="$t('search_category')"
                        class="w-full border rounded-xl p-2 sm:p-3 lg:p-4 dark:text-white text-black focus:outline-none dark:bg-gray-800/50 focus:ring-2 focus:ring-[#FF2D20] hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 placeholder-gray-400 text-sm sm:text-base lg:text-medium"
                    />
                </div>

                <!-- New category Button -->
                <div class="lg:justify-self-end w-full lg:w-auto">
                    <button
                        @click="showAddModal = true"
                        class="w-full lg:w-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3 lg:py-4 bg-green-700 from-[#FF2D20] to-red-600 dark:text-white text-black rounded-xl hover:ring-white/20 duration-300 hover:bg-gray-700/50 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] text-sm sm:text-base lg:text-medium font-medium transform translate-y-0 hover:-translate-y-1"
                    >
                        {{ $t('add_new_category') }}
                    </button>
                </div>
            </div>
        </template>

        <template #logo>
            <AuthenticationCardLogo />
        </template>
        <!-- Search Module -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-gray-200 dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                <!-- Search Module -->
                <div
                    v-if="showSearchModal"
                    class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
                    @click="closeModal"
                >
                    <div
                        class="bg-white dark:text-white text-black dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-6 rounded shadow-md w-3/4"
                        @click.stop
                    >
                        <!-- search bar in module -->
                        <div class="mb-4 flex">
                            <input
                                v-model="searchTerm"
                                type="text"
                                :placeholder="$t('search_category')"
                                class="lg:w-1/2 w-full border justify-center dark:bg-gray-800 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>

                        <!-- Search results -->
                        <div>
                            <h3 class="text-md font-bold mb-2">
                                {{ $t('search_results') }}
                            </h3>
                            <div
                                v-if="filteredCategories.length > 0"
                                class="grid grid-cols-1 sm:grid-cols-2 gap-4 border rounded-xl p-4"
                            >
                                <div
                                    v-for="(category, index) in filteredCategories"
                                    :key="category.id"
                                    class="p-4 rounded border shadow-sm flex flex-col sm:grid sm:grid-cols-2 gap-3 items-start sm:items-center"
                                >
                                    <div class="flex items-center w-full mb-2 sm:mb-0">
                                        <div class="ml-5 text-gray-400">
                                            {{ index + 1 }}
                                        </div>
                                        <div class="font-medium">{{ category.name }}</div>
                                    </div>
                                    <!-- دکمه‌های عملیات -->
                                    <div class="flex flex-wrap gap-2 w-full sm:justify-end">
                                        <button
                                            @click="viewCategory(category)"
                                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-blue-200 text-blue-700 hover:bg-blue-300 transition-all duration-200 hover:scale-105"
                                        >
                                            {{ $t('view') }}
                                        </button>

                                        <button
                                            @click.stop="editCategory(category)"
                                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-blue-500 dark:text-white text-black hover:bg-blue-600 transition-all duration-200 hover:scale-105"
                                        >
                                            {{ $t('edit') }}
                                        </button>

                                        <button
                                            @click="deleteCategory(category.id)"
                                            class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-red-500 dark:text-white text-black hover:bg-red-600 transition-all duration-200 hover:scale-105"
                                        >
                                            {{ $t('delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-gray-500">
                                {{ $t('no_category_found') }}
                            </p>
                        </div>
                    </div>
                </div>

                    <!-- Categories List -->
                    <div class="dark:text-white text-black p-4 md:px-10 xl:px-24 2xl:px-4 py-10">
                        <h1 class="text-xl lg:text-2xl font-bold mb-6">{{ $t('category_list') }}</h1>

                        <div v-if="categories.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-gray-700 rounded-xl p-4">
                            <div
                                v-for="(category, index) in categories"
                                :key="category.id"
                                class="p-4 xl:p-6 rounded-xl shadow-sm flex flex-col justify-between hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform translate-y-0 hover:-translate-y-1 dark:text-white text-black border border-gray-700"
                            >
                                <!-- نام دسته‌بندی -->
                                <div class="flex items-center mb-4">
                                    <div class="ml-4 text-gray-400">{{ index + 1 }}</div>
                                    <div class="font-medium truncate">{{ category.name }}</div>
                                </div>
                                <div class="mb-4">{{ $t('word_count') }}:<strong>  {{ category.words_count }}</strong></div>

                                <!-- دکمه‌های عملیات -->
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        @click="viewCategory(category)"
                                        class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-blue-200 text-blue-700 hover:bg-blue-300 transition-all duration-200 hover:scale-105"
                                    >
                                        {{ $t('view') }}
                                    </button>

                                    <button
                                        @click="editCategory(category)"
                                        class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-blue-500 dark:text-white text-black hover:bg-blue-600 transition-all duration-200 hover:scale-105"
                                    >
                                        {{ $t('edit') }}
                                    </button>

                                    <button
                                        @click="deleteCategory(category.id)"
                                        class="px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base rounded bg-red-500 dark:text-white text-black hover:bg-red-600 transition-all duration-200 hover:scale-105"
                                    >
                                        {{ $t('delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p v-else class="text-gray-600 text-center py-8 text-lg">
                            {{ $t('no_category_found') }}
                        </p>
                    </div>
                  </div>
        </div>
        <!-- Show Modal -->
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
            @click="closeModal"
        >
            <div
                class="bg-white dark:bg-gray-800 border border-gray-700 p-6 sm:p-8 rounded-lg shadow-xl w-full max-w-5xl mx-4 max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <h2 class="text-xl lg:text-2xl font-bold mb-6 dark:text-white text-black border-b border-gray-700 pb-4">
                    {{ $t('category_details') }}
                </h2>

                <div class="space-y-6 mb-6">
                    <!-- Category -->
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 items-start">
                        <strong class="dark:text-white text-black text-lg sm:col-span-1">{{ $t('category') }}:</strong>
                        <span class="text-gray-300 sm:col-span-5">{{ selectedCategory.name }}</span>
                    </div>

                    <!-- Description -->
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 items-start">
                        <strong class="dark:text-white text-black text-lg sm:col-span-1">{{ $t('description') }}:</strong>
                        <div class="text-gray-300 sm:col-span-5 break-categories whitespace-pre-wrap min-h-[100px] bg-gray-800/50 p-4 rounded-lg border border-gray-700">
                            {{ selectedCategory.description }}
                        </div>
                    </div>

                    <!-- Category Words -->
                    <div v-if="selectedCategory.words && selectedCategory.words.length > 0" class="mt-6">
                      <h3 class="text-lg font-bold dark:text-white text-black border-b border-gray-700 pb-2">{{ $t('category_list') }}</h3>
                      <ul v-if="selectedCategory.words && selectedCategory.words.length > 0" class="mt-4 space-y-2">
                        <li v-for="(word, index) in selectedCategory.words" :key="word.id" class="text-gray-300 p-2 border-b border-gray-600">
                          <div class="p-4 rounded shadow-sm grid grid-cols-3 items-center">
                            <div class="flex">
                              <div class="mx-5">{{ index + 1 }}</div>
                              <div class="mx-5">{{ word.word }}</div>
                            </div>

                            <div class="">{{ word.meaning }}</div>
                            <div class="">{{ word.pronunciation }}</div>
                          </div>
                        </li>
                      </ul>
                      <p v-else class="text-gray-400 mt-4">{{ $t('no_category_found') }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 mt-8 border-t border-gray-700 pt-4">
                    <button
                        @click="editCategory(selectedCategory)"
                        class="px-6 py-2.5 bg-blue-500 dark:text-white text-black rounded-lg hover:bg-blue-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-offset-2 focus:ring-offset-gray-800"
                    >
                        {{ $t('edit') }}
                    </button>
                    <button
                        @click="closeModal"
                        class="px-6 py-2.5 bg-gray-600 dark:text-white text-black rounded-lg hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                    >
                        {{ $t('leave') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Add New Category Modal -->
        <div
            v-if="showAddModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
            @click="closeModal"
        >
            <div
                class="bg-white dark:text-white text-black dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 p-6 rounded shadow-md w-1/2"
                @click.stop
            >
                <h2 class="text-lg font-bold mb-4">{{ $t('add_new_category') }}</h2>
                <form @submit.prevent="addCategory" class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="add-category">{{ $t('name_category') }}</label>
                        <input
                            id="add-category"
                            v-model="newCategory.name"
                            type="text"
                            class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                            required
                        />
                    </div>
                    <div>
                        <label for="add-description">{{ $t('description') }}</label>
                        <input
                            id="add-description"
                            v-model="newCategory.description"
                            type="text"
                            class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                        />
                    </div>
                    <div class="col-span-2 flex justify-start">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-500 dark:text-white text-black rounded hover:bg-green-600 ml-5"
                        >
                            {{ $t('save') }}
                        </button>
                        <button
                            type="button"
                            @click="closeAddModal"
                            class="ml-4 px-4 py-2 bg-gray-500 dark:text-white text-black rounded hover:bg-gray-600"
                        >
                            {{ $t('close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
            @click="closeModal"
        >
            <div
                class="bg-white dark:bg-gray-800 border border-gray-700 p-6 rounded-lg shadow-xl w-full max-w-2xl mx-4"
                @click.stop
            >
                <h2 class="text-xl lg:text-2xl font-bold mb-6 dark:text-white text-black">
                    {{ $t('edit_category') }}
                </h2>

                <form @submit.prevent="saveCategory" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <!-- Name Input -->
                        <div>
                            <label class="block font-medium dark:text-white text-black mb-2">{{ $t('name_category') }}:</label>
                            <input
                                v-model="editForm.name"
                                type="text"
                                class="w-full px-4 py-2 rounded-lg border border-gray-700 bg-gray-800/50 dark:text-white text-black focus:outline-none focus:ring-2 focus:ring-[#FF2D20] transition-all duration-200"
                            />
                        </div>

                        <!-- Description Input -->
                        <div>
                            <label class="block font-medium dark:text-white text-black mb-2">{{ $t('description') }}:</label>
                            <input
                                v-model="editForm.description"
                                type="text"
                                class="w-full px-4 py-2 rounded-lg border border-gray-700 bg-gray-800/50 dark:text-white text-black focus:outline-none focus:ring-2 focus:ring-[#FF2D20] transition-all duration-200"
                            />
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            type="submit"
                            class="px-6 py-2.5 bg-blue-700 dark:text-white text-black rounded-lg hover:bg-blue-900 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-offset-2 focus:ring-offset-gray-800"
                        >
                            {{ $t('save') }}
                        </button>
                        <button
                            type="button"
                            @click="closeEditModal"
                            class="px-6 py-2.5 bg-gray-600 dark:text-white text-black rounded-lg hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                        >
                            {{ $t('Close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </AppLayout>
</template>

<script>
import axios from "axios";

export default {
    props: {
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            showSearchModal: false,
            searchTerm: "",
            showAddModal: false,
            newCategory: {
                name: "",
                description: "",
            },
            showEditModal: false,
            editForm: {
                id: null,
                name: "",
                description: "",
            },
            showModal: false,
            selectedCategory: {},
        };
    },
    methods: {
        openSearchModal() {
            this.showSearchModal = true;
        },
        closeSearchModal() {
            this.showSearchModal = false;
            this.searchTerm = "";
        },

        linkCategoryToTeam(categoryId, teamId) {
          axios.post(`/team/${teamId}/categories/add-category`, { category_id: categoryId })
          .then((response) => {
              console.log("دسته به تیم مربوطه اضافه شد:", response.data);
            })
            .catch((error) => {
              console.error("خطا در ربط دادن دسته به تیم:", error);
            });
          },

          addCategory(event) {
            if (event) event.preventDefault();

            // در اینجا فرض شده که this.newCategory یک رشته است که نام دسته را نگه می‌دارد.
            // در صورت نیاز می‌توانید این مقدار را به صورت آبجکت (مثلاً { name: "..." }) نیز ارسال کنید.
            const formData = new FormData();
            formData.append('name', this.newCategory.name);
            formData.append('description', this.newCategory.description);

            axios.post(route("categories.store"), formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            })
            .then(response => {
              // دسته جدید ایجاد شده و در response.data.category قرار دارد.
              if (window.location.pathname.includes('/team/')) {
                const teamId = this.$page.props.auth.user.current_team_id;
                console.log("Current Team ID:", teamId);

                return axios.post(`/team/${teamId}/categories/add-category`, {
                  category_id: response.data.category.id,
                })
                .then(response => {
                  console.log("Category linked to team:", response.data);
                })
                .catch(error => {
                  console.error("Error linking category to team:", error);
                  if (error.response) {
                    console.error("Server response:", error.response.data);
                  }
                });
              }
            })
            .then(teamResponse => {
              if (teamResponse) {
                console.log("Category linked to team:", teamResponse.data);
              }
              const notification = document.createElement('div');
              notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg bg-green-500 dark:text-white text-black transform transition-all duration-500';
              notification.innerHTML = '<div class="flex items-center"><span class="mr-2">✓</span>دسته با موفقیت اضافه شد</div>';
              document.body.appendChild(notification);
              setTimeout(() => {
                location.reload();
              }, 2000);
            })
            .catch(error => {
              console.error('Error in addCategory:', error.response ? error.response.data : error);
              const notification = document.createElement('div');
              notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg bg-red-500 dark:text-white text-black transform transition-all duration-500';
              notification.innerHTML = '<div class="flex items-center"><span class="mr-2">✕</span>خطا در ذخیره‌سازی دسته</div>';
              document.body.appendChild(notification);
            });
          },


        closeAddModal() {
            this.showAddModal = false;
            this.newCategory = {
                name: "",
                description: "",
            };
        },
        viewCategory(category) {
          axios.get(`/categories/${category.id}/words`).then(response => {
            this.selectedCategory = response.data.category;
            this.showModal = true;
          })
          .catch(error => {
            console.error("Error fetching words:", error);
          });
        },
        closeModal() {
            this.showSearchModal = false;
            this.showModal = false;
            this.showEditModal = false;
            this.showSearchModal = false;
            this.searchTerm = "";
            this.showAddModal = false;
            this.newCategory = {
                name: "",
                description: "",
            };
        },
        editCategory(category) {
            this.closeModal();
            this.editForm = { id: category.id, name: category.name, description: category.description };
            this.showEditModal = true;
        },
        closeEditModal() {
            this.showEditModal = false;
        },
        async saveCategory() {
          axios.put(route("categories.update", this.editForm.id), this.editForm).then(() => {
            location.reload();
            const categoryIndex = this.categories.findIndex(
                (category) => category.id === this.editForm.id
            );
            if (categoryIndex !== -1) {
                this.$set(this.categories, categoryIndex, { ...this.editForm });
            }
            this.closeEditModal();
            location.reload();
          });
          setTimeout(() => {
            window.location.reload(true);
          }, 500);
        },

    },
    computed: {
        // فیلتر کلمات بر اساس کلمه یا معنی
        filteredCategories() {
            const term = this.searchTerm.trim().toLowerCase();
            if (!term) return this.categories;
            return this.categories.filter(
                (category) =>
                    category.name.toLowerCase().includes(term)
            );
        },
    },
    mounted() {
        // بستن ماژول در صورت کلیک بیرون
        window.addEventListener("click", this.handleClickOutside);
    },
    beforeDestroy() {
        window.removeEventListener("click", this.handleClickOutside);
    },
};
</script>

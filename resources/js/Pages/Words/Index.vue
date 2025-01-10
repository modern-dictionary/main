<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const form = useForm({
    word: '',
    meaning: '',
    pronansiation: '',
    description: '',
});

const submit = () => {
  form.post(route('words.store'));
};

const deleteWord = (id) => {
  if (confirm('از حذف کلمه مورد نظر اطمینان دارید؟')) {
    axios.delete(route('words.destroy', id))
    location.reload();
  }
};
</script>

<template>
    <Head title="words" />

    <AppLayout title="کلمات">
      <template #header dir="rtl">
        <div class="grid grid-cols-3 items-center">
          <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
              کلمات
          </h2>

          <!-- Search Bar  -->
            <div class="relative ">
              <input
              v-model="searchTerm"
              @focus="openSearchModal"
              type="text"
              placeholder="جستجوی کلمه یا معنی..."
              class="w-full border rounded p-2 text-white focus:outline-none dark:bg-gray-800 focus:ring-2 focus:ring-blue-500"
              />
            </div>

          <!-- New Word -->
          <div class="w-auto justify-self-center">
            <button
              @click="showAddModal = true"
              class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
              >
              افزودن کلمه جدید
            </button>
          </div>
        </div>
      </template>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

          <!-- Search Module -->
          <div
          v-if="showSearchModal"
          class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
          @click="closeModal"
          >
          <div class="bg-white text-white dark:bg-gray-800 border-b border-gray-100
          dark:border-gray-700 p-6 rounded shadow-md w-3/4" @click.stop>
            <!-- search bar in module -->
            <div class="mb-4 flex">
              <input
              v-model="searchTerm"
              type="text"
              placeholder="جستجوی کلمه یا معنی..."
              class="w-1/2 border justify-center dark:bg-gray-800 rounded p-2
              focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Search results -->
            <div>
              <h3 class="text-md font-bold mb-2">نتایج جستجو:</h3>
              <div v-if="filteredWords.length > 0" class="list-disc pl-5">
                <div
                v-for="(word, index) in filteredWords"
                :key="word.id"
                class="p-4 rounded shadow-sm grid grid-cols-5 items-center"
                >
                <div class="flex items-center">
                  <div class="ml-5">{{ index + 1 }}</div>
                  <div>{{ word.word }}</div>
                </div>
                <div>{{ word.meaning }}</div>
                <div>{{ word.pronansiation }}</div>
                <div>{{ word.description }}</div>
                <!-- دکمه‌های عملیات -->
                <div class="flex justify-end">
                  <button
                  @click="viewWord(word)"
                  class="px-4 py-2 rounded bg-blue-200 text-blue-700 ml-2 hover:bg-blue-300"
                  >
                  مشاهده
                </button>

                <button
                  @click="editWord(word)"
                  class="px-4 py-2 rounded bg-blue-500 text-white ml-2 hover:bg-blue-600"
                  >
                  ویرایش
                </button>

                <button
                  @click="deleteWord(word.id)"
                  class="px-4 py-2 rounded bg-red-500 text-white ml-2 hover:bg-red-600"
                  >
                  حذف
                </button>
              </div>
            </div>
              </div>
              <p v-else class="text-gray-500">نتیجه‌ای یافت نشد.</p>
            </div>
          </div>
        </div>
        <div class="text-white px-20 py-10">
          <h1 class="text-xl font-bold mb-4">لیست کلمات</h1>
          <div class="pb-2 grid grid-cols-5">
          <div class="pr-20"><strong> کلمه </strong></div>
          <div><strong> معنی </strong></div>
          <div><strong> تلفظ </strong></div>
          <div><strong> توضیحات </strong></div>
          <div>
          <strong>  دکمه‌های عملیات  </strong>
          </div>
          </div>

          <div v-if="words.length > 0" class="space-y-2 border rounded">
            <div
            v-for="(word, index) in words"
            :key="word.id"
            class="p-4 rounded shadow-sm grid grid-cols-5 items-center"
            >
            <div class="flex items-center">
              <div class="ml-12">{{ index + 1 }}</div>
              <div>{{ word.word }}</div>
            </div>
            <div>{{ word.meaning }}</div>
            <div>{{ word.pronansiation }}</div>
            <div>{{ word.description }}</div>
            <!-- دکمه‌های عملیات -->
            <div class="flex justify-end">
              <button
              @click="viewWord(word)"
              class="px-4 py-2 rounded bg-blue-200 text-blue-700 ml-2 hover:bg-blue-300"
              >
              مشاهده
            </button>

            <button
              @click="editWord(word)"
              class="px-4 py-2 rounded bg-blue-500 text-white ml-2 hover:bg-blue-600"
              >
              ویرایش
            </button>

            <button
              @click="deleteWord(word.id)"
              class="px-4 py-2 rounded bg-red-500 text-white ml-2 hover:bg-red-600"
              >
              حذف
            </button>
          </div>
        </div>
      </div>
    <p v-else class="text-gray-600">هیچ کلمه ای یافت نشد</p>

    <!-- Edit Modal  -->
    <div
      v-if="showEditModal"
      class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
      @click="closeModal"
    >
      <div class="bg-white text-white dark:bg-gray-800 border-b border-gray-100
      dark:border-gray-700 p-6 rounded shadow-md w-1/2" @click.stop>
        <h2 class="text-xl font-bold mb-4">ویرایش کلمه</h2>
        <form @submit.prevent="saveWord">
          <div class="mb-4">
            <label class="block font-bold mb-2">کلمه:</label>
            <input
              v-model="editForm.word"
              type="text"
              class="w-full dark:bg-gray-800 px-3 py-2 border rounded"
            />
          </div>
          <div class="mb-4">
            <label class="block font-bold mb-2">معنی:</label>
            <input
              v-model="editForm.meaning"
              type="text"
              class="w-full dark:bg-gray-800 px-3 py-2 border rounded"
            />
          </div>
          <div class="mb-4">
            <label class="block font-bold mb-2">تلفظ:</label>
            <input
              v-model="editForm.pronansiation"
              type="text"
              class="w-full dark:bg-gray-800 px-3 py-2 border rounded"
            />
          </div>
          <div class="mb-4">
            <label class="block font-bold mb-2">توضیحات:</label>
            <textarea
              v-model="editForm.description"
              class="w-full dark:bg-gray-800 px-3 py-2 border rounded"
            ></textarea>
          </div>
          <div class="flex justify-start">
            <button
              type="submit"
              class="px-4 py-2 ml-5 rounded bg-blue-500 text-white hover:bg-blue-600"
            >
              ذخیره
            </button>
            <button
              type="button"
              @click="closeEditModal"
              class="px-4 py-2 rounded bg-gray-500 text-white mr-2 hover:bg-gray-600"
            >
              لغو
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
      <!-- Show Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50"
        @click="closeModal"
        >
        <div class="bg-white text-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700
         p-6 rounded shadow-md w-1/2" @click.stop>
          <h2 class="text-xl font-bold mb-4">جزئیات کلمه</h2>
          <div><strong>کلمه:</strong> {{ selectedWord.word }}</div>
          <div><strong>معنی:</strong> {{ selectedWord.meaning }}</div>
          <div><strong>تلفظ:</strong> {{ selectedWord.pronansiation }}</div>
          <div><strong>توضیحات:</strong> {{ selectedWord.description }}</div>
          <button
            @click="closeModal"
            class="mt-4 px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600"
            >
            بستن
          </button>
        </div>
      </div>


      <!-- Add New Word Modal -->
      <div
      v-if="showAddModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      @click="closeModal"
      >
        <div class="bg-white text-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700
         p-6 rounded shadow-md w-1/2" @click.stop>
          <h2 class="text-lg font-bold mb-4">افزودن کلمه جدید</h2>
          <form @submit.prevent="addWord" class="grid grid-cols-2 gap-4">
            <div>
              <label for="add-word">کلمه</label>
              <input
                id="add-word"
                v-model="newWord.word"
                type="text"
                class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                required
                />
              </div>
              <div>
                <label for="add-meaning">معنی</label>
                <input
                id="add-meaning"
                v-model="newWord.meaning"
                type="text"
                class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                required
                />
              </div>
              <div>
                <label for="add-pronansiation">تلفظ</label>
                <input
                id="add-pronansiation"
                v-model="newWord.pronansiation"
                type="text"
                class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                />
              </div>
              <div>
                <label for="add-description">توضیحات</label>
                <input
                id="add-description"
                v-model="newWord.description"
                type="text"
                class="mt-1 block dark:bg-gray-800 w-full border rounded p-2"
                />
              </div>
              <div class="col-span-2 flex justify-start">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 ml-5">
                  ذخیره
                </button>
                <button
                type="button"
                @click="closeAddModal"
                class="ml-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                >
                لغو
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    words: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      showSearchModal: false,
      searchTerm: "",
      showAddModal: false,
      newWord: {
        word: "",
        meaning: "",
        pronansiation: "",
        description: "",
      },
      showEditModal: false,
      editForm: {
        id: null,
        word: "",
        meaning: "",
        pronansiation: "",
        description: "",
      },
      showModal: false,
      selectedWord: {},
    };
  },
  methods: {
    openSearchModal() {
      this.showSearchModal = true;
    },
    closeSearchModal() {
      this.showSearchModal = false;
      this.searchTerm = ""; // پاک کردن عبارت جستجو هنگام بستن ماژول
    },
    // handleClickOutside(event) {
    //   if (
    //     this.showSearchModal &&
    //     !event.target.closest(".absolute") &&
    //     !event.target.closest("input")
    //   ) {
    //     this.closeSearchModal();
    //   }
    // },
    addWord() {
      axios.post(route("words.store"), this.newWord).then((response) => {
        this.words.push(response.data);
        this.closeAddModal();
        location.reload();
      });
    },
    closeAddModal() {
      this.showAddModal = false;
      this.newWord = {
        word: "",
        meaning: "",
        pronansiation: "",
        description: "",
      };
    },
    viewWord(word) {
      this.selectedWord = word;
      this.showModal = true;
    },
    closeModal() {
      this.showSearchModal = false;
      this.showModal = false;
      this.showEditModal = false;
      this.showSearchModal = false;
      this.searchTerm = "";
      this.showAddModal = false;
      this.newWord = {
        word: "",
        meaning: "",
        pronansiation: "",
        description: "",
      };
    },
    editWord(word) {
      this.editForm = { ...word }; // کپی مشخصات کلمه برای ویرایش
      this.showEditModal = true;
    },
    closeEditModal() {
      this.showEditModal = false;
    },
    async saveWord() {
      // try {
        // ارسال درخواست PUT به روت update
        axios.put(route("words.update", this.editForm.id), this.editForm);
        location.reload();
        const wordIndex = this.words.findIndex(
          (word) => word.id === this.editForm.id
        );
        if (wordIndex !== -1) {
          this.$set(this.words, wordIndex, { ...this.editForm });
        }
        this.closeEditModal();

      // } catch (error) {
      //   console.error("Error updating word:", error);
      // }
    },
  },
  computed: {
    // فیلتر کلمات بر اساس کلمه یا معنی
    filteredWords() {
      const term = this.searchTerm.trim().toLowerCase();
      if (!term) return [];
      return this.words.filter(
        (word) =>
          word.word.toLowerCase().includes(term) ||
          word.meaning.toLowerCase().includes(term)
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

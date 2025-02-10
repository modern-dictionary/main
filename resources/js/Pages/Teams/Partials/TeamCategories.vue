<template>
    <FormSection @submitted="updateTeamCategories" class="p-5 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50">
        <template #title>
            <span class="dark:text-white text-black">دسته‌بندی‌های تیم</span>
        </template>

        <template #description>
            <span class="dark:text-white text-black">دسته‌بندی‌های مرتبط با تیم خود را انتخاب کنید.</span>
        </template>

        <template #form>
            <!-- انتخاب دسته‌بندی‌ها -->
            <div class="col-span-5">
                <label class="block font-medium dark:text-white text-black text-sm mb-1">دسته‌بندی‌ها:</label>
                <select v-model="form.selectedCategories" multiple
                        class="w-full px-3 py-1.5 rounded-lg border border-gray-700 bg-gray-800/50 dark:text-white text-black focus:outline-none focus:ring-2 focus:ring-[#FF2D20] transition-all duration-200 text-sm">
                    <option v-for="category in allCategories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>

                <!-- نمایش دسته‌بندی‌های انتخاب‌شده -->
                <div class="flex flex-wrap gap-2 mt-2">
                    <span v-for="categoryId in form.selectedCategories" :key="categoryId"
                          class="bg-gray-600 dark:text-white text-black text-xs px-2 py-0.5 rounded-full flex items-center">
                        {{ getCategoryName(categoryId) }}
                        <button type="button" @click="removeCategory(categoryId)" class="ml-1.5 text-red-400">×</button>
                    </span>
                </div>
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3 dark:text-white text-black">
                ذخیره شد.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                           class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50">
                ذخیره
            </PrimaryButton>
        </template>
    </FormSection>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    team: Object,
    categories: Array,
    allCategories: Array,
    availableRoles: Array,
    userPermissions: Object,
});

// استفاده از useForm برای مدیریت وضعیت انتخاب دسته‌بندی‌ها
const form = useForm({
    selectedCategories: props.categories.map(category => category.id),
});

const updateTeamCategories = () => {
    form.put(route('team-categories.update', props.team), {
        errorBag: 'updateTeamCategories',
        preserveScroll: true,
    });
};

const getCategoryName = (id) => {
    const category = props.allCategories.find(cat => cat.id === id);
    return category ? category.name : '';
};

const removeCategory = (id) => {
    form.selectedCategories = form.selectedCategories.filter(categoryId => categoryId !== id);
};
</script>

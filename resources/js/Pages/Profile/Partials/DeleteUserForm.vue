<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection class="p-5 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1">
        <template #title>
            <span class="text-white">Delete Account</span>
        </template>

        <template #description>
            <span class="text-white">Permanently delete your account.</span>
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmUserDeletion"
                    class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1">
                    Delete Account
                </DangerButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <DialogModal :show="confirmingUserDeletion" @close="closeModal">
                <template #title>
                    <span class="text-white">Delete Account</span>
                </template>

                <template #content>
                    <div class="text-white rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1">
                        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.

                        <div class="mt-4">
                            <TextInput
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                class="mt-1 block w-3/4 text-white rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1"
                                placeholder="Password"
                                autocomplete="current-password"
                                @keyup.enter="deleteUser"
                            />

                            <InputError :message="form.errors.password" class="mt-2 text-white" />
                        </div>
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton @click="closeModal"
                        class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1">
                        <span class="text-white">Cancel</span>
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 transform hover:-translate-y-1"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        <span class="text-white">Delete Account</span>
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>

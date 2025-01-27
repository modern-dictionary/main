<script setup>
import { ref, computed, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmsPassword from '@/Components/ConfirmsPassword.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: '',
});

const twoFactorEnabled = computed(
    () => ! enabling.value && page.props.auth.user?.two_factor_enabled,
);

watch(twoFactorEnabled, () => {
    if (! twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(route('two-factor.enable'), {}, {
        preserveScroll: true,
        onSuccess: () => Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ]),
        onFinish: () => {
            enabling.value = false;
            confirming.value = props.requiresConfirmation;
        },
    });
};

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route('two-factor.confirm'), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route('two-factor.recovery-codes'))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route('two-factor.disable'), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection class="p-5 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 slide-up">
        <template #title>
            <span class="text-white">Two Factor Authentication</span>
        </template>

        <template #description>
            <span class="text-white">Add additional security to your account using two factor authentication.</span>
        </template>

        <template #content>
            <h3 v-if="twoFactorEnabled && ! confirming" class="text-lg font-medium text-white">
                You have enabled two factor authentication.
            </h3>

            <h3 v-else-if="twoFactorEnabled && confirming" class="text-lg font-medium text-white">
                Finish enabling two factor authentication.
            </h3>

            <h3 v-else class="text-lg font-medium text-white">
                You have not enabled two factor authentication.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-white">
                <p>
                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-white">
                        <p v-if="confirming" class="font-semibold">
                            To finish enabling two factor authentication, scan the following QR code using your phone's authenticator application or enter the setup key and provide the generated OTP code.
                        </p>

                        <p v-else>
                            Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application or enter the setup key.
                        </p>
                    </div>

                    <div class="mt-4 p-2 inline-block bg-white rounded-lg" v-html="qrCode" />

                    <div v-if="setupKey" class="mt-4 max-w-xl text-sm text-white">
                        <p class="font-semibold">
                            Setup Key: <span v-html="setupKey"></span>
                        </p>
                    </div>

                    <div v-if="confirming" class="mt-4">
                        <InputLabel for="code" value="Code" class="text-white" />

                        <TextInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="block mt-1 w-1/2 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication"
                        />

                        <InputError :message="confirmationForm.errors.code" class="mt-2" />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && ! confirming">
                    <div class="mt-4 max-w-xl text-sm text-white">
                        <p class="font-semibold">
                            Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-900 text-white rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50">
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="! twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <PrimaryButton
                            type="button"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                            class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                        >
                            <span class="black-white">Enable</span>
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="me-3 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            <span class="text-white">Confirm</span>
                        </PrimaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && ! confirming"
                            class="me-3 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                        >
                            <span class="text-white">Regenerate Recovery Codes</span>
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && ! confirming"
                            class="me-3 rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                        >
                            <span class="text-white">Show Recovery Codes</span>
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <SecondaryButton
                            v-if="confirming"
                            class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            <span class="text-white">Cancel</span>
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton
                            v-if="! confirming"
                            class="rounded-lg hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            <span class="text-white">Disable</span>
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>

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

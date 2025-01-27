<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-700 focus:border-[#FF2D20] focus:ring-[#FF2D20] rounded-md shadow-sm bg-gray-700/50 text-white/90"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 dark:border-indigo-600 text-start text-base font-medium text-white bg-indigo-50/10 hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 focus:outline-none'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white hover:ring-white/20 hover:shadow-xl hover:shadow-[#FF2D20]/10 transition duration-300 hover:bg-gray-700/50 focus:outline-none';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-start">
            <slot />
        </button>

        <a v-else-if="as == 'a'" :class="classes" class="w-full text-start" :href="href">
            <slot />
        </a>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>

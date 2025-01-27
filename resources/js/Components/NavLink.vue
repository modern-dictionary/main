<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-white    hover:shadow-[#FF2D20]/10 transition duration-300  transform hover:translate-y-[-4px] focus:outline-none focus:border-indigo-700'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white rounded-lg  hover:shadow-[#FF2D20]/10 transition duration-300  transform hover:translate-y-[-4px] focus:outline-none focus:text-white focus:border-gray-300';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

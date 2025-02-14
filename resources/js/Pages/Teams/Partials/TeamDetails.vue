<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-900 p-6 rounded-lg w-full max-w-2xl">
            <button @click="$emit('close')"
                class="mb-4 px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                {{ $t('back') }}
            </button>

            <h2 class="text-2xl font-bold">{{ team.name }}</h2>
            <p class="text-gray-400">{{ $t('team_owner') }}: {{ team.owner.name }}</p>
            <p class="text-gray-400">{{ $t('member_count') }}: {{ 1 + team.users_count }}</p>
            <p class="text-gray-400">{{ $t('word_count') }}: {{ team.words_count }}</p>

            <!-- لیست اعضا -->
            <h3 class="mt-4 text-lg font-semibold">{{ $t('users') }}</h3>
            <ul class="space-y-2 mt-2">
                <li v-for="user in team.users" :key="user.id" class="p-2 bg-gray-700 rounded">
                    {{ user.name }} ({{ user.email }})
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        team: Object,
    },
};
</script>

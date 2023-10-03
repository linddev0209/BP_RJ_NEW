<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Calendar from '@/Components/Calendar.vue'
import { Head } from '@inertiajs/vue3';
import { provide } from 'vue';

const {vacations, acceptedVacations, notifications, users, userId, userType} = defineProps(['vacations', 'acceptedVacations', 'notifications', 'users', 'userId', 'userType']);

provide(/* key */ 'vacations', /* value */ vacations);
provide(/* key */ 'acceptedVacations', /* value */ acceptedVacations);
provide(/* key */ 'notifications', /* value */ notifications);
provide(/* key */ 'users', /* value */ users);
provide(/* key */ 'userId', /* value */ userId);
provide(/* key */ 'userType', /* value */ userType);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <h1 class="text-3xl text-center font-bold tracking-tight text-gray-600" style="padding-top: 50px;">Calendar Part</h1>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Calendar/>
                </div>
            </div>
        </div>
        
    </AuthenticatedLayout>

</template>

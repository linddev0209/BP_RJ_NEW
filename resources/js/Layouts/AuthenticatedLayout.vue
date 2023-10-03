<script setup>
import { inject, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Notification from '@/Components/Notification.vue';
import NotiDropDown from '@/Components/NofiDropDown.vue';

const showingNavigationDropdown = ref(false);
const notis = inject('notifications')
const vacas = inject('vacations')
const users = inject('users')
const userType = inject('userType');
const managerEmails = [];
users.map((user)=>{
    if(user.user_type==="manager")
    managerEmails.push(user.email);
})

// Check if 'notis' is empty
const isNotisEmpty = notis && notis.length > 0;
const isNotisEmptyVac = vacas && vacas.length > 0;
const emit = defineEmits();

async function notihandleMgr(){
    const link = document.getElementById('hiddenLink');
    link.click();
}

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('ferie.index')" :active="route().current('ferie.index')">
                                    Personal Request
                                </NavLink>
                                <NavLink v-if="userType=='manager'" :href="route('manager_user.index')" :active="route().current('manager_user.index')">
                                    User Info
                                </NavLink>
                                <NavLink id="hiddenLink" :href="route('ferie.index', { tnum: 2 })" :active="route().current('ferie.index')" style="display: none;">
                                    Personal Request
                                </NavLink>
                            </div>
                        </div>
                        <div class="flex-grow"></div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <span>{{ $page.props.auth.user.name }} {{ $page.props.auth.user.user_type }}</span>

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <NotiDropDown align="left" >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                v-if="(isNotisEmpty && !managerEmails.includes($page.props.auth.user.email)) || (isNotisEmptyVac && managerEmails.includes($page.props.auth.user.email))"
                                                type="button"
                                                class="inline-flex items-left px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <svg width="24px" height="24px" viewBox="0 0 24 24" id="_24x24_On_Light_Notification-Alert" data-name="24x24/On Light/Notification-Alert" xmlns="http://www.w3.org/2000/svg">
                                                    <rect id="view-box" width="24" height="24" fill="none"/>
                                                    <path id="Shape" d="M6,17v-.5H2.25A2.253,2.253,0,0,1,0,14.25v-.382a2.542,2.542,0,0,1,1.415-2.289A1.247,1.247,0,0,0,2.1,10.572l.446-4.91A6.227,6.227,0,0,1,10.618.286a5.477,5.477,0,0,0-.635,1.374A4.794,4.794,0,0,0,8.75,1.5,4.7,4.7,0,0,0,4.045,5.8L3.6,10.708A2.739,2.739,0,0,1,2.089,12.92a1.055,1.055,0,0,0-.589.949v.382A.751.751,0,0,0,2.25,15h13A.751.751,0,0,0,16,14.25v-.382a1.053,1.053,0,0,0-.586-.948A2.739,2.739,0,0,1,13.9,10.708l-.2-2.18a5.473,5.473,0,0,0,1.526.221l.166,1.822a1.26,1.26,0,0,0,.686,1.005,2.547,2.547,0,0,1,1.418,2.29v.382a2.252,2.252,0,0,1-2.25,2.25H11.5V17A2.75,2.75,0,0,1,6,17Zm1.5,0A1.25,1.25,0,0,0,10,17v-.5H7.5ZM15.047,6.744A3.486,3.486,0,0,1,13.5,6.28L13.456,5.8a4.7,4.7,0,0,0-1.648-3.185,3.5,3.5,0,0,1,.61-1.417A6.221,6.221,0,0,1,14.95,5.662l.1,1.081v0Z" transform="translate(3.25 2.25)" fill="#141124"/>
                                                    <path id="Shape-2" data-name="Shape" d="M3.5,7A3.5,3.5,0,1,1,7,3.5,3.5,3.5,0,0,1,3.5,7Z" transform="translate(15 2)" fill="#ff6359"/>
                                                </svg>
                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                v-else
                                                type="button"
                                                class="inline-flex items-left px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                :disabled="true"
                                                style="cursor: pointer;"
                                            >
                                                <svg width="24px" height="24px" viewBox="0 0 24 24" id="_24x24_On_Light_Notification" data-name="24x24/On Light/Notification" xmlns="http://www.w3.org/2000/svg">
                                                    <rect id="view-box" width="24" height="24" fill="none"/>
                                                    <path id="Shape" d="M6,17v-.5H2.25A2.253,2.253,0,0,1,0,14.25v-.382a2.542,2.542,0,0,1,1.415-2.289A1.248,1.248,0,0,0,2.1,10.572l.446-4.91a6.225,6.225,0,0,1,12.4,0l.446,4.91a1.26,1.26,0,0,0,.686,1.005,2.547,2.547,0,0,1,1.418,2.29v.382a2.252,2.252,0,0,1-2.25,2.25H11.5V17A2.75,2.75,0,0,1,6,17Zm1.5,0A1.25,1.25,0,0,0,10,17v-.5H7.5ZM4.045,5.8,3.6,10.708A2.738,2.738,0,0,1,2.089,12.92a1.055,1.055,0,0,0-.589.949v.382A.751.751,0,0,0,2.25,15h13A.751.751,0,0,0,16,14.25v-.382a1.054,1.054,0,0,0-.586-.948A2.739,2.739,0,0,1,13.9,10.708L13.456,5.8a4.725,4.725,0,0,0-9.411,0Z" transform="translate(3.25 2.25)" fill="#141124"/>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <Notification v-if="managerEmails.includes($page.props.auth.user.email)" v-for="vacation in vacas" class="cursor-pointer" :title="'Requeset from '+ vacation.user.name" :createDate="vacation.start_date+' ~ '+vacation.end_date" :type="'Pending'" @click="notihandleMgr()"/>
                                        <Notification v-else v-for="notification in notis" class="cursor-pointer" :title="notification.notititle" :createDate="notification.notified_at" :type="notification.notitype" @click="notihandleMgr()" />
                                    </template>
                                </NotiDropDown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('ferie.index')" :active="route().current('ferie.index')">
                            Richiedi ferie
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('manager_user.index')" :active="route().current('manager_user.index')">
                            User Info
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}{{ $page.props.auth.user.user_type }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Your existing styles */

/* Define the hover-gray class */
.hover-gray {
    background-color: #E5E7EB; /* Change to your desired background color */
}

/* Add cursor-pointer class */
.cursor-pointer {
    cursor: pointer;
}
</style>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserManager from "@/Components/UserManager.vue";
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue'
import DropdownOption from '@/Components/DropdownOption.vue';
import Checkbox from '@/Components/Checkbox.vue';
import {useForm, Head} from '@inertiajs/vue3';
import Calendar from '@/Components/Calendar.vue'

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import {Italian} from 'flatpickr/dist/l10n/it.js';
import axios from 'axios';
import { ref, defineProps, provide } from 'vue';
import Modal from '@/Components/Modal.vue';
import { createHydrationRenderer } from 'vue';

const isModalVisible = ref(false);
const dropdownOptions = ["MARRIAGE LEAVE", "PARENTAL LEAVE", "APPRENTICESHIP COURSE", "COMPANY MEDICAL EXAMINATION", "PAID PERMIT", "NON-MANDATORY TRAINING COURSE", 
                         "COMPULSORY TRAINING COURSE", "BLOOD DONATION", "STUDY PERMIT", "HOLIDAYS"];
const subbuttons=["", "Pending List", "Accepted List", "Rejected List", "Pending List", "Deleted List", "Request History"];
const adminEmails = ['lindseyhildebrand0209@gmail.com'];
const managerEmails = [];
const enddatestate = ref(false);
const vacationRefs = ref();
const ajax_flag = ref(false);
const requestListTitle = ref('User Info');
const {vacations, notifications, users, userType} = defineProps(['vacations', 'notifications', 'users', 'userType']);
const whichPage = ref(1);

provide(/* key */ 'notifications', /* value */ notifications);
provide(/* key */ 'vacations', /* value */ vacations);
provide(/* key */ 'users', /* value */ users);
provide(/* key */ 'userType', /* value */ userType);
let employees = [];
employees = users.filter((user)=>user.user_type=="employee")
users.map((user)=>{
    if(user.user_type==="manager")
    managerEmails.push(user.email);
})

function isManager(email){
    return managerEmails.includes(email);
}

</script>

<template>
    <Head title="User Info"/>

    <AuthenticatedLayout @handleMgrNotiTest="handleMNoti()">

        <!-- REQUEST LIST PART -->
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 v-if="!isManager($page.props.auth.user.email)" style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <h1 v-else style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <div id="listDom" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <UserManager v-for="employee in employees" :key="employee.id" :employee="employee" :managerEmails="managerEmails" @handleTest="handleTest()"/>
            </div>
        </div>
        <!-- END -->
    </AuthenticatedLayout>
</template>

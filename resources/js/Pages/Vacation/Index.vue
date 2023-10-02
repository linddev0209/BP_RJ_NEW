<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Vacation from "@/Components/Vacation.vue";
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

const isModalVisible = ref(false);
const dropdownOptions = ["MARRIAGE LEAVE", "PARENTAL LEAVE", "APPRENTICESHIP COURSE", "COMPANY MEDICAL EXAMINATION", "PAID PERMIT", "NON-MANDATORY TRAINING COURSE", 
                         "COMPULSORY TRAINING COURSE", "BLOOD DONATION", "STUDY PERMIT", "HOLIDAYS"];
const subbuttons=["", "Pending List", "Accepted List", "Rejected List", "Pending List", "Deleted List", "Request History"];
const adminEmails = ['sr.dev529@gmail.com'];
const managerEmails = ['sr.dev529@gmail.com'];
const enddatestate = ref(false);
const vacationRefs = ref();
const ajax_flag = ref(false);
const requestListTitle = ref('Pending List');
const {vacations, notifications} = defineProps(['vacations', 'notifications']);
const whichPage = ref(1);

provide(/* key */ 'notifications', /* value */ notifications);
provide(/* key */ 'vacations', /* value */ vacations)

const selectOption = (idx) => {
    resetEverything();
    // UI handle
    switch (idx)
    {
        case 0:
        case 1:
        case 9:
            setVisibleTrue('none');                 // hide the check box div
            setDisableTrue(false);                  // enable end date
            break;
        case 2:
        case 5:
        case 6:
        case 7:
        case 8:
            setVisibleTrue('');                     // show the check box div
            setDisableTrue(true);                   // disable end date
            setVisibleTrueChk('', 'chkbox1');       // show chkbox1
            setVisibleTrueChk('', 'chkbox2');       // show chkbox2
            break;
        case 3:
            setVisibleTrue('');                     // show the check box div
            setVisibleTrueChk('none', 'chkbox2');   // hide chkbox2
            setVisibleTrueChk('', 'chkbox1');       // show chkbox1
            break;
        case 4:
            setVisibleTrue('');                     // show the check box div
            setVisibleTrueChk('none', 'chkbox1');   // hide chkbox1
            setVisibleTrueChk('', 'chkbox2');       // show chkbox2
            setDisableTrue(false);
            break;
        default:
            setVisibleTrue('');                     // show the check box div
            setDisableTrue(false);                  // enable end date
            setVisibleTrueChk('', 'chkbox1');       // show the chkbox1
            setVisibleTrueChk('', 'chkbox2');       // show the chkbox2    
    }

    // Emit an event with the selected option text
    document.getElementById('onlyText').textContent = dropdownOptions[idx];
    form.request_type = dropdownOptions[idx];
}

const s_time = ref('');
const e_time = ref('');
const s_date = ref('');
const e_date = ref('');

const form = useForm({
    request_type: '',
    reason: '',
    end_date: '',
    start_date: '',
    end_time: '00:00',
    start_time: '00:00'
});

const handleTest = () => {
    showModal( false );
}

function resetEverything(){
    document.getElementById('onlyText').textContent = 'SELECT REQUEST';
    setDisableTrue(false);
    document.getElementById('wholeday').checked = false;
    document.getElementById('period').checked = false;
    document.getElementById('startTime').value = '';
    document.getElementById('endTime').value = '';
    document.getElementById('fromthe').value = '';
    document.getElementById('tothe').value = '';
    document.getElementById('textAreaId').text = '';
    form.reset();
}

function setDisableTrue(flag){
    enddatestate.value = flag;
    const divElement = document.getElementById('tothediv');
    const childElements = divElement.querySelectorAll("input");
    childElements.forEach((element) => {
        if( element.type == 'text')
        {
            if( flag == false )
            {
                element.style.backgroundColor="white";
                element.value = '';
            }
            else
                element.style.backgroundColor="lightgrey";
        }
    });
}

function setVisibleTrue(flag){
    document.getElementById('chkbox').style.display = flag;
    setVisibleTrueDalle(flag);
}

function setVisibleTrueDalle(flag){
    document.getElementById('dalleat').style.display = flag;
}

function setVisibleTrueChk(flag, id){
    document.getElementById(id).style.display = flag;
}

function chkboxClicked(flag){
    var selectedOption = document.getElementById('onlyText').textContent;
    if( selectedOption == 'APPRENTICESHIP COURSE' || selectedOption == 'NON-MANDATORY TRAINING COURSE' || selectedOption == 'COMPULSORY TRAINING COURSE' || selectedOption == 'BLOOD DONATION' || selectedOption == 'STUDY PERMIT'){
        if( flag == 1 )
        {
            if( document.getElementById('wholeday').checked == true ) 
            {
                setDisableTrue(false);
                setVisibleTrueDalle('none');
                setVisibleTrueChk('none', 'chkbox2');
            }
            else
            {
                setDisableTrue(true);
                setVisibleTrueDalle('');
                setVisibleTrueChk('', 'chkbox2');
            }
            
        }
        else
        {
            if( document.getElementById('period').checked == true ) 
            {
                setDisableTrue(false);
                setVisibleTrueDalle('');
                setVisibleTrueChk('none', 'chkbox1');
            }
            else
            {
                setDisableTrue(true);
                setVisibleTrueChk('', 'chkbox1');
            }
        }
    }
    else if(selectedOption == 'COMPANY MEDICAL EXAMINATION'){
        if( flag == 1 )
        {
            if( document.getElementById('wholeday').checked == true ) 
                setVisibleTrueDalle('none');
            else
                setVisibleTrueDalle('');
        }
    }
    else if( selectedOption == 'PAID PERMIT' ){
        if( flag == 2 )
        {
            if( document.getElementById('period').checked == true ) 
                setDisableTrue(true);
            else
                setDisableTrue(false);
        }
    }
}

function startDate() {
    form.start_date = s_date.value
    if( enddatestate.value )
        e_date.value = s_date.value;
}

function endDate() {
    form.end_date = e_date.value
}

function startTime(){
    const splitArray = s_time.value.split(":");
    const intValue = parseInt(splitArray[1]);
    if( intValue <= 30 )
        form.start_time = splitArray[0]+":00"
    else
        form.start_time = splitArray[0]+":30"
}

function endTime(){
    const splitArray = e_time.value.split(":");
    const intValue = parseInt(splitArray[1]);
    if( intValue <= 30 )
        form.end_time = splitArray[0]+":00"
    else
        form.end_time = splitArray[0]+":30"
}

const config_time = ref({
// set wrap to true only when using 'input-group'
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});

const config_start = ref({
// set wrap to true only when using 'input-group'
    maxDate: e_date,
    altFormat: 'd-m-Y',
    dateFormat: 'd-m-Y',
    locale: Italian, // locale for this instance only

    "disable": [
        function (date) {
            // return true to disable
            return (date.getDay() === 0 || date.getDay() === 6);

        },
        {
            from:"01-01-1901",
            to: new Date().fp_incr(-1),
        }

    ]
});


const config_end = ref({
    minDate: s_date,
    altFormat: 'd-m-Y',
    altInput: true,
    dateFormat: 'd-m-Y',
    locale: Italian, // locale for this instance only

    "disable": [
        function (date) {
            // return true to disable
            return (date.getDay() === 0 || date.getDay() === 6);

        },
        {
            from:"01-01-1901",
            to: new Date().fp_incr(-1),
        }
    ]
});

const isActive1 = ref(true);
const isActive2 = ref(false);
const isActive3 = ref(false);
const isActive4 = ref(true);
const isActive5 = ref(false);
const isActive6 = ref(false);

const toggleActive = (num) => {
    whichPage.value = num;
    switch(num){
        case 1:
            isActive1.value = true;
            isActive2.value = false;
            isActive3.value = false;
            updateShow( 'Pending' );
            requestListTitle.value = 'Pending List'
            break;
        case 2:
            isActive1.value = false;
            isActive2.value = true;
            isActive3.value = false;
            updateShow( 'Accepted' );
            requestListTitle.value = 'Accepted List'
            break;
        case 3:
            isActive1.value = false;
            isActive2.value = false;
            isActive3.value = true;
            updateShow( 'Rejected' );
            requestListTitle.value = 'Rejected List'
            break;
        case 4:
            isActive4.value = true;
            isActive5.value = false;
            isActive6.value = false;
            updateShow( 'Pending' );
            requestListTitle.value = 'Pending List'
            break;
        case 5:
            isActive4.value = false;
            isActive5.value = true;
            isActive6.value = false;
            updateShow( 'Deleted' );
            requestListTitle.value = 'Deleted List'
            break;
        case 6:
            isActive4.value = false;
            isActive5.value = false;
            isActive6.value = true;
            updateShow( 'All' );
            requestListTitle.value = 'Request History'
            break;
    }
};

function isManager(email){
    return managerEmails.includes(email);
}

async function updateShow(status) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const response = await axios.post('/updateVacation', {
            data: {
                'status': status,
            }
        },{
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        // Handle the response
        vacationRefs.value = response.data.vacations;
        ajax_flag.value = true;
        //document.getElementById('listDom').innerHTML = "<Vacation v-for='vacation in "+response.data.vacations+"' :key='vacation.id':vacation='vacation'/>";
        
    } catch (error) {
        // Handle errors
        refreshPage();
    }
}

function refreshPage(){
    location.reload();
}

const showModal = ( flag ) => {
    if( flag )
    {
        resetEverything();
        whichPage.value = 1;
    }
    isModalVisible.value = true;
};

const closeModal = () => {
    toggleActive(whichPage.value);
    isModalVisible.value = false;
};

</script>

<template>
    <Modal :show="isModalVisible" @close="closeModal">
        <template #content>
            <!-- Container for modal content -->
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all" :class="maxWidthClass">
                <!-- SVG Icon as a Circle -->
                <div class="p-2 text-center">
                    <div class="h-20 w-20 mx-auto mb-0 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                        <svg v-if="statusFlag=='Accepted'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#34a853">
                            <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                            <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        </svg>
                        <svg v-if="statusFlag=='Rejected'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#ea4335">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                        </svg>
                        <svg v-if="statusFlag=='Require Action'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#4285f4">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
                        </svg>
                        <svg v-if="statusFlag=='Deleted' || statusFlag=='DDeleted'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#000000">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </div>
                </div>

                <!-- Modal Header -->
                <div class="p-2 text-center border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Successfully {{ statusFlag }}</h2>
                </div>

                <!-- Modal Body -->
                <div class="p-4 text-center">
                    
                </div>

                <!-- Modal Footer -->
                <div class="p-2 flex justify-center" style="
                        padding-top: 0px;
                        padding-bottom: 16px" >
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Confirm
                    </button>
                </div>
            </div>
        </template>
    </Modal>
    <Head title="Richiedi ferie"/>

    <AuthenticatedLayout @handleMgrNotiTest="handleMNoti()">
        <div v-if="!isManager($page.props.auth.user.email)" class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('ferie.store'), { onSuccess: () => showModal( true ) })">                
                <!-- DROP DOWN -->
                <h1 class="text-3xl text-center font-bold tracking-tight text-gray-600">Personal Request</h1>
                <div style="margin-top: 1rem;" class="basis-1/2">
                    <!-- Use the Dropdown component -->
                    <Dropdown>
                        <template #trigger>
                            <button
                                id="selectButton"
                                type="button"
                                class="py-2 bg-blue-500 flex items-center justify-center text-white rounded hover:bg-blue-600 w-full"
                            >
                                <span id="onlyText">SELECT REQUEST</span>

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
                        </template>

                        <template #content >
                            <DropdownOption v-for="index in 11" v-model="request_type" @click="selectOption(index-1)" :key="index"> {{dropdownOptions[index-1]}} </DropdownOption>
                        </template>
                    </Dropdown>
                    <InputError :message="form.errors.request_type" class="mt-2"/>
                </div>
                <!-- END -->

                <!-- CHECK BOX LINE -->
                <div id="chkbox" style="margin-top: 1rem;" class="flex  flex-row gap-4" >
                    
                    <div class="basis-1/2">
                        <label id="chkbox1" class="inline-flex items-center cursor-pointer" @click="chkboxClicked(1)">
                            <Checkbox id="wholeday" />
                            <p class="ml-2">Whole Day</p>
                        </label>
                    </div>

                    <div class="basis-1/2">
                        <label id="chkbox2" class="inline-flex items-center cursor-pointer" @click="chkboxClicked(2)">
                            <Checkbox id="period" />
                            <p class="ml-2">Periodical by Hour</p>
                        </label>
                    </div>

                </div>
                <!-- END -->

                <!-- DATE LINE -->
                <div id="fromto" style="margin-top: 1rem;" class="flex  flex-row gap-4">
                    
                    <div class="basis-1/2">
                        <flat-pickr 
                                    id="fromthe"
                                    v-model="s_date"
                                    :config="config_start"
                                    placeholder="From the"
                                    class=" block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    readonly="readonly"
                                    @on-change="startDate()"
                        />
                        <InputError :message="form.errors.start_date" class="mt-2"/>
                    </div>
                    <div id="tothediv" class="basis-1/2">
                        <flat-pickr 
                                    id="tothe"
                                    v-model="e_date"
                                    :config="config_end"
                                    placeholder="To the"
                                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    readonly="readonly"
                                    @on-change="endDate()"
                                    v-bind:disabled="enddatestate"
                        />

                        <InputError :message="form.errors.end_date" class="mt-2"/>
                    </div>

                </div>
                <!-- END -->

                <!-- TIME LINE -->
                <div id="dalleat" style="margin-top: 1rem;" class="flex  flex-row gap-4">
                    
                    <div class="basis-1/2">
                        <flat-pickr 
                                    id="startTime"
                                    v-model="s_time"
                                    :config="config_time"
                                    placeholder="Dalle"
                                    class=" block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    readonly="readonly"
                                    @on-close="startTime()"
                        />
                        <InputError :message="form.errors.start_time" class="mt-2"/>
                    </div>
                    <div id="endTimediv" class="basis-1/2">
                        <flat-pickr 
                                    id="endTime"
                                    v-model="e_time"
                                    :config="config_time"
                                    placeholder="At"
                                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                    readonly="readonly"
                                    @on-close="endTime()"
                        />

                        <InputError :message="form.errors.end_time" class="mt-2"/>
                    </div>
                </div>
                <!-- END -->

                <!-- TEXTAREA LINE -->
                <textarea
                    id="textAreaId"
                    v-model="form.reason"
                    placeholder="Motivazione"
                    class="mt-4 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <!-- END -->

                <!-- SUBMIT BUTTON -->
                <InputError :message="form.errors.reason" class="mt-2"/>
                <PrimaryButton class="mt-4 justify-end">Send Request</PrimaryButton>
                <!-- END -->

            </form>
        </div>

        <!-- REQUEST LIST PART -->
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <h1 v-if="!isManager($page.props.auth.user.email)" style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <h1 v-else style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <div class="flex flex-1">
                <SecondaryButton v-if="!isManager($page.props.auth.user.email)" :active="isActive1" @click="toggleActive(1)" class="mt-4 flex-1 flex items-center justify-center">Pending List</SecondaryButton>
                <SecondaryButton v-if="!isManager($page.props.auth.user.email)" :active="isActive2" @click="toggleActive(2)" class="mt-4 flex-1 flex items-center justify-center">Accepted List</SecondaryButton>
                <SecondaryButton v-if="!isManager($page.props.auth.user.email)" :active="isActive3" @click="toggleActive(3)" class="mt-4 flex-1 flex items-center justify-center">Rejected List</SecondaryButton>
                <SecondaryButton v-if="isManager($page.props.auth.user.email)" :active="isActive4" @click="toggleActive(4)" class="mt-4 flex-1 flex items-center justify-center">Pending List</SecondaryButton>
                <SecondaryButton v-if="isManager($page.props.auth.user.email)" :active="isActive5" @click="toggleActive(5)" class="mt-4 flex-1 flex items-center justify-center">Deleted List</SecondaryButton>
                <SecondaryButton v-if="isManager($page.props.auth.user.email)" :active="isActive6" @click="toggleActive(6)" class="mt-4 flex-1 flex items-center justify-center">Request History</SecondaryButton>
            </div>
            <div id="listDom" class="mt-6 bg-white shadow-sm rounded-lg divide-y">

                <Vacation v-if="!ajax_flag" v-for="vacation in vacations" :key="vacation.id" :vacation="vacation" @handleTest="handleTest()"/>
                <Vacation v-else v-for="vacation in vacationRefs" :vacation="vacation" @handleTest="handleTest()"/>
            </div>
        </div>
        <!-- END -->
    </AuthenticatedLayout>
</template>

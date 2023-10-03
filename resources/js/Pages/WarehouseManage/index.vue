<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Tab from "@/Components/Tab.vue";
import {Head} from '@inertiajs/vue3';
import 'flatpickr/dist/flatpickr.css';
import { ref, defineProps, provide } from 'vue';
import Modal from '@/Components/Modal.vue';

const isModalVisible = ref(false);
const statusMessage = ref('');
const statusMessageType = ref(1);
const managerEmails = [];
const {requesters, warehoses, products, instruments, vehciles, users, userType} = defineProps(['requesters', 'warehoses', 'products', 'instruments', 'vehciles', 'users', 'userType']);

provide(/* key */ 'requesters', /* value */ requesters);
provide(/* key */ 'warehoses', /* value */ warehoses);
provide(/* key */ 'products', /* value */ products);
provide(/* key */ 'instruments', /* value */ instruments);
provide(/* key */ 'vehciles', /* value */ vehciles);
provide(/* key */ 'users', /* value */ users);
provide(/* key */ 'userType', /* value */ userType);

console.log(requesters);
console.log(warehoses);

let employees = [];
employees = users.filter((user)=>user.user_type=="employee")
users.map((user)=>{
    if(user.user_type==="manager")
    managerEmails.push(user.email);
})

function isManager(email){
    return managerEmails.includes(email);
}

const categoryNamesM = [
  'PIV_In_Warehouse',
  'PIVs_Requests',
  'PIV_Registration',
  'PIV_Update',
];

const categoryNamesU = [
  'Send_PIVs_Request',
  'PIV_Request_Status',
];

var categoryNames = [];
categoryNames = categoryNamesM;
const categories = ref([]);

if( userType == 'employee' )
    categoryNames = categoryNamesU;

    const temp = [];

// for (let i = 0; i < 5; i++) {
//     // Customize the values for each object
//     const newObj = {
//         id: i + 1, // Customize the ID
//         title: `Custom Title ${i + 1}`, // Customize the title
//         date: `${i}h ago`, // Customize the date
//         commentCount: i * 2, // Customize the comment count
//         shareCount: i * 3, // Customize the share count
//     };

//     // Push the customized object into the array
//     temp.push(newObj);
// }

for (const categoryName of categoryNames) {
    const temp = [];
    var flag = false;

    if( categoryName == 'PIVs_Requests' || categoryName == 'PIV_Request_Status')
    {
        for( const requester of requesters )
        {
            for( const warehose of warehoses )
            {
                const rowdata = {};
                
                if( requester.id == warehose.requesterid )
                {
                    flag = true;
                    rowdata.value = {
                        id: warehose.id, // Customize the ID
                        pivid: warehose.PIVid, // Customize the ID
                        reqid: requester.id, // Customize the ID
                        databaseid: warehose.PIVtype, // Customize the ID
                        title: requester.username, // Customize the title
                        date: requester.request_date, // Customize the date
                        name: warehose.PIVname, // Customize the date
                        quantity: warehose.quantity,
                        returnedNum: warehose.returned, // Customize the comment count
                        allowedNum: warehose.tempered, // Customize the share count
                    };
                }
                else
                    continue;
                
                if (categories.value && Array.isArray(categories.value[categoryName])) {
                    categories.value[categoryName].push(rowdata);
                } else {
                // Handle the error gracefully, e.g., by initializing the array if it doesn't exist.
                    categories.value[categoryName] = [rowdata];
                }
            }
        }
        
        if( !flag )
            categories.value[categoryName] = [];

    }
    else
    {
        categories.value[categoryName] = [];
    }
}

const viptypes = [
    { id: 1, name: 'Products' },
    { id: 2, name: 'Instruments' },
    { id: 3, name: 'Vehicles' },
]

const viptypes1 = [
    { id: 1, name: 'Products1' },
    { id: 2, name: 'Instruments1' },
    { id: 3, name: 'Vehicles1' },
]

const viptypes2 = [
    { id: 1, name: 'Products2' },
    { id: 2, name: 'Instruments2' },
    { id: 3, name: 'Vehicles2' },
]

const closeModal = () => {
    isModalVisible.value = false;
    location.reload();
};

const showModal = (flag, id) => {
    statusMessageType.value = id;
    statusMessage.value = flag;
    isModalVisible.value = true;
};

</script>

<template>
    <Modal :show="isModalVisible" @close="closeModal">
        <template #content>
            <!-- Container for modal content -->
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all" :class="maxWidthClass">
                <!-- SVG Icon as a Circle -->
                <div class="p-2 text-center">
                    <div class="h-30 w-30 mx-auto mb-0 bg-grey-10 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                        <svg v-if="statusMessageType==2" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#34a853">
                            <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                            <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        </svg>
                        <svg v-if="statusMessageType==1" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#ea4335">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                        </svg>
                        <svg v-if="statusMessage=='Require Action'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#4285f4">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
                        </svg>
                        <svg v-if="statusMessage=='Deleted' || statusMessage=='DDeleted'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 0 24 24" width="48px" fill="#000000">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </div>
                </div>

                <!-- Modal Header -->
                <div class="p-1 text-center border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800" style="font-size: 1rem; margin-left: 10px; margin-right: 10px">{{ statusMessage }}</h2>
                </div>

                <!-- Modal Body -->
                <div class="p-2 text-center">
                    
                </div>

                <!-- Modal Footer -->
                <div class="p-2 flex justify-center" style="
                        padding-top: 0px;
                        padding-bottom: 16px" >
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" @click="closeModal">
                    Confirm
                    </button>
                </div>
            </div>
        </template>
    </Modal>
    <Head title="User Info"/>

    <AuthenticatedLayout @handleMgrNotiTest="handleMNoti()">

        <!-- REQUEST LIST PART -->
        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8" style="padding-left: 5px; padding-right: 5px">
            
            <h1 v-if="isManager($page.props.auth.user.email)" style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">Warehouse Management</h1>
            <h1 v-else style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">Warehouse Management</h1>

            <div v-if="isManager($page.props.auth.user.email)" id="listDom" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Tab :categories = "categories" :mflag = "isManager($page.props.auth.user.email)" :who = "$page.props.auth.user.name" :combodata = "viptypes" @on-load="showModal"/>
            </div>
            <div v-else id="listDom" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Tab :categories = "categories" :mflag = "isManager($page.props.auth.user.email)" :who = "$page.props.auth.user.name" :combodata = "viptypes" @on-load="showModal"/>
            </div>
        </div>
        <!-- END -->

    </AuthenticatedLayout>
</template>

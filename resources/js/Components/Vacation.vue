<script setup>
import axios from 'axios';
import { ref, defineEmits } from 'vue'

const isModalVisible = ref(false);
const statusFlag = ref();
const {vacation, managerEmails} = defineProps(['vacation', 'managerEmails']);
const emit = defineEmits();

async function onDeleteClick( id, status ){
    var user_id = document.getElementById('userId').textContent;
    var manager_name = document.getElementById('managerName').textContent;

    try {
        await axios.delete('/vacations/'+id+":"+status+":"+user_id+":"+manager_name);
        // Trigger a page refresh
        emit('handleTest');
    } catch (error) {
        console.error('Error deleting vacation:', error);
    }
}

</script>

<template>
    
    <p id="userId" style="display: none;">{{ vacation.user.id }}</p>
    <p id="managerName" style="display: none;">{{ $page.props.auth.user.name }}</p>
    <!-- If manager -->
    <div v-if="managerEmails.includes($page.props.auth.user.email)">
        <!-- If deleted -->
        <div v-if="vacation.delflag" style="background-color: lightgrey;">
            <div class="p-6 flex space-x-2">
                <div class="justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ vacation.user.name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{
                                    new Date(vacation.created_at).toLocaleString()
                                }}</small>
                        </div>
                        <button @click="onDeleteClick(vacation.id, 'DDeleted')">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                <path d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.request_type }}</p>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.start_date }} </span>
                    <span class="text-1xl  tracking-tight text-gray-600">  ~  </span>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.end_date }} </span>
                    <small v-if="vacation.start_time != '00:00'" class="ml-2 text-sm text-gray-600">{{vacation.start_time}}~{{ vacation.end_time }}</small>
                    <small v-else class="ml-2 text-sm text-gray-600">All day</small>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.reason }}</p>
                </div>

            </div>
            
            <div class="ml-6 mb-4" >
                <span
                    style="margin-bottom: 1rem;"
                    class="ml-4 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">Deleted
                </span>
            </div>
        </div>
        <!-- If not deleted -->
        <div v-else>
            <!-- If status is Pending -->
            <div v-if="vacation.status == 'Pending'" class="p-6 flex space-x-2">
                <div class="justify-end">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                    </svg>
                </div>


                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ vacation.user.name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{
                                    new Date(vacation.created_at).toLocaleString()
                                }}</small>
                        </div>
                        <div>
                            <button style="margin-right: 1rem;" @click="onDeleteClick(vacation.id, 'Accepted')">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#34a853">
                                    <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                                    <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                </svg>
                            </button>
                            <button style="margin-right: 1rem;" @click="onDeleteClick(vacation.id, 'Rejected')">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ea4335">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                </svg>
                            </button>
                            <button @click="onDeleteClick(vacation.id, 'Require Action')">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#4285f4">
                                    <path d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.request_type }}</p>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.start_date }} </span>
                    <span class="text-1xl  tracking-tight text-gray-600">  ~  </span>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.end_date }} </span>
                    <small v-if="vacation.start_time != '00:00'" class="ml-2 text-sm text-gray-600">{{vacation.start_time}}~{{ vacation.end_time }}</small>
                    <small v-else class="ml-2 text-sm text-gray-600">All day</small>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.reason }}</p>
                </div>
            </div>
            <!-- If status is not Pending -->
            <div v-else class="p-6 flex space-x-2">
                <div class="justify-end">
                    <svg v-if="vacation.status=='Accepted'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#34a853">
                        <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                        <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                    </svg>
                    <svg v-if="vacation.status=='Rejected'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ea4335">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                    <svg v-if="vacation.status=='Require Action'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#4285f4">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
                    </svg>
                </div>


                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ vacation.user.name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{
                                    new Date(vacation.created_at).toLocaleString()
                                }}</small>
                        </div>
                    </div>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.request_type }}</p>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.start_date }} </span>
                    <span class="text-1xl  tracking-tight text-gray-600">  ~  </span>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.end_date }} </span>
                    <small v-if="vacation.start_time != '00:00'" class="ml-2 text-sm text-gray-600">{{vacation.start_time}}~{{ vacation.end_time }}</small>
                    <small v-else class="ml-2 text-sm text-gray-600">All day</small>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.reason }}</p>
                </div>
            </div>
            
            <div class="ml-6 mb-4" >
                <span
                    style="margin-bottom: 1rem;"
                    class="ml-4 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ vacation.status }}
                </span>
            </div>
        </div>
    </div>
    <!-- If employee -->
    <div v-else>
        <!-- If only their history -->
        <div  v-if="vacation.user.id === $page.props.auth.user.id">
            <div class="p-6 flex space-x-2">
                <div class="justify-end">
                    <svg v-if="vacation.status=='Accepted'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#34a853">
                        <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                        <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                    </svg>
                    <svg v-if="vacation.status=='Rejected'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ea4335">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                    <svg v-if="vacation.status=='Require Action'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#4285f4">
                        <path d="M0 0h24v24H0z" fill="none"/>
                        <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
                    </svg>
                </div>


                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-800">{{ vacation.user.name }}</span>
                            <small class="ml-2 text-sm text-gray-600">{{
                                    new Date(vacation.created_at).toLocaleString()
                                }}</small>
                        </div>
                    </div>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.request_type }}</p>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.start_date }} </span>
                    <span class="text-1xl  tracking-tight text-gray-600">  ~  </span>
                    <span class="mt-4 text-1xl font-bold text-yellow-700">{{ vacation.end_date }} </span>
                    <small v-if="vacation.start_time != '00:00'" class="ml-2 text-sm text-gray-600">{{vacation.start_time}}~{{ vacation.end_time }}</small>
                    <small v-else class="ml-2 text-sm text-gray-600">All day</small>
                    <p class="mt-4 text-lg text-gray-600">{{ vacation.reason }}</p>
                </div>

            </div>
            <div class="ml-6 mb-4" >
                <span
                    style="margin-bottom: 1rem;"
                    class="ml-4 inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">{{ vacation.status }}
                </span>
            </div>
        </div>
    </div>
</template>

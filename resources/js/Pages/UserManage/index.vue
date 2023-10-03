<script setup>
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UserManager from "@/Components/UserManager.vue";
import 'flatpickr/dist/flatpickr.css';
import { ref, defineProps, provide, computed } from 'vue';
const managerEmails = [];
const requestListTitle = ref('User Info');
const {vacations, notifications, users, userType, userId} = defineProps(['vacations', 'notifications', 'users', 'userType', 'userId']);
const currentPage = ref(1); // Initialize currentPage to 1
const tempEmployeesArray = ref([]); // Use ref for reactive variable
const itemNumbers = 5;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

provide(/* key */ 'notifications', /* value */ notifications);
provide(/* key */ 'vacations', /* value */ vacations);
provide(/* key */ 'users', /* value */ users);
provide(/* key */ 'userType', /* value */ userType);
provide(/* key */ 'userId', /* value */ userId);
let employees = [];
employees = users.filter((user)=>user.user_type=="employee")
const totalPages = computed(() => Math.ceil(employees.length / itemNumbers));
users.map((user)=>{
    if(user.user_type==="manager")
    managerEmails.push(user.email);
})

function isManager(email){
    return managerEmails.includes(email);
}

const handleConfirm = async (id) => {
    try{
    await axios.post('/user_authorization_update/'+id,{}, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
  }
  catch(err){
    console.log(err);
  }
}

const handleRemove = async (id) => {
    try{
    await axios.delete('/user_info_delete/'+id,{}, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
    }
    catch(err){
        console.log(err)
    }
    employees = employees.filter((employee) => employee.id !== id)
    // Update the tempEmployeesArray to match the current page's data
    updateTempEmployeesArray();
}

const handleEdit = async (modifiedContents) => {
    try{
    await axios.post('/user_info_update/'+modifiedContents.id,{
      userName: modifiedContents.userName,
      userEmail: modifiedContents.userEmail
    }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        });
    // Update the employees array and tempEmployeesArray
        employees = employees.map(employee => {
        if (employee.id === modifiedContents.id) {
            return {
            ...employee,
            name: modifiedContents.userName,
            email: modifiedContents.userEmail
            };
        }
        return employee;
        });
        // tempEmployeesArray.map(tempItem => {
        //     if (tempItem.id === modifiedContents.id) {
        //     return {
        //         ...tempItem,
        //         name: modifiedContents.userName,
        //         email: modifiedContents.userEmail
        //     };
        //     }
        //     return tempItem;
        // })

        updateTempEmployeesArray();
    }
    catch(err){
        console.log(err)
    }
}

function refreshPage(){
    location.reload();
}
// Define the updateTempManagerVacationArray function
const updateTempEmployeesArray = () => {
    tempEmployeesArray.value = []; // Access .value to modify the ref

    const startIndex = (currentPage.value - 1) * itemNumbers;
    const endIndex = Math.min(startIndex + itemNumbers, employees.length);

    for (let i = startIndex; i < endIndex; i++) {
        tempEmployeesArray.value.push(employees[i]);
    }
}

// Initialize the tempManagerVacationArray based on the first page
updateTempEmployeesArray();

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--; // Decrease currentPage by 1
        updateTempEmployeesArray();
    }
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++; // Increase currentPage by 1
        updateTempEmployeesArray();
    }
}

const gotoPage = (page) => {
    currentPage.value = page; // Update currentPage when a page is clicked
    updateTempEmployeesArray();
}
</script>

<template>
    <Head title="User Info"/>

    <AuthenticatedLayout @handleMgrNotiTest="handleMNoti()">
        <!-- REQUEST LIST PART -->
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <input type="hidden" id="csrf-token" value="{{ csrf_token() }}" />
            <h1 v-if="!isManager($page.props.auth.user.email)" style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <h1 v-else style="margin-top: 2rem;" class="text-3xl text-center font-bold tracking-tight text-gray-600">{{requestListTitle}}</h1>
            <div id="listDom" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <UserManager v-for="employee in tempEmployeesArray" :key="employee.id" :employee="employee" :managerEmails="managerEmails" @handleRemove="handleRemove" @handleEdit="handleEdit" @handleConfirm="handleConfirm"/>
            </div>
            <nav aria-label="Vacation Pagination" class="pagination" v-if="!employees.length == 0">
                <ul>
                    <li class="pagination-item" @click="previousPage" :class="{ disabled: currentPage === 1 }">
                        <button :disabled="currentPage === 1" style="font-size: 10px;">prev</button>
                    </li>
                    <li v-for="page in totalPages" :key="page" >
                        <button @click="gotoPage(page)" :class="{ active: page === currentPage }">{{ page }}</button>
                    </li>
                    <li class="pagination-item" @click="nextPage" :class="{ disabled: currentPage === totalPages }">
                        <button :disabled="currentPage === totalPages" style="font-size: 10px;">next</button>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- END -->
    </AuthenticatedLayout>
</template>


<style scoped>
.active {
  background-color: #007bff !important; /* Example background color for the active button */
  color: #fff !important; /* Example text color for the active button */
  font-weight: bold; /* Example font weight for the active button */
  /* Add any other styles you want for the active class */
}
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 1rem;
  }
  
  .pagination ul {
    list-style-type: none;
    display: flex;
    padding: 0;
  }
  
  .pagination li {
    margin: 0 0.5rem;
  }
  
  .pagination-item {
    cursor: pointer;
  }
  
  .pagination-item.disabled button {
    pointer-events: none;
    opacity: 0.5;
  }
  
  .pagination-item.active button {
    background-color: #3498db;
    color: #fff;
    font-weight: bold;
    border: 1px solid #3498db;
    border-radius: 50%;
  }
  
  .pagination button {
    background-color: #fff;
    color: #3498db;
    border: 1px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .pagination button i {
    font-size: 20px;
  }
  
  .pagination button:disabled {
    background-color: #f2f2f2;
    color: #d9d9d9;
    border: 1px solid #d9d9d9;
    cursor: not-allowed;
  }
  </style>

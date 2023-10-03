<script setup>
import axios from 'axios';
import { ref, defineEmits } from 'vue'
import { Switch } from '@headlessui/vue'

const enabled = ref(false)
const isModalVisible = ref(false);
const statusFlag = ref();
const {vacation, managerEmails, employee} = defineProps(['vacation', 'managerEmails', 'employee']);
// const emit = defineEmits();

// async function onDeleteClick( id, status ){
//     var user_id = document.getElementById('userId').textContent;
//     var manager_name = document.getElementById('managerName').textContent;

//     try {
//         await axios.delete('/vacations/'+id+":"+status+":"+user_id+":"+manager_name);
//         // Trigger a page refresh
//         emit('handleTest');
//     } catch (error) {
//         console.error('Error deleting vacation:', error);
//     }
// }
const test = () => {
    console.log(employee)
}
</script>

<template>
    <ul class="user-list">
        <li class="user-item">
            <div class="user-avatar">
                <!-- Display User Avatar Here -->
                <img :src="employee.avatar || 'https://via.placeholder.com/60'" alt="User Avatar" class="avatar-img" />
            </div>
            <div class="user-info">
                <h3 class="user-name">{{ employee.name }}</h3>
                <p class="user-email">{{ employee.email }}</p>
            </div>
                <div class="user-actions">
                    <Switch
                        v-model="enabled"
                        :class="enabled ? 'bg-teal-900' : 'bg-teal-500'"
                        class="relative inline-flex h-[28px] w-[62px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                        >
                        <span class="sr-only">Use setting</span>
                        <span
                            aria-hidden="true"
                            :class="enabled ? 'translate-x-9' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-[24px] w-[24px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                        />
                    </Switch>
                    <button @click="editUser(employee)" class="edit-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17l-1 4 4-1 9-9-3-3-9 9z"/>
                        </svg>
                    </button>
                    <button @click="deleteUser(employee)" class="delete-button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff">
                            <path d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                        </svg>
                    </button>
            </div>
        </li>
    </ul>  
</template>
<style scoped>
.user-list {
  list-style: none;
  padding: 0;
}

.user-item {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 5px;
  background-color: #f9f9f9;
}

.user-avatar {
  flex: 0 0 60px;
  margin-right: 10px;
}

.avatar-img {
  width: 100%;
  max-width: 60px;
  height: auto;
  border-radius: 50%;
}

.user-info {
  flex-grow: 1;
}

.user-name {
  font-size: 1.2rem;
  margin: 0;
  font-weight: bold;
}

.user-email {
  margin: 0;
  color: #777;
}

.user-actions {
  flex: 0 0 auto;
  display: flex;
  gap:5px;
  align-items: center;
}

.edit-button{    
  padding: 5px 10px;
  margin-top: 5px;
  cursor: pointer;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;

}
.delete-button {
  padding: 5px 10px;
  margin-top: 5px;
  cursor: pointer;
  background-color: red;
  color: #fff;
  border: none;
  border-radius: 5px;
}

.edit-button:hover,
.delete-button:hover {
  background-color: #0056b3;
}
</style>
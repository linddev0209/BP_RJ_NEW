<script setup>
import axios from 'axios';
import { ref } from 'vue'
import { Switch,
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogPanel,
        DialogTitle, } from '@headlessui/vue'

const enabled = ref(false)
const showManagerDialog = ref(false);
const showManagerEditDialog = ref(false);
const showManagerDeleteDialog = ref(false);

const {vacation, managerEmails, employee, userId} = defineProps(['vacation', 'managerEmails', 'employee', 'userId']);
const userNameModify = ref(employee.name);
const userEmailModify = ref(employee.email);
const emit = defineEmits();

if(employee.authorization == 1){
  enabled.value = true;
}
const toggleManagerDialog = (value) => {
  enabled.value = value;
  if (value === true) {
    showManagerDialog.value = true;
  } else {
    showManagerDialog.value = false;
    // Reset the switch value when closing the dialog
    enabled.value = false;
  }
};

const confirmAuthorization = async () => {
  emit('handleConfirm', employee.id)
  showManagerDialog.value = false;
  enabled.value = true;
}
const cancelAuthorization = () => {
  showManagerDialog.value = false;
  enabled.value = false;
}
//<!--  Edit User      -->
const toggleManagerEditDialog = () => {
    showManagerEditDialog.value = true;
};

const confirmEditUser = async () => {
  let modifiedContents = {
    id: employee.id,
    userName: userNameModify.value,
    userEmail: userEmailModify.value
  }
  emit('handleEdit', modifiedContents)
  showManagerEditDialog.value = false;
}

const cancelEditUser = () => {
  showManagerEditDialog.value = false;
}

//-------Delete User---------
const toggleManagerDeleteDialog = () => {
    showManagerDeleteDialog.value = true;
};
let employeeId = employee.id
const confirmDeleteUser = async () => {
  await emit('handleRemove', employeeId);
  showManagerDeleteDialog.value = false;
}

const cancelDeleteUser = () => {
  showManagerDeleteDialog.value = false;
}

const test = () => {
    console.log(employee)
}
</script>

<template>
    <TransitionRoot appear :show="showManagerDialog" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
            <div
            class="flex min-h-full items-center justify-center p-4 text-center"
            >
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
            >
                <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                >
                <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900"
                >
                    Edit User Authorization!
                </DialogTitle>
                <div class="p-4">
                    Are you sure?
                    <div class="mt-4 flex justify-end">
                        <button
                        class="px-4 py-2 mr-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-blue-300"
                        @click="confirmAuthorization"
                    >
                        Confirm
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
                        @click="cancelAuthorization"
                    >
                        Close
                    </button>
                    </div>
                </div>
                </DialogPanel>
            </TransitionChild>
            </div>
        </div>
        </Dialog>
    </TransitionRoot>
    <TransitionRoot appear :show="showManagerEditDialog" as="template">
        <Dialog as="div" @close="closeEditModal" class="relative z-10">
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
            <div
            class="flex min-h-full items-center justify-center p-4 text-center"
            >
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
            >
                <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                >
                <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900"
                >
                    User Informaion
                </DialogTitle>
                <div class="p-4">
                    Name:
                    <input
                      type="text"
                      v-model="userNameModify"
                      class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                    />
                    email:
                    <input
                      type="text"
                      v-model="userEmailModify"
                      class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                    />
                    <div class="mt-4 flex justify-end">
                        <button
                        class="px-4 py-2 mr-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-blue-300"
                        @click="confirmEditUser"
                    >
                        Confirm
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
                        @click="cancelEditUser"
                    >
                        Close
                    </button>
                    </div>
                </div>
                </DialogPanel>
            </TransitionChild>
            </div>
        </div>
        </Dialog>
    </TransitionRoot>
    <TransitionRoot appear :show="showManagerDeleteDialog" as="template">
        <Dialog as="div" @close="closeDeleteModal" class="relative z-10">
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
            <div
            class="flex min-h-full items-center justify-center p-4 text-center"
            >
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0 scale-95"
                enter-to="opacity-100 scale-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100 scale-100"
                leave-to="opacity-0 scale-95"
            >
                <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                >
                <DialogTitle
                    as="h3"
                    class="text-lg font-medium leading-6 text-gray-900"
                >
                    Are you sure to remove this user?
                </DialogTitle>
                <div class="p-4">
                    <div class="mt-4 flex justify-end">
                        <button
                        class="px-4 py-2 mr-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-blue-300"
                        @click="confirmDeleteUser"
                    >
                        Confirm
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
                        @click="cancelDeleteUser"
                    >
                        Close
                    </button>
                    </div>
                </div>
                </DialogPanel>
            </TransitionChild>
            </div>
        </div>
        </Dialog>
    </TransitionRoot>
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
                        @update:model-value="toggleManagerDialog"
                        :disabled="enabled"
                        >
                        <span class="sr-only">Use setting</span>
                        <span
                            aria-hidden="true"
                            :class="enabled ? 'translate-x-9' : 'translate-x-0'"
                            class="pointer-events-none inline-block h-[24px] w-[24px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                        />
                    </Switch>
                    <button @click="toggleManagerEditDialog" class="edit-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 17l-1 4 4-1 9-9-3-3-9 9z"/>
                        </svg>
                    </button>
                    <button @click="toggleManagerDeleteDialog" class="delete-button">
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
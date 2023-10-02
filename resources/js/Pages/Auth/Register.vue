<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="name" value="Name" />

        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />

        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div class="mt-4">
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
        />

        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />

        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />

        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />

        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>
      <div class="py-2" style="display: flex; align-items: center; gap: 2px;">
        <span>User</span>
        <Switch
          v-model="enabled"
          :class="enabled ? 'bg-teal-900' : 'bg-teal-700'"
          class="relative inline-flex h-[28px] w-[64px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
          @update:model-value="toggleManagerDialog"
        >
          <span
            aria-hidden="true"
            :class="enabled ? 'translate-x-9' : 'translate-x-0'"
            class="pointer-events-none inline-block h-[24px] w-[24px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
          />
        </Switch>
        <span>Manager</span>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          :href="route('login')"
          class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          Already registered?
        </Link>

        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Register
        </PrimaryButton>
      </div>
    </form>
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
                    Manager Password Confirmation
                </DialogTitle>
                <div class="p-4">
                    <input
                    type="password"
                    v-model="managerPassword"
                    placeholder="Enter Manager Password"
                    class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                    />
                    <div class="mt-4 flex justify-end">
                        <button
                        @click="confirmManagerPasswordDialog"
                        class="px-4 py-2 mr-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-blue-300"
                    >
                        Confirm
                    </button>
                    <button
                        @click="closeManagerDialog"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300"
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

    <div v-if="showManagerPasswordError" class="toast error-toast">
      Manager password is incorrect.
    </div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Switch,
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogPanel,
        DialogTitle, } from '@headlessui/vue'
import { ref } from 'vue'

const enabled = ref(false)
const showManagerDialog = ref(false);
const managerPassword = ref('');
const showManagerPasswordError = ref(false);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  user_type: '',
  authorization: false
});

const submit = () => {
  const user_type = enabled.value ? 'manager' : 'employee';
  form.authorization = user_type == 'manager' ?  true : false;
  form.user_type = user_type;

  form.post(route('register'), {
    onFinish: () => {
      form.reset('password', 'password_confirmation');
    }
  });
};

const toggleManagerDialog = (value) => {
    console.log(value)
  enabled.value = value;
  if (value === true) {
    showManagerDialog.value = true;
  } else {
    showManagerDialog.value = false;
    // Reset the switch value when closing the dialog
    enabled.value = false;
  }
};

const closeManagerDialog = () => {
  showManagerDialog.value = false;
  enabled.value = false;
  showManagerPasswordError.value=false;
};

const confirmManagerPasswordDialog = () => {
    if ( managerPassword.value !== 'Manager123') {
        showManagerPasswordError.value = true;
        return;
    }else{
        showManagerPasswordError.value = false;
        showManagerDialog.value = false;
        enabled.value = true;
    }

}

</script>

<style scoped>
.error-toast {
  background-color: #ff0000; /* Red background */
  color: #ffffff; /* White text color */
  padding: 10px;
  border-radius: 4px;
  text-align: center;
  font-size: 16px;
  /* Add any other styles you want for the error toast here */
}
</style>
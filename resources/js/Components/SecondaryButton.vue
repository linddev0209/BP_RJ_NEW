<template>
    <button
      :class="{
        'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest focus:outline-none transition ease-in-out duration-150': true,
        'hover:text-gray-900': !active,
        'text-gray-900 bg-gray-200': active,
      }"
    >
        <slot />
    </button>
  </template>
  
  <script setup>
        const props = defineProps ({
            active: {
                type: Boolean,
                default: false,
            }
        });
  </script>
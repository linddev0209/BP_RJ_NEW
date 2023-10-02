<script>
export default {
  props: {
    title: String,
    createDate: String,
    type: String,
  },
  computed: {
    formattedDate() {
      // You can format the date here as needed
      return this.createDate;
    },
  },
  methods: {
    handleMouseOver() {
      // Change the background color when the mouse is over the component
      this.$refs.notification.style.backgroundColor = '#f0f0f0';
    },
    handleMouseOut() {
      // Reset the background color when the mouse leaves the component
      this.$refs.notification.style.backgroundColor = '#ffffff'; // or any other default color
    },
  },
};
</script>

<template>
    <div class="notification">
      <div class="notification-icon">
        <svg v-if="type=='Accepted'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#34a853">
            <path d="M0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
            <path d="M16.59 7.58L10 14.17l-3.59-3.58L5 12l5 5 8-8zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
        </svg>
        <svg v-if="type=='Rejected'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ea4335">
            <path d="M0 0h24v24H0z" fill="none"/>
            <path d="M14.59 8L12 10.59 9.41 8 8 9.41 10.59 12 8 14.59 9.41 16 12 13.41 14.59 16 16 14.59 13.41 12 16 9.41 14.59 8zM12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
        </svg>
        <svg v-if="type=='Require Action'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#4285f4">
            <path d="M0 0h24v24H0z" fill="none"/>
            <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
        </svg>
        <svg v-if="type=='Pending'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#4285f4">
            <path d="M0 0h24v24H0z" fill="none"/>
            <path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.58l2.2-2.21c.28-.27.36-.66.25-1.01C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM12 3v10l3-3h6V3h-9z"/>
        </svg>
      </div>
      <div class="notification-content">
        <h3>{{ title }}</h3>
        <small class="text-sm text-gray-600">{{ formattedDate }}</small>
      </div>
    </div>
  </template>

<style scoped>
.notification {
  display: flex;
  align-items: center;
  background-color: #ffffff;
  padding: 3px;
  width: 100%;
  border: 1px solid #ffffff;
  border-radius: 5px;
  margin-bottom: 10px;
}
.notification:hover {
  background-color: #f0f0f0; /* Change the background color on hover */
}

.notification-icon {
  margin-right: 10px;
}

.notification h3 {
  font-size: 0.8rem;
  margin: 0;
}

.notification p {
  color: #666;
  margin: 0;
}
</style>
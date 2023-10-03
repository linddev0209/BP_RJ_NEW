<template>
    <nav aria-label="Vacation Pagination" class="pagination">
      <ul>
        <li class="pagination-item" @click="previousPage" :class="{ disabled: currentPage === 1 }">
          <button :disabled="currentPage === 1" style="font-size: 10px;">
            prev
          </button>
        </li>
        <li v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
          <button @click="gotoPage(page)">{{ page }}</button>
        </li>
        <li class="pagination-item" @click="nextPage" :class="{ disabled: currentPage === totalPages }">
          <button :disabled="currentPage === totalPages" style="font-size: 10px;">
            next
          </button>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: Number,
      totalPages: Number,
    },
    methods: {
      previousPage() {
        if (this.currentPage > 1) {
          this.$emit('page-change', this.currentPage - 1);
        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.$emit('page-change', this.currentPage + 1);
        }
      },
      gotoPage(page) {
        this.$emit('page-change', page);
      },
    },
  };
  </script>
  
  <style scoped>
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
  
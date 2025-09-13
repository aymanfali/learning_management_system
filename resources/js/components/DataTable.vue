<script>
import PrimaryBtn from './PrimaryBtn.vue';

export default {
    components: {
        PrimaryBtn
    },
    props: {
        headers: {
            type: Array,
            required: true,
            // Example:
            // [
            //   { label: "Name", key: "name", type: "text" },
            //   { label: "Email", key: "email", type: "text" },
            //   { label: "Profile Picture", key: "avatar", type: "image" },
            //   { label: "Created At", key: "created_at", type: "date" }
            // ]
        },
        items: {
            type: Array,
            required: true
        },
        showActions: {
            type: Boolean,
            default: true
        },
        allowEdit: {
            type: Boolean,
            default: true
        },
        itemsPerPage: {
            type: Number,
            default: 5
        },
        filterableColumns: {
            type: Array,
            default: () => []
            // Example:
            // [{ key: "name", label: "Name", type: "text" }]
        }
    },
    data() {
        return {
            currentPage: 1,
            filters: {}
        }
    },
    created() {
        // Initialize filters
        this.filterableColumns.forEach(col => {
            this.filters[col.key] = '';
        });
    },
    computed: {
        filterItems() {
            return this.items.filter(item => {
                return this.filterableColumns.every(filterConfig => {
                    const filterValue = this.filters[filterConfig.key];
                    if (!filterValue) return true;

                    const itemValue = item[filterConfig.key];

                    if (filterConfig.type === 'date') {
                        try {
                            const filteredItem = new Date(itemValue).setHours(0, 0, 0, 0);
                            const dateFilter = new Date(filterValue).setHours(0, 0, 0, 0);
                            return filteredItem === dateFilter;
                        } catch {
                            return false;
                        }
                    } else {
                        return itemValue &&
                            itemValue.toString().toLowerCase().includes(filterValue.toLowerCase());
                    }
                });
            });
        },
        totalPages() {
            return Math.ceil(this.filterItems.length / this.itemsPerPage);
        },
        paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filterItems.slice(start, end);
        },
    },
    watch: {
        filterItems() {
            if (this.currentPage > this.totalPages) {
                this.currentPage = this.totalPages || 1;
            }
        }
    },
    methods: {
        handleEdit(item) {
            this.$emit('edit', item);
        },
        handleDelete(item) {
            this.$emit('delete', item);
        },
        handleView(item) {
            this.$emit('view', item);
        },
        formatDate(value) {
            if (!value) return '';
            try {
                return new Date(value).toLocaleDateString();
            } catch {
                return value;
            }
        },
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        handleFilter() {
            this.currentPage = 1;
        },
        clearFilters() {
            Object.keys(this.filters).forEach(key => {
                this.filters[key] = '';
            });
            this.currentPage = 1;
        }
    }
}
</script>


<template>
    <div class="rounded-lg shadow overflow-auto">
        <!-- Filters -->
        <form @submit.prevent="handleFilter" class="md:flex md:flex-wrap p-3">
            <div v-for="filterConfig in filterableColumns" :key="filterConfig.key" class="m-3">
                <label v-if="filterConfig.label" class="block text-sm font-medium mb-1">
                    {{ filterConfig.label }}
                </label>
                <input v-if="filterConfig.type !== 'date'" type="text" v-model="filters[filterConfig.key]"
                    :placeholder="`Filter by ${filterConfig.label || filterConfig.key}`"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <input v-else type="date" v-model="filters[filterConfig.key]"
                    :placeholder="`Filter by ${filterConfig.label || filterConfig.key}`"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            </div>
            <div class="m-1 flex items-end">
                <PrimaryBtn type="button" name="Clear" @click="clearFilters" class="ml-2" />
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/20">
                <thead>
                    <tr>
                        <th v-for="col in headers" :key="col.key"
                            class="px-6 py-3 text-center text-md font-bold uppercase tracking-wider">
                            {{ col.label }}
                        </th>
                        <th v-if="showActions" class="px-6 py-3 text-center text-md font-bold uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary/20">
                    <tr v-for="(item, index) in paginatedItems" :key="`row-${index}`">
                        <td v-for="col in headers" :key="col.key" class="px-6 py-4 text-sm text-center">
                            <template v-if="col.type === 'image' && item[col.key]">
                                <img :src="`/storage/${item[col.key]}`"
                                    class="h-8 w-8 rounded-full object-cover mx-auto" :alt="`${col.label} image`">
                            </template>
                            <template v-else-if="col.type === 'date'">
                                {{ formatDate(item[col.key]) }}
                            </template>
                            <template v-else>
                                {{ item[col.key] }}
                            </template>
                        </td>

                        <!-- Actions -->
                        <td v-if="showActions" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button @click="handleView(item)" class="mr-3 cursor-pointer" title="View details">
                                <span class="material-symbols-rounded"> visibility </span>
                            </button>
                            <button v-if="allowEdit" @click="handleEdit(item)" class="mr-3 cursor-pointer" title="Edit">
                                <span class="material-symbols-rounded"> edit </span>
                            </button>
                            <button @click="handleDelete(item)" class="cursor-pointer text-red-600 hover:text-red-900"
                                title="Delete">
                                <span class="material-symbols-rounded"> delete </span>
                            </button>
                        </td>
                    </tr>

                    <!-- Empty state -->
                    <tr v-if="filterItems.length === 0">
                        <td :colspan="headers.length + (showActions ? 1 : 0)" class="px-6 py-4 text-center">
                            No items found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-bg px-4 py-3 flex items-center justify-between border-t border-primary/20 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="prevPage" :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md"
                    :class="currentPage === 1 ? 'bg-gray-100 text-gray-400' : 'bg-white hover:bg-gray-50'">
                    Previous
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md"
                    :class="currentPage === totalPages ? 'bg-gray-100 text-gray-400' : 'bg-white hover:bg-gray-50'">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm">
                        Showing <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                        to <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filterItems.length)
                        }}</span>
                        of <span class="font-medium">{{ filterItems.length }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium"
                            :class="currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'">
                            <span class="sr-only">Previous</span>
                            ‹
                        </button>

                        <template v-for="page in totalPages" :key="page">
                            <button @click="goToPage(page)" :aria-current="page === currentPage ? 'page' : undefined"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium" :class="page === currentPage
                                    ? 'z-10 bg-primary border-primary text-white'
                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'">
                                {{ page }}
                            </button>
                        </template>

                        <button @click="nextPage" :disabled="currentPage === totalPages"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium"
                            :class="currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50'">
                            <span class="sr-only">Next</span>
                            ›
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

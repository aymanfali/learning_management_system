<template>
    <ConfirmModal
        :show="showConfirm"
        title="Delete User"
        message="Are you sure you want to delete this user? This action cannot be undone."
        confirmText="Delete"
        cancelText="Cancel"
        @confirm="confirmDelete"
        @cancel="showConfirm = false"
    />
    <DataTable :headers="[
        { label: 'Name', key: 'name', type: 'text' },
        { label: 'Email', key: 'email', type: 'text' },
        { label: 'Role', key: 'role', type: 'text' },
        { label: 'Profile Picture', key: 'image', type: 'image' },
        { label: 'Created At', key: 'created_at', type: 'date' },
        { label: 'Updated At', key: 'updated_at', type: 'date' }
    ]" :items="users" :allow-edit="false" :filterableColumns="[
        { key: 'name', label: 'Name', type: 'text' },
        { label: 'Email', key: 'email', type: 'text' },
        { label: 'Role', key: 'role', type: 'text' },
        { key: 'created_at', label: 'Created Date', type: 'date' },
        { label: 'Updated At', key: 'updated_at', type: 'date' }
    ]" @view="viewUser" @delete="deleteUser" />

</template>
<script>

import axios from 'axios';
import DataTable from './DataTable.vue';
import ConfirmModal from './ConfirmModal.vue';


export default {
    components: {
        DataTable,
        ConfirmModal
    },
    data() {
        return {
            users: [],
            showConfirm: false,
            userToDelete: null,
        }
    },
    methods: {
        async fetchUsers() {
            try {
                const { data } = await axios.get('/api/v1/users');
                this.users = data;
            } catch (error) {
                console.error("Failed to fetch users:", error);
                this.$toast?.error("Unable to load users."); // optional if using a toast plugin
            }
        },
        viewUser(user) {
            window.location.href = `/users/${user.id}`;
        },
        deleteUser(user) {
            this.userToDelete = user;
            this.showConfirm = true;
        },
        async confirmDelete() {
            if (!this.userToDelete) return;
            try {
                await axios.delete(`/api/v1/users/${this.userToDelete.id}`);
                this.$toast?.success('User deleted successfully');
                this.fetchUsers();
            } catch (error) {
                this.$toast?.error('Failed to delete user');
            }
            this.showConfirm = false;
            this.userToDelete = null;
        }
    },

    mounted() {
        this.fetchUsers()
    }
}
</script>

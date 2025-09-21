<template>
    <ConfirmModal :show="showConfirm" title="Delete User"
        message="Are you sure you want to delete this user? This action cannot be undone." confirmText="Delete"
        cancelText="Cancel" @confirm="confirmDelete" @cancel="showConfirm = false" />
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
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import DataTable from "./DataTable.vue";
import ConfirmModal from "./ConfirmModal.vue";
import { useToast } from "vue-toastification";

const users = ref([]);
const showConfirm = ref(false);
const userToDelete = ref(null);
const loading = ref(false);

const toast = useToast();

const fetchUsers = async () => {
    loading.value = true;
    try {
        const { data } = await axios.get("/api/v1/users");
        users.value = data;
    } catch (error) {
        console.error("Failed to fetch users:", error);
        toast.error("Unable to load users.");
    } finally {
        loading.value = false;
    }
};

const viewUser = (user) => {
    window.location.href = `users/${user.id}`;
};

const deleteUser = (user) => {
    userToDelete.value = user;
    showConfirm.value = true;
};

const confirmDelete = async () => {
    if (!userToDelete.value) return;
    try {
        await axios.delete(`/api/v1/users/${userToDelete.value.id}`);
        // Optimistic UI update
        users.value = users.value.filter((u) => u.id !== userToDelete.value.id);
        toast.success("User deleted successfully");
    } catch (error) {
        toast.error("Failed to delete user");
    }
    showConfirm.value = false;
    userToDelete.value = null;
};

onMounted(fetchUsers);
</script>

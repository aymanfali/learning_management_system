<template>
    <ConfirmModal :show="showConfirm" title="Delete course"
        message="Are you sure you want to delete this course? This action cannot be undone." confirmText="Delete"
        cancelText="Cancel" @confirm="confirmDelete" @cancel="showConfirm = false" />
    <DataTable :headers="[
        { label: 'Course Name', key: 'name', type: 'text' },
        { label: 'Instructor', key: 'instructor_name', type: 'text' },
        { label: 'Image', key: 'image', type: 'image' },
        { label: 'Created At', key: 'created_at', type: 'date' },
        { label: 'Updated At', key: 'updated_at', type: 'date' }
    ]" :items="courses" :filterableColumns="[
        { key: 'name', label: 'Name', type: 'text' },
        { key: 'instructor_name', label: 'Instructor', type: 'text' },
        { key: 'created_at', label: 'Created Date', type: 'date' },
        { key: 'updated_at', label: 'Updated Date', type: 'date' }
    ]" @view="viewCourse" @edit="editCourse" @delete="deleteCourse" />

</template>
<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import DataTable from "./DataTable.vue";
import ConfirmModal from "./ConfirmModal.vue";
import { useToast } from "vue-toastification";

const courses = ref([]);
const showConfirm = ref(false);
const courseToDelete = ref(null);
const loading = ref(false);

const toast = useToast();

const fetchCourses = async () => {
    loading.value = true;
    try {
        const { data } = await axios.get("/api/v1/courses");
        // Assuming API returns { data: [...] }
        courses.value = data.data;
    } catch (error) {
        console.error("Failed to fetch courses:", error);
        toast.error("Unable to load courses.");
    } finally {
        loading.value = false;
    }
};

const viewCourse = (course) => {
    window.location.href = `courses/${course.id}`;
};

const editCourse = (course) => {
    window.location.href = `courses/edit/${course.id}`;
};

const deleteCourse = (course) => {
    courseToDelete.value = course;
    showConfirm.value = true;
};

const confirmDelete = async () => {
    if (!courseToDelete.value) return;
    try {
        await axios.delete(`/api/v1/courses/${courseToDelete.value.id}`);
        courses.value = courses.value.filter(
            (c) => c.id !== courseToDelete.value.id
        );
        toast.success("Course deleted successfully");
    } catch (error) {
        toast.error("Failed to delete course");
    }
    showConfirm.value = false;
    courseToDelete.value = null;
};

onMounted(fetchCourses);
</script>

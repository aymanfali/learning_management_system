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
            courses: [],
            showConfirm: false,
            courseToDelete: null,
        }
    },
    methods: {
        async fetchCourses() {
            try {
                const { data } = await axios.get('/api/v1/courses');
                this.courses = data.data;

            } catch (error) {
                console.error("Failed to fetch courses:", error);
                this.$toast?.error("Unable to load courses."); 
            }
        },
        viewCourse(course) {
            window.location.href = `/courses/${course.id}`;
        },
        editCourse(course) {
            window.location.href = `/courses/edit/${course.id}`;
        },
        deleteCourse(course) {
            this.courseToDelete = course;
            this.showConfirm = true;
        },
        async confirmDelete() {
            if (!this.courseToDelete) return;
            try {
                await axios.delete(`/api/v1/courses/${this.courseToDelete.id}`);
                this.$toast?.success('course deleted successfully');
                this.fetchCourses();
            } catch (error) {
                this.$toast?.error('Failed to delete course');
            }
            this.showConfirm = false;
            this.courseToDelete = null;
        }
    },

    mounted() {
        this.fetchCourses()
    }
}
</script>

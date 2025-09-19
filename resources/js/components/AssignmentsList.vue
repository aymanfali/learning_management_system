<template>
    <DataTable :headers="[
        { label: 'Student', key: 'student_name', type: 'text' },
        { label: 'Course', key: 'course_name', type: 'text' },
        { label: 'Lesson', key: 'lesson_title', type: 'text' },
        { label: 'Assignment File', key: 'assignment_file_status', type: 'badge' },
        { label: 'Grade', key: 'grade', type: 'number' },
        { label: 'Feedback', key: 'feedback', type: 'text' },
        { label: 'Submitted At', key: 'created_at', type: 'date' }
    ]" :items="assignments" :filterableColumns="[
        { key: 'student_name', label: 'Student', type: 'text' },
        { key: 'lesson_title', label: 'Lesson', type: 'text' },
        { key: 'grade', label: 'Grade', type: 'number' },
        { key: 'created_at', label: 'Submitted Date', type: 'date' }
    ]" @edit="editAssignment" :allowEdit="true" :allowView="false" :allowDelete="false" />

    <div v-if="loading" class="text-center py-4">Loading assignments...</div>
</template>

<script>
import axios from "axios";
import DataTable from "./DataTable.vue";

export default {
    components: {
        DataTable,
    },
    data() {
        return {
            assignments: [],
            loading: false,
        };
    },
    methods: {
        async fetchAssignments() {
            this.loading = true;
            try {
                const { data } = await axios.get("/api/v1/assignments");
                this.assignments = data.data.map(a => ({
                    ...a,
                    student_name: a.student?.name || "N/A",
                    lesson_title: a.lesson?.title || "N/A",
                    course_name: a.course?.name || "N/A",
                    assignment_file_status: a.assignment_file ? "Yes" : "No",
                }));
            } catch (error) {
                this.$toast?.error("Unable to load assignments.");
            } finally {
                this.loading = false;
            }
        },

        editAssignment(assignment) {
            window.location.href = `assignments/edit/${assignment.id}`;
        },

    },
    mounted() {
        this.fetchAssignments();
    },
};
</script>

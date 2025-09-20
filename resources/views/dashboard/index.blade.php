<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="py-8 max-w-7xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                            <!-- Monthly Registrations -->
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">
                                <h3 class="text-lg font-semibold mb-4">Monthly Registrations (Students vs Instructors)
                                </h3>
                                <canvas id="monthlyStatsChart"></canvas>
                            </div>

                            <!-- Daily Registrations -->
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">
                                <h3 class="text-lg font-semibold mb-4">Daily Registrations (Last 7 Days)</h3>
                                <canvas id="dailyStatsChart"></canvas>
                            </div>

                            <!-- Weekly Courses -->
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">
                                <h3 class="text-lg font-semibold mb-4">Weekly New Courses</h3>
                                <canvas id="weeklyCoursesChart"></canvas>
                            </div>

                            <!-- Course Enrollments -->
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">
                                <h3 class="text-lg font-semibold mb-4">Top 5 Courses by Enrollments</h3>
                                <canvas id="courseEnrollmentsChart"></canvas>
                            </div>

                        </div>
                    </div>

                    <script>
                        const monthlyStats = @json($monthlyStats);
                        const months = Object.keys(monthlyStats);

                        const studentData = [];
                        const instructorData = [];

                        months.forEach(month => {
                            const stats = monthlyStats[month];
                            const student = stats.find(s => s.role === 'student');
                            const instructor = stats.find(s => s.role === 'instructor');
                            studentData.push(student ? student.total : 0);
                            instructorData.push(instructor ? instructor.total : 0);
                        });

                        new Chart(document.getElementById('monthlyStatsChart'), {
                            type: 'line',
                            data: {
                                labels: months.map(m => 'Month ' + m),
                                datasets: [{
                                        label: 'Students',
                                        data: studentData,
                                        borderColor: 'rgb(54, 162, 235)',
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        tension: 0.3,
                                        fill: true
                                    },
                                    {
                                        label: 'Instructors',
                                        data: instructorData,
                                        borderColor: 'rgb(255, 99, 132)',
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        tension: 0.3,
                                        fill: true
                                    }
                                ]
                            }
                        });


                        // Daily Registrations
                        const dailyStats = @json($dailyStats);
                        const dailyLabels = Object.keys(dailyStats);

                        const dailyStudentData = [];
                        const dailyInstructorData = [];

                        dailyLabels.forEach(date => {
                            const stats = dailyStats[date];
                            const student = stats.find(s => s.role === 'student');
                            const instructor = stats.find(s => s.role === 'instructor');
                            dailyStudentData.push(student ? student.total : 0);
                            dailyInstructorData.push(instructor ? instructor.total : 0);
                        });

                        new Chart(document.getElementById('dailyStatsChart'), {
                            type: 'bar',
                            data: {
                                labels: dailyLabels,
                                datasets: [{
                                        label: 'Students',
                                        data: dailyStudentData,
                                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                                    },
                                    {
                                        label: 'Instructors',
                                        data: dailyInstructorData,
                                        backgroundColor: 'rgba(255, 99, 132, 0.6)'
                                    }
                                ]
                            }
                        });

                        // Weekly Courses
                        const weeklyCourses = @json($weeklyCourses);
                        const weeklyLabels = Object.keys(weeklyCourses).map(w => 'Week ' + w);
                        const weeklyData = Object.values(weeklyCourses);

                        new Chart(document.getElementById('weeklyCoursesChart'), {
                            type: 'bar',
                            data: {
                                labels: weeklyLabels,
                                datasets: [{
                                    label: 'Courses',
                                    data: weeklyData,
                                    backgroundColor: 'rgba(153, 102, 255, 0.6)'
                                }]
                            }
                        });

                        const courseEnrollments = @json($courseEnrollments);
                        const courseLabels = courseEnrollments.map(c => c.course.name);
                        const courseData = courseEnrollments.map(c => c.total);

                        new Chart(document.getElementById('courseEnrollmentsChart'), {
                            type: 'bar',
                            data: {
                                labels: courseLabels,
                                datasets: [{
                                    label: 'Enrollments',
                                    data: courseData,
                                    backgroundColor: 'rgba(255, 159, 64, 0.6)'
                                }]
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@vite('resources/js/app.js')

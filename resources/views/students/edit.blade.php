<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php
    $courseRows = $courses->map(function ($course) {
        return [
            'id' => $course->id,
            'course_name' => $course->course_name,
            'department_id' => $course->department_id,
            'department_name' => optional($course->department)->name,
        ];
    });

    $departmentRows = $departments->map(function ($department) {
        return [
            'id' => $department->id,
            'name' => $department->name,
        ];
    });

    $studentData = [
        'student_id' => old('student_id', $student->student_id),
        'first_name' => old('first_name', $student->first_name),
        'last_name' => old('last_name', $student->last_name),
        'birthday' => old('birthday', optional($student->birthday)->format('Y-m-d') ?? $student->birthday),
        'gender' => old('gender', $student->gender),
        'email' => old('email', $student->email),
        'phone' => old('phone', $student->phone),
        'department_id' => old('department_id', $student->department_id ?? optional($student->enrolledCourse)->department_id),
        'course_id' => old('course_id', $student->course_id),
        'course' => old('course', $student->course),
        'year_level' => old('year_level', $student->year_level),
        'photoUrl' => $student->photo ? asset('storage/' . $student->photo) : '',
    ];

    $props = [
        'title' => 'Edit Student',
        'action' => route('students.update', $student),
        'method' => 'PUT',
        'submitLabel' => 'Update Student',
        'cancelUrl' => route('students.index'),
        'csrf' => csrf_token(),
        'student' => $studentData,
        'departments' => $departmentRows,
        'courses' => $courseRows,
        'errors' => $errors->all(),
    ];
@endphp

<div id="student-form-app" data-vue-component="StudentForm"></div>
<script id="student-form-app-props" type="application/json">
    @json($props)
</script>
</body>
</html>
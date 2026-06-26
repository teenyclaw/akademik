<?php

namespace Database\Seeders;

use App\Domain\Enums\AttendanceStatus;
use App\Domain\Enums\DayOfWeek;
use App\Domain\Enums\PaymentStatus;
use App\Domain\Enums\StudentStatus;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\Assessment;
use App\Models\AssessmentComponent;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\Curriculum;
use App\Models\EducationLevel;
use App\Models\FeeType;
use App\Models\FinalGrade;
use App\Models\Grade;
use App\Models\GradeLevel;
use App\Models\GradeScale;
use App\Models\Guardian;
use App\Models\LearningMaterial;
use App\Models\LessonHour;
use App\Models\Major;
use App\Models\PaymentBill;
use App\Models\Room;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolSetting;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $sdLevel = EducationLevel::where('code', 'SD')->first();
        $smaLevel = EducationLevel::where('code', 'SMA')->first();

        $sdSchool = $this->createSchool('SD Negeri 1 Jakarta', 'sd-negeri-1-jakarta', $sdLevel->id);
        $smaSchool = $this->createSchool('SMA Negeri 1 Jakarta', 'sma-negeri-1-jakarta', $smaLevel->id);

        $this->createSchoolData($sdSchool, 'SD', false);
        $this->createSchoolData($smaSchool, 'SMA', true);
    }

    private function createSchool(string $name, string $slug, int $levelId): School
    {
        return School::create([
            'name' => $name,
            'slug' => $slug,
            'address' => 'Jl. Pendidikan No. 1, Jakarta',
            'phone' => '021-1234567',
            'email' => Str::slug($name).'@sch.id',
            'website' => 'https://'.Str::slug($name).'.sch.id',
            'education_level_id' => $levelId,
            'report_header' => $name."\nJl. Pendidikan No. 1, Jakarta",
            'is_active' => true,
        ]);
    }

    private function createSchoolData(School $school, string $prefix, bool $hasMajor): void
    {
        $admin = User::create([
            'name' => "Admin {$school->name}",
            'email' => "admin@{$school->slug}.test",
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin-sekolah');

        $teacherUser = User::create([
            'name' => "Guru {$prefix} 1",
            'email' => "guru@{$school->slug}.test",
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $teacherUser->assignRole('guru');

        $studentUser = User::create([
            'name' => "Siswa {$prefix} 1",
            'email' => "siswa@{$school->slug}.test",
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $studentUser->assignRole('siswa');

        SchoolSetting::create(['school_id' => $school->id, 'key' => 'grade_format', 'value' => json_encode(['type' => 'numeric', 'max' => 100])]);
        SchoolSetting::create(['school_id' => $school->id, 'key' => 'nis_format', 'value' => json_encode(['prefix' => date('Y'), 'length' => 4])]);

        $year = AcademicYear::create([
            'school_id' => $school->id,
            'name' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
            'is_active' => true,
        ]);

        $semester = Semester::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'name' => 'Semester Ganjil',
            'semester_number' => 1,
            'start_date' => '2025-07-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
            'is_locked' => false,
        ]);

        Curriculum::create(['school_id' => $school->id, 'name' => 'Kurikulum Merdeka', 'description' => 'Kurikulum Merdeka', 'is_active' => true]);

        $gradeLevel = GradeLevel::create([
            'school_id' => $school->id,
            'name' => $hasMajor ? 'Kelas 10' : 'Kelas 1',
            'level_number' => $hasMajor ? 10 : 1,
        ]);

        $major = null;
        if ($hasMajor) {
            $major = Major::create(['school_id' => $school->id, 'name' => 'IPA', 'code' => 'IPA']);
        }

        $teacher = Teacher::create([
            'school_id' => $school->id,
            'user_id' => $teacherUser->id,
            'nip' => '198001012010011001',
            'name' => $teacherUser->name,
            'gender' => 'male',
            'status' => 'active',
            'joined_at' => '2010-01-01',
        ]);
        $teacherUser->update(['profile_type' => Teacher::class, 'profile_id' => $teacher->id]);

        $room = Room::create(['school_id' => $school->id, 'name' => 'Ruang 101', 'capacity' => 40]);
        $lessonHour = LessonHour::create(['school_id' => $school->id, 'name' => 'Jam ke-1', 'start_time' => '07:00', 'end_time' => '07:45', 'sort_order' => 1]);

        $schoolClass = SchoolClass::create([
            'school_id' => $school->id,
            'academic_year_id' => $year->id,
            'grade_level_id' => $gradeLevel->id,
            'major_id' => $major?->id,
            'name' => ($hasMajor ? 'X IPA 1' : '1 A'),
            'homeroom_teacher_id' => $teacher->id,
            'capacity' => 30,
        ]);

        $group = SubjectGroup::create(['school_id' => $school->id, 'name' => 'Kelompok A', 'description' => 'Muatan nasional']);
        $subject = Subject::create(['school_id' => $school->id, 'subject_group_id' => $group->id, 'code' => 'MTK', 'name' => 'Matematika']);

        TeacherSubject::create(['school_id' => $school->id, 'teacher_id' => $teacher->id, 'subject_id' => $subject->id, 'class_id' => $schoolClass->id]);

        ClassSchedule::create([
            'school_id' => $school->id,
            'class_id' => $schoolClass->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'room_id' => $room->id,
            'lesson_hour_id' => $lessonHour->id,
            'day' => DayOfWeek::Monday,
            'semester_id' => $semester->id,
        ]);

        $student = Student::create([
            'school_id' => $school->id,
            'class_id' => $schoolClass->id,
            'user_id' => $studentUser->id,
            'nis' => date('Y').'001',
            'nisn' => '0123456789',
            'name' => $studentUser->name,
            'gender' => 'male',
            'birth_place' => 'Jakarta',
            'birth_date' => '2010-01-15',
            'religion' => 'Islam',
            'status' => StudentStatus::Active,
            'enrolled_at' => '2025-07-01',
        ]);
        $studentUser->update(['profile_type' => Student::class, 'profile_id' => $student->id]);

        $guardianUser = User::create([
            'name' => "Orang Tua {$prefix}",
            'email' => "ortu@{$school->slug}.test",
            'password' => Hash::make('password'),
            'school_id' => $school->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $guardianUser->assignRole('orang-tua');

        $guardian = Guardian::create([
            'school_id' => $school->id,
            'user_id' => $guardianUser->id,
            'name' => $guardianUser->name,
            'gender' => 'male',
            'phone' => '081234567890',
            'email' => $guardianUser->email,
        ]);
        $guardianUser->update(['profile_type' => Guardian::class, 'profile_id' => $guardian->id]);
        $student->guardians()->attach($guardian->id, ['school_id' => $school->id, 'relationship' => 'ayah', 'is_primary' => true]);

        Attendance::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'class_id' => $schoolClass->id,
            'date' => now()->toDateString(),
            'status' => AttendanceStatus::Present,
            'recorded_by' => $teacherUser->id,
        ]);

        $component = AssessmentComponent::create(['school_id' => $school->id, 'name' => 'Ulangan Harian', 'code' => 'UH', 'weight' => 30, 'sort_order' => 1]);
        AssessmentComponent::create(['school_id' => $school->id, 'name' => 'UTS', 'code' => 'UTS', 'weight' => 30, 'sort_order' => 2]);
        AssessmentComponent::create(['school_id' => $school->id, 'name' => 'UAS', 'code' => 'UAS', 'weight' => 40, 'sort_order' => 3]);

        GradeScale::create(['school_id' => $school->id, 'min_score' => 90, 'max_score' => 100, 'grade_letter' => 'A', 'description' => 'Sangat Baik']);
        GradeScale::create(['school_id' => $school->id, 'min_score' => 80, 'max_score' => 89, 'grade_letter' => 'B', 'description' => 'Baik']);
        GradeScale::create(['school_id' => $school->id, 'min_score' => 70, 'max_score' => 79, 'grade_letter' => 'C', 'description' => 'Cukup']);
        GradeScale::create(['school_id' => $school->id, 'min_score' => 0, 'max_score' => 69, 'grade_letter' => 'D', 'description' => 'Kurang']);

        $assessment = Assessment::create([
            'school_id' => $school->id,
            'semester_id' => $semester->id,
            'subject_id' => $subject->id,
            'class_id' => $schoolClass->id,
            'component_id' => $component->id,
            'name' => 'UH Matematika 1',
            'max_score' => 100,
            'date' => now()->toDateString(),
        ]);

        Grade::create(['school_id' => $school->id, 'student_id' => $student->id, 'assessment_id' => $assessment->id, 'score' => 85]);

        FinalGrade::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'semester_id' => $semester->id,
            'score' => 85,
            'grade_letter' => 'B',
            'description' => 'Baik',
        ]);

        Announcement::create([
            'school_id' => $school->id,
            'title' => 'Selamat Datang Tahun Ajaran 2025/2026',
            'content' => 'Selamat datang di tahun ajaran baru. Mari kita mulai dengan semangat belajar!',
            'type' => 'general',
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);

        $feeType = FeeType::create(['school_id' => $school->id, 'name' => 'SPP Bulanan', 'code' => 'SPP', 'amount' => 500000, 'is_recurring' => true]);
        PaymentBill::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'fee_type_id' => $feeType->id,
            'amount' => 500000,
            'due_date' => now()->addDays(10)->toDateString(),
            'period' => now()->format('Y-m'),
            'status' => PaymentStatus::Unpaid,
        ]);

        LearningMaterial::create([
            'school_id' => $school->id,
            'subject_id' => $subject->id,
            'class_id' => $schoolClass->id,
            'title' => 'Pengenalan Aljabar',
            'description' => 'Materi pengenalan aljabar dasar',
            'url' => 'https://example.com/materi-aljabar',
            'created_by' => $teacherUser->id,
        ]);

        Assignment::create([
            'school_id' => $school->id,
            'subject_id' => $subject->id,
            'class_id' => $schoolClass->id,
            'title' => 'Tugas 1 - Operasi Aljabar',
            'description' => 'Kerjakan soal operasi aljabar halaman 10-15',
            'deadline' => now()->addDays(7),
            'max_score' => 100,
            'created_by' => $teacherUser->id,
        ]);
    }
}

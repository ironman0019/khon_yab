<?php

namespace Database\Seeders;

use App\Enums\BloodRequestStatus;
use App\Models\BloodRequest;
use App\Models\City;
use App\Models\HospitalUser;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitalUsers = HospitalUser::with('user')->get();
        $adminUsers = User::where('is_admin', true)->get();
        $provinces = Province::with('cities')->take(10)->get();

        if ($hospitalUsers->isEmpty()) {
            $this->command->warn('No hospital users found. Please run HospitalUserSeeder first.');

            return;
        }

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhFactors = ['positive', 'negative'];
        $statuses = [
            BloodRequestStatus::Pending->value,
            BloodRequestStatus::Approved->value,
            BloodRequestStatus::Rejected->value,
            BloodRequestStatus::Completed->value,
        ];

        // Patient names in different languages
        $englishPatientNames = [
            'Ahmed Hassan', 'Fatima Ali', 'Mohammad Karim', 'Zainab Ahmad', 'Omar Khan',
        ];

        $persianPatientNames = [
            'احمد حسین', 'فاطمه علی', 'محمد کریم', 'زینب احمد', 'عمر خان',
        ];

        $pashtoPatientNames = [
            'احمد حسین', 'فاطمه علی', 'محمد کریم', 'زینب احمد', 'عمر خان',
        ];

        // Request reasons in different languages
        $englishReasons = [
            'Emergency surgery required',
            'Accident victim needs blood transfusion',
            'Patient with severe anemia',
            'Post-operative blood loss',
            'Chronic disease treatment',
        ];

        $persianReasons = [
            'جراحی اضطراری مورد نیاز است',
            'قربانی حادثه نیاز به انتقال خون دارد',
            'بیمار با کم خونی شدید',
            'از دست دادن خون پس از عمل',
            'درمان بیماری مزمن',
        ];

        $pashtoReasons = [
            'د اضطراري جراحي اړتیا',
            'د حادثې قرباني ته د وینو لیږد اړتیا لري',
            'د شدید انیمیا سره ناروغ',
            'د عملیات وروسته د وینو ضایع کیدل',
            'د مزمن ناروغۍ درملنه',
        ];

        // Medical center names in different languages
        $englishMedicalCenters = [
            'Central Emergency Ward', 'Surgery Department', 'Intensive Care Unit', 'Emergency Room',
        ];

        $persianMedicalCenters = [
            'بخش اورژانس مرکزی', 'بخش جراحی', 'بخش مراقبت‌های ویژه', 'اتاق اورژانس',
        ];

        $pashtoMedicalCenters = [
            'د مرکزي اضطراري برخه', 'د جراحي برخه', 'د شدیدو پاملرنو برخه', 'د اضطراري خونه',
        ];

        // Rejection reasons in different languages
        $rejectionReasons = [
            'Insufficient blood inventory',
            'موجودی خون کافی نیست',
            'د وینو موجودیت کافي نه دی',
            'Request does not meet criteria',
            'درخواست معیارها را برآورده نمی‌کند',
            'غوښتنه معیارونه پوره نه کوي',
        ];

        // Notes in different languages
        $notes = [
            'Urgent request. Patient condition critical.',
            'درخواست فوری. وضعیت بیمار بحرانی است.',
            'عاجل غوښتنه. د ناروغ حالت بحراني دی.',
            'Standard blood request procedure followed.',
            'رویه استاندارد درخواست خون رعایت شد.',
            'د وینو د غوښتنې معیاري پروسیجر تعقیب شو.',
        ];

        // Create requests
        for ($i = 0; $i < 20; $i++) {
            $hospitalUser = $hospitalUsers->random();
            $province = $provinces->random();
            $city = $province->cities->random();
            $status = $statuses[array_rand($statuses)];

            // Randomly choose language for this request
            $langIndex = rand(0, 2);

            $patientName = match ($langIndex) {
                0 => $englishPatientNames[array_rand($englishPatientNames)],
                1 => $persianPatientNames[array_rand($persianPatientNames)],
                2 => $pashtoPatientNames[array_rand($pashtoPatientNames)],
                default => $englishPatientNames[array_rand($englishPatientNames)],
            };

            $requestReason = match ($langIndex) {
                0 => $englishReasons[array_rand($englishReasons)],
                1 => $persianReasons[array_rand($persianReasons)],
                2 => $pashtoReasons[array_rand($pashtoReasons)],
                default => $englishReasons[array_rand($englishReasons)],
            };

            $medicalCenter = match ($langIndex) {
                0 => $englishMedicalCenters[array_rand($englishMedicalCenters)],
                1 => $persianMedicalCenters[array_rand($persianMedicalCenters)],
                2 => $pashtoMedicalCenters[array_rand($pashtoMedicalCenters)],
                default => $englishMedicalCenters[array_rand($englishMedicalCenters)],
            };

            $approvedBy = null;
            $approvalDate = null;
            $rejectionReason = null;

            if ($status === BloodRequestStatus::Approved->value || $status === BloodRequestStatus::Rejected->value) {
                $approvedBy = $adminUsers->random()->id;
                $approvalDate = now()->subDays(rand(1, 30));

                if ($status === BloodRequestStatus::Rejected->value) {
                    $rejectionReason = $rejectionReasons[array_rand($rejectionReasons)];
                }
            }

            BloodRequest::create([
                'requested_by' => $hospitalUser->user_id,
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'rh_factor' => $rhFactors[array_rand($rhFactors)],
                'number_of_bags' => rand(1, 5),
                'patient_name' => $patientName,
                'patient_age' => rand(5, 80),
                'request_reason' => $requestReason,
                'contact_number' => '0700'.rand(100000, 999999),
                'province_id' => $province->id,
                'city_id' => $city->id,
                'medical_center' => $medicalCenter,
                'status' => $status,
                'approved_by' => $approvedBy,
                'approval_date' => $approvalDate,
                'rejection_reason' => $rejectionReason,
                'notes' => $notes[array_rand($notes)],
            ]);
        }

        $this->command->info('Blood requests seeded successfully!');
        $this->command->info('Total blood requests: '.BloodRequest::count());
    }
}


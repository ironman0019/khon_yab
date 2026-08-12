<?php

namespace Database\Seeders;

use App\Enums\BloodRequestStatus;
use App\Enums\UserType;
use App\Models\BloodRequest;
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
        $laboratoryUsers = User::query()
            ->where('user_type', UserType::Laboratory->value)
            ->get();
        $adminUsers = User::query()
            ->where('is_admin', true)
            ->get();
        $provinces = Province::query()
            ->with('cities')
            ->has('cities')
            ->take(10)
            ->get();

        if ($laboratoryUsers->isEmpty()) {
            $this->command->warn('No laboratory users found. Please run LaboratorySeeder first.');

            return;
        }

        if ($adminUsers->isEmpty()) {
            $this->command->warn('No admin users found. Please run UserSeeder first.');

            return;
        }

        if ($provinces->isEmpty()) {
            $this->command->warn('No provinces with cities found. Please run AfghanistanProvinceCitySeeder first.');

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

        $patientNames = [
            'احمد رضایی',
            'فاطمه حسینی',
            'محمد کریمی',
            'زینب احمدی',
            'علی نوری',
            'مریم محمدی',
            'حسن جعفری',
            'ناهید رحیمی',
            'عبدالله صدیقی',
            'فرشته اکبری',
            'یوسف شریفی',
            'سارا محمودی',
        ];

        $englishReasons = [
            'Emergency surgery required',
            'Accident victim needs blood transfusion',
            'Patient with severe anemia',
            'Post-operative blood loss',
            'Chronic disease treatment',
            'Blood needed for high-risk delivery',
            'Severe burn patient',
            'Platelet shortage in cancer patient',
        ];

        $persianReasons = [
            'جراحی اضطراری مورد نیاز است',
            'قربانی حادثه نیاز به انتقال خون دارد',
            'بیمار با کم‌خونی شدید',
            'از دست دادن خون پس از عمل جراحی',
            'درمان بیماری مزمن',
            'نیاز به خون برای زایمان پرخطر',
            'بیمار سوختگی شدید',
            'کمبود پلاکت در بیمار سرطانی',
        ];

        $pashtoReasons = [
            'د اضطراري جراحي اړتیا',
            'د حادثې قرباني ته د وینو لیږد اړتیا لري',
            'د شدید انیمیا سره ناروغ',
            'د عملیات وروسته د وینو ضایع کیدل',
            'د مزمن ناروغۍ درملنه',
            'د لوړ خطر زیږون لپاره وینې ته اړتیا',
            'د شدیدو سوځېدلو ناروغ',
            'په سرطاني ناروغ کې د پلاټلیټ کمښت',
        ];

        $englishMedicalCenters = [
            'Central Emergency Ward',
            'Surgery Department',
            'Intensive Care Unit',
            'Emergency Room',
            'General Hospital',
            'Pediatric Hospital',
            'Maternity Ward',
            'Medical Treatment Center',
        ];

        $persianMedicalCenters = [
            'بخش اورژانس مرکزی',
            'بخش جراحی',
            'بخش مراقبت‌های ویژه',
            'اتاق اورژانس',
            'بیمارستان عمومی',
            'بیمارستان کودکان',
            'بخش زنان و زایمان',
            'مرکز درمانی',
        ];

        $pashtoMedicalCenters = [
            'د مرکزي اضطراري برخه',
            'د جراحي برخه',
            'د شدیدو پاملرنو برخه',
            'د اضطراري خونه',
            'عمومي روغتون',
            'د ماشومانو روغتون',
            'د ښځو او زیږون برخه',
            'درملنیز مرکز',
        ];

        $englishRejectionReasons = [
            'Insufficient blood inventory',
            'Request does not meet criteria',
            'Patient information is incomplete',
            'Requested blood type is unavailable',
        ];

        $persianRejectionReasons = [
            'موجودی خون کافی نیست',
            'درخواست معیارها را برآورده نمی‌کند',
            'اطلاعات بیمار ناقص است',
            'گروه خونی درخواستی در دسترس نیست',
        ];

        $pashtoRejectionReasons = [
            'د وینو موجودیت کافي نه دی',
            'غوښتنه معیارونه پوره نه کوي',
            'د ناروغ معلومات نیمګړي دي',
            'غوښتل شوې د وینې ډله شتون نلري',
        ];

        $englishNotes = [
            'Urgent request. Patient condition critical.',
            'Standard blood request procedure followed.',
            'Needs quick coordination with the laboratory.',
            'Patient is admitted to the intensive care unit.',
        ];

        $persianNotes = [
            'درخواست فوری. وضعیت بیمار بحرانی است.',
            'رویه استاندارد درخواست خون رعایت شد.',
            'نیاز به هماهنگی سریع با آزمایشگاه.',
            'بیمار در بخش مراقبت‌های ویژه بستری است.',
        ];

        $pashtoNotes = [
            'عاجل غوښتنه. د ناروغ حالت بحراني دی.',
            'د وینو د غوښتنې معیاري پروسیجر تعقیب شو.',
            'د لابراتوار سره چټک همغږۍ ته اړتیا لري.',
            'ناروغ په د شدیدو پاملرنو برخه کې بستر دی.',
        ];

        // Iranian mobile prefixes (09XXXXXXXXX)
        $mobilePrefixes = ['0912', '0913', '0910', '0911', '0935', '0936', '0939', '0901', '0902', '0990'];

        for ($i = 0; $i < 12; $i++) {
            $laboratoryUser = $laboratoryUsers->random();
            $province = $provinces->random();
            $city = $province->cities->random();
            $status = $statuses[array_rand($statuses)];
            $langIndex = rand(0, 2);

            $requestReason = match ($langIndex) {
                0 => $englishReasons[array_rand($englishReasons)],
                1 => $persianReasons[array_rand($persianReasons)],
                2 => $pashtoReasons[array_rand($pashtoReasons)],
            };

            $medicalCenter = match ($langIndex) {
                0 => $englishMedicalCenters[array_rand($englishMedicalCenters)],
                1 => $persianMedicalCenters[array_rand($persianMedicalCenters)],
                2 => $pashtoMedicalCenters[array_rand($pashtoMedicalCenters)],
            };

            $notes = match ($langIndex) {
                0 => $englishNotes[array_rand($englishNotes)],
                1 => $persianNotes[array_rand($persianNotes)],
                2 => $pashtoNotes[array_rand($pashtoNotes)],
            };

            $approvedBy = null;
            $approvalDate = null;
            $rejectionReason = null;

            if ($status === BloodRequestStatus::Approved->value || $status === BloodRequestStatus::Rejected->value) {
                $approvedBy = $adminUsers->random()->id;
                $approvalDate = now()->subDays(rand(1, 30));

                if ($status === BloodRequestStatus::Rejected->value) {
                    $rejectionReason = match ($langIndex) {
                        0 => $englishRejectionReasons[array_rand($englishRejectionReasons)],
                        1 => $persianRejectionReasons[array_rand($persianRejectionReasons)],
                        2 => $pashtoRejectionReasons[array_rand($pashtoRejectionReasons)],
                    };
                }
            }

            BloodRequest::create([
                'requested_by' => $laboratoryUser->id,
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'rh_factor' => $rhFactors[array_rand($rhFactors)],
                'number_of_bags' => rand(1, 5),
                'patient_name' => $patientNames[array_rand($patientNames)],
                'patient_age' => rand(5, 80),
                'request_reason' => $requestReason,
                'contact_number' => $mobilePrefixes[array_rand($mobilePrefixes)].rand(1000000, 9999999),
                'province_id' => $province->id,
                'city_id' => $city->id,
                'medical_center' => $medicalCenter,
                'status' => $status,
                'approved_by' => $approvedBy,
                'approval_date' => $approvalDate,
                'rejection_reason' => $rejectionReason,
                'notes' => $notes,
            ]);
        }

        $this->command->info('Blood requests seeded successfully!');
        $this->command->info('Total blood requests: '.BloodRequest::count());
    }
}

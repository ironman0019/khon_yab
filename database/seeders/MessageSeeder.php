<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->count() < 2) {
            $this->command->warn('Not enough users found. Please run UserSeeder first.');

            return;
        }

        // Subjects in different languages
        $englishSubjects = [
            'Blood Donation Request',
            'Urgent Blood Need',
            'Thank You for Your Donation',
            'Donation Reminder',
            'System Notification',
            'Welcome to KhonYab',
        ];

        $persianSubjects = [
            'درخواست اهدای خون',
            'نیاز فوری به خون',
            'تشکر از اهدای شما',
            'یادآوری اهدا',
            'اطلاعیه سیستم',
            'خوش آمدید به خون یاب',
        ];

        $pashtoSubjects = [
            'د وینو د اهدا غوښتنه',
            'د وینو فوري اړتیا',
            'ستاسو د اهدا مننه',
            'د اهدا یادونه',
            'د سیسټم خبرتیا',
            'خون یاب ته ښه راغلاست',
        ];

        // Messages in different languages
        $englishMessages = [
            'Thank you for your recent blood donation. Your contribution saves lives!',
            'We have an urgent need for blood type O positive. Can you help?',
            'Your donation has been successfully processed and is now available for patients in need.',
            'This is a reminder that you are eligible to donate blood again. Please consider scheduling a donation.',
            'Welcome to KhonYab! We are here to connect blood donors with those in need.',
            'Your blood donation record has been updated. Thank you for keeping your information current.',
        ];

        $persianMessages = [
            'از اهدای خون اخیر شما متشکریم. کمک شما جان‌ها را نجات می‌دهد!',
            'ما نیاز فوری به خون نوع O مثبت داریم. آیا می‌توانید کمک کنید؟',
            'اهدای شما با موفقیت پردازش شد و اکنون برای بیماران نیازمند در دسترس است.',
            'این یادآوری است که شما دوباره واجد شرایط اهدای خون هستید. لطفاً در نظر بگیرید که یک اهدا برنامه‌ریزی کنید.',
            'به خون یاب خوش آمدید! ما اینجا هستیم تا اهداکنندگان خون را با نیازمندان متصل کنیم.',
            'سابقه اهدای خون شما به‌روزرسانی شد. از شما برای به‌روز نگه داشتن اطلاعاتتان متشکریم.',
        ];

        $pashtoMessages = [
            'ستاسو د وروستي وینو د اهدا مننه. ستاسو مرسته ژوندونه خوندي کوي!',
            'موږ د O مثبت وینو فوري اړتیا لرو. ایا تاسو مرسته کولی شئ؟',
            'ستاسو اهدا په بریالیتوب سره پروسس شوې او اوس د اړتیا لرونکو ناروغانو لپاره شتون لري.',
            'دا یادونه ده چې تاسو بیا د وینو د اهدا وړ یاست. مهرباني وکړئ د اهدا د وخت ټاکلو فکر وکړئ.',
            'خون یاب ته ښه راغلاست! موږ دلته یو چې د وینو اهدا کوونکي د اړتیا لرونکو سره وصل کړو.',
            'ستاسو د وینو د اهدا ریکارډ تازه شو. د خپلو معلوماتو د اوسني ساتلو لپاره مننه.',
        ];

        // Create messages between different users
        for ($i = 0; $i < 30; $i++) {
            $sender = $users->random();
            $recipient = $users->where('id', '!=', $sender->id)->random();

            // Randomly choose language for this message
            $langIndex = rand(0, 2);

            $subject = match ($langIndex) {
                0 => $englishSubjects[array_rand($englishSubjects)],
                1 => $persianSubjects[array_rand($persianSubjects)],
                2 => $pashtoSubjects[array_rand($pashtoSubjects)],
                default => $englishSubjects[array_rand($englishSubjects)],
            };

            $message = match ($langIndex) {
                0 => $englishMessages[array_rand($englishMessages)],
                1 => $persianMessages[array_rand($persianMessages)],
                2 => $pashtoMessages[array_rand($pashtoMessages)],
                default => $englishMessages[array_rand($englishMessages)],
            };

            // Randomly decide if message is read
            $isRead = rand(0, 1) === 1;
            $readAt = $isRead ? now()->subDays(rand(1, 10)) : null;

            // Random creation date (past 30 days to now)
            $createdAt = now()->subDays(rand(0, 30));

            Message::create([
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'subject' => $subject,
                'message' => $message,
                'is_read' => $isRead,
                'read_at' => $readAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Messages seeded successfully!');
        $this->command->info('Total messages: '.Message::count());
        $this->command->info('Unread messages: '.Message::where('is_read', false)->count());
    }
}

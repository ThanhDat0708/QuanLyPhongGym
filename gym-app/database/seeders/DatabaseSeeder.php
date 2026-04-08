<?php

namespace Database\Seeders;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@gym.local',
        ], [
            'name' => 'Gym Admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $memberUser = User::firstOrCreate([
            'email' => 'member@gym.local',
        ], [
            'name' => 'Gym Member',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $trainerUser = User::firstOrCreate([
            'email' => 'trainer@gym.local',
        ], [
            'name' => 'Nguyễn Văn PT',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $memberUser2 = User::firstOrCreate([
            'email' => 'member2@gym.local',
        ], [
            'name' => 'Tran Thi Member',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $trainerUser2 = User::firstOrCreate([
            'email' => 'trainer2@gym.local',
        ], [
            'name' => 'Lê Văn Coach',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $memberUser3 = User::firstOrCreate([
            'email' => 'member3@gym.local',
        ], [
            'name' => 'Pham Van Member',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $memberUser4 = User::firstOrCreate([
            'email' => 'member4@gym.local',
        ], [
            'name' => 'Hoang Thi Member',
            'password' => Hash::make('password'),
            'role' => 'member',
        ]);

        $trainerUser3 = User::firstOrCreate([
            'email' => 'trainer3@gym.local',
        ], [
            'name' => 'Trần Quang Coach',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $trainerUser4 = User::firstOrCreate([
            'email' => 'trainer4@gym.local',
        ], [
            'name' => 'Đỗ Minh Coach',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $memberUser->member()->firstOrCreate([], [
            'phone' => '0900000001',
            'address' => 'Quận 1, TP.HCM',
            'height' => 1.72,
            'weight' => 68.5,
            'status' => 'active',
        ]);

        $memberUser2->member()->firstOrCreate([], [
            'phone' => '0900000002',
            'address' => 'Quận 3, TP.HCM',
            'height' => 1.62,
            'weight' => 55.0,
            'status' => 'active',
        ]);

        $memberUser3->member()->firstOrCreate([], [
            'phone' => '0900000003',
            'address' => 'Quận 5, TP.HCM',
            'height' => 1.78,
            'weight' => 74.0,
            'status' => 'active',
        ]);

        $memberUser4->member()->firstOrCreate([], [
            'phone' => '0900000004',
            'address' => 'Thu Duc, TP.HCM',
            'height' => 1.66,
            'weight' => 58.0,
            'status' => 'active',
        ]);

        Trainer::firstOrCreate([
            'user_id' => $trainerUser->id,
        ], [
            'experience' => 3,
            'specialty' => 'Giảm cân',
            'status' => 'active',
        ]);

        Trainer::firstOrCreate([
            'user_id' => $trainerUser2->id,
        ], [
            'experience' => 5,
            'specialty' => 'Sức mạnh & thể lực',
            'status' => 'active',
        ]);

        Trainer::firstOrCreate([
            'user_id' => $trainerUser3->id,
        ], [
            'experience' => 4,
            'specialty' => 'Yoga & dẻo dai',
            'status' => 'active',
        ]);

        Trainer::firstOrCreate([
            'user_id' => $trainerUser4->id,
        ], [
            'experience' => 6,
            'specialty' => 'Thể hình',
            'status' => 'active',
        ]);

        $package1 = GymPackage::firstOrCreate([
            'name' => 'Gói 1 Tháng',
        ], [
            'price' => 500000,
            'duration' => 30,
            'description' => 'Gói tập cơ bản trong 30 ngày.',
        ]);

        $package2 = GymPackage::firstOrCreate([
            'name' => 'Gói 3 Tháng',
        ], [
            'price' => 1300000,
            'duration' => 90,
            'description' => 'Tiết kiệm hơn cho mục tiêu trung hạn.',
        ]);

        $package3 = GymPackage::firstOrCreate([
            'name' => 'Gói 1 Năm',
        ], [
            'price' => 4200000,
            'duration' => 365,
            'description' => 'Gói tập dài hạn tiết kiệm nhất.',
        ]);

        $package4 = GymPackage::firstOrCreate([
            'name' => 'Gói 6 Tháng',
        ], [
            'price' => 2400000,
            'duration' => 180,
            'description' => 'Lựa chọn cân bằng giữa giá và thời hạn.',
        ]);

        $package5 = GymPackage::firstOrCreate([
            'name' => 'Gói PT 10 Buổi',
        ], [
            'price' => 3000000,
            'duration' => 60,
            'description' => 'Khóa tập có huấn luyện viên riêng 10 buổi.',
        ]);

        $registration1 = Registration::firstOrCreate([
            'member_id' => $memberUser->member->id,
            'gym_package_id' => $package1->id,
        ], [
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(25)->toDateString(),
            'status' => 'active',
        ]);

        $registration2 = Registration::firstOrCreate([
            'member_id' => $memberUser2->member->id,
            'gym_package_id' => $package2->id,
        ], [
            'start_date' => now()->subDays(12)->toDateString(),
            'end_date' => now()->addDays(78)->toDateString(),
            'status' => 'pending',
        ]);

        $registration3 = Registration::firstOrCreate([
            'member_id' => $memberUser->member->id,
            'gym_package_id' => $package3->id,
        ], [
            'start_date' => now()->subDays(30)->toDateString(),
            'end_date' => now()->addDays(335)->toDateString(),
            'status' => 'active',
        ]);

        $registration4 = Registration::firstOrCreate([
            'member_id' => $memberUser3->member->id,
            'gym_package_id' => $package4->id,
        ], [
            'start_date' => now()->subDays(8)->toDateString(),
            'end_date' => now()->addDays(172)->toDateString(),
            'status' => 'active',
        ]);

        $registration5 = Registration::firstOrCreate([
            'member_id' => $memberUser4->member->id,
            'gym_package_id' => $package5->id,
        ], [
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->addDays(58)->toDateString(),
            'status' => 'pending',
        ]);

        Payment::firstOrCreate([
            'registration_id' => $registration1->id,
        ], [
            'amount' => $package1->price,
            'method' => 'cash',
            'status' => 'paid',
            'payment_date' => now()->subDays(4)->toDateString(),
        ]);

        Payment::firstOrCreate([
            'registration_id' => $registration2->id,
        ], [
            'amount' => $package2->price,
            'method' => 'cash',
            'status' => 'pending',
            'payment_date' => null,
        ]);

        Payment::firstOrCreate([
            'registration_id' => $registration3->id,
        ], [
            'amount' => $package3->price,
            'method' => 'cash',
            'status' => 'paid',
            'payment_date' => now()->subDays(28)->toDateString(),
        ]);

        Payment::firstOrCreate([
            'registration_id' => $registration4->id,
        ], [
            'amount' => $package4->price,
            'method' => 'cash',
            'status' => 'paid',
            'payment_date' => now()->subDays(7)->toDateString(),
        ]);

        Payment::firstOrCreate([
            'registration_id' => $registration5->id,
        ], [
            'amount' => $package5->price,
            'method' => 'cash',
            'status' => 'pending',
            'payment_date' => null,
        ]);

        Schedule::firstOrCreate([
            'member_id' => $memberUser->member->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer@gym.local'))->value('id'),
            'date' => now()->addDay()->toDateString(),
            'time' => '07:30',
        ], [
            'status' => 'pending',
        ]);

        Schedule::firstOrCreate([
            'member_id' => $memberUser2->member->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer2@gym.local'))->value('id'),
            'date' => now()->addDays(2)->toDateString(),
            'time' => '18:00',
        ], [
            'status' => 'done',
        ]);

        Schedule::firstOrCreate([
            'member_id' => $memberUser3->member->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer3@gym.local'))->value('id'),
            'date' => now()->addDays(3)->toDateString(),
            'time' => '09:00',
        ], [
            'status' => 'pending',
        ]);

        Schedule::firstOrCreate([
            'member_id' => $memberUser4->member->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer4@gym.local'))->value('id'),
            'date' => now()->addDays(4)->toDateString(),
            'time' => '17:30',
        ], [
            'status' => 'cancel',
        ]);

        Review::firstOrCreate([
            'user_id' => $memberUser->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer@gym.local'))->value('id'),
        ], [
            'rating' => 5,
            'comment' => 'HLV nhiệt tình, bài tập phù hợp.',
        ]);

        Review::firstOrCreate([
            'user_id' => $memberUser2->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer2@gym.local'))->value('id'),
        ], [
            'rating' => 4,
            'comment' => 'Lịch tập rõ ràng, tiến bộ tốt.',
        ]);

        Review::firstOrCreate([
            'user_id' => $memberUser3->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer3@gym.local'))->value('id'),
        ], [
            'rating' => 5,
            'comment' => 'Buổi tập mềm mại, phù hợp người mới.',
        ]);

        Review::firstOrCreate([
            'user_id' => $memberUser4->id,
            'trainer_id' => Trainer::whereHas('user', fn ($query) => $query->where('email', 'trainer4@gym.local'))->value('id'),
        ], [
            'rating' => 4,
            'comment' => 'Coach có kinh nghiệm, theo sát học viên.',
        ]);
    }
}

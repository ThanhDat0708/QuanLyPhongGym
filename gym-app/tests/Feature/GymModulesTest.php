<?php

namespace Tests\Feature;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Schedule;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_member_can_register_package(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create([
            'user_id' => $user->id,
            'phone' => '0901234567',
            'address' => 'TP.HCM',
            'height' => 1.70,
            'weight' => 65.0,
            'status' => 'active',
        ]);
        $package = GymPackage::create([
            'name' => 'Goi thu',
            'price' => 100000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $response = $this->actingAs($user)->post(route('site.package.register', $package), [
            'start_date' => now()->toDateString(),
            'confirm_registration' => '1',
        ]);

        $response->assertRedirect(route('site.registrations'));
        $this->assertDatabaseHas('registrations', [
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'preferred_trainer_id' => null,
        ]);
        $this->assertDatabaseHas('payments', [
            'amount' => 100000,
            'method' => 'invoice',
            'status' => 'pending',
        ]);
    }

    public function test_member_can_register_package_with_optional_trainer(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create([
            'user_id' => $user->id,
            'phone' => '0901234568',
            'address' => 'TP.HCM',
            'height' => 1.68,
            'weight' => 59.0,
            'status' => 'active',
        ]);
        $trainerUser = User::factory()->create(['role' => 'staff']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 4,
            'specialty' => 'Bodybuilding',
            'status' => 'active',
        ]);
        $package = GymPackage::create([
            'name' => 'Goi PT',
            'price' => 300000,
            'duration' => 30,
            'description' => 'co PT',
        ]);

        $response = $this->actingAs($user)->post(route('site.package.register', $package), [
            'start_date' => now()->toDateString(),
            'trainer_id' => $trainer->id,
            'confirm_registration' => '1',
        ]);

        $response->assertRedirect(route('site.registrations'));
        $this->assertDatabaseHas('registrations', [
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'preferred_trainer_id' => $trainer->id,
        ]);
    }

    public function test_home_page_can_search_packages(): void
    {
        GymPackage::create([
            'name' => 'Goi Giam Mo',
            'price' => 500000,
            'duration' => 30,
            'description' => 'tap cardio',
        ]);
        GymPackage::create([
            'name' => 'Goi Tang Co',
            'price' => 700000,
            'duration' => 30,
            'description' => 'tap ta nang',
        ]);

        $response = $this->get(route('home', ['q' => 'Giam Mo']));

        $response->assertOk();
        $response->assertSee('Goi Giam Mo');
        $response->assertDontSee('Goi Tang Co');
    }

    public function test_member_must_complete_personal_info_before_registering_package(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        Member::create(['user_id' => $user->id, 'status' => 'active']);
        $package = GymPackage::create([
            'name' => 'Goi Bat Buoc Ho So',
            'price' => 300000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $response = $this->actingAs($user)->get(route('site.package.register.confirm', $package));

        $response->assertRedirect(route('site.personal-info'));
        $this->assertDatabaseMissing('registrations', [
            'gym_package_id' => $package->id,
        ]);
    }

    public function test_member_payment_page_hides_canceled_payments(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'status' => 'active']);
        $package = GymPackage::create([
            'name' => 'Goi An',
            'price' => 200000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'cancel',
        ]);

        Payment::create([
            'registration_id' => $registration->id,
            'amount' => 200000,
            'method' => 'invoice',
            'status' => 'cancel',
        ]);

        $response = $this->actingAs($user)->get(route('site.payments'));

        $response->assertOk();
        $response->assertDontSee('HD-');
        $response->assertSee('Bạn chưa có hóa đơn nào.');
    }

    public function test_member_can_pay_pending_invoice(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'status' => 'active']);
        $package = GymPackage::create([
            'name' => 'Goi Thanh Toan',
            'price' => 450000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'registration_id' => $registration->id,
            'amount' => 450000,
            'method' => 'invoice',
            'status' => 'pending',
        ]);

        $invoiceResponse = $this->actingAs($user)->get(route('site.payments.invoice', $payment));
        $invoiceResponse->assertOk();
        $invoiceResponse->assertSee('Hóa đơn thanh toán');
        $invoiceResponse->assertSee('Goi Thanh Toan');
        $invoiceResponse->assertSee('450,000 VND');

        $response = $this->actingAs($user)->patch(route('site.payments.pay', $payment));

        $response->assertStatus(302);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'paid',
        ]);
    }

    public function test_member_can_submit_review(): void
    {
        $memberUser = User::factory()->create(['role' => 'member']);
        $trainerUser = User::factory()->create(['role' => 'staff']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 2,
            'specialty' => 'Cardio',
            'status' => 'active',
        ]);

        $response = $this->actingAs($memberUser)->post(route('site.reviews.store'), [
            'trainer_id' => $trainer->id,
            'rating' => 5,
            'comment' => 'Tot',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('reviews', [
            'user_id' => $memberUser->id,
            'trainer_id' => $trainer->id,
            'rating' => 5,
        ]);
    }

    public function test_admin_can_create_package(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.packages.store'), [
            'name' => 'Goi 3 thang',
            'price' => 1200000,
            'duration' => 90,
            'description' => 'Muc tieu 90 ngay',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('gym_packages', ['name' => 'Goi 3 thang']);
    }

    public function test_admin_can_create_schedule_and_payment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $memberUser = User::factory()->create(['role' => 'member']);
        $trainerUser = User::factory()->create(['role' => 'staff']);

        $member = Member::create(['user_id' => $memberUser->id, 'status' => 'active']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 3,
            'specialty' => 'Strength',
            'status' => 'active',
        ]);
        $package = GymPackage::create([
            'name' => 'Goi 1 thang',
            'price' => 500000,
            'duration' => 30,
            'description' => 'desc',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.schedules.store'), [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'date' => now()->toDateString(),
            'time' => '08:30',
            'status' => 'pending',
        ])->assertStatus(302);

        $this->actingAs($admin)->post(route('admin.payments.store'), [
            'registration_id' => $registration->id,
            'amount' => 500000,
            'status' => 'paid',
            'payment_date' => now()->toDateString(),
        ])->assertStatus(302);

        $this->assertDatabaseHas('schedules', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
        ]);

        $this->assertDatabaseHas('payments', [
            'registration_id' => $registration->id,
            'method' => 'invoice',
            'status' => 'paid',
        ]);
    }

    public function test_admin_can_update_registration_preferred_trainer(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $memberUser = User::factory()->create(['role' => 'member']);
        $trainerUser = User::factory()->create(['role' => 'staff']);

        $member = Member::create(['user_id' => $memberUser->id, 'status' => 'active']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 5,
            'specialty' => 'Fat Loss',
            'status' => 'active',
        ]);
        $package = GymPackage::create([
            'name' => 'Goi PT Admin',
            'price' => 900000,
            'duration' => 30,
            'description' => 'desc',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.registrations.update', $registration), [
            'preferred_trainer_id' => $trainer->id,
            'start_date' => $registration->start_date->format('Y-m-d'),
            'end_date' => $registration->end_date->format('Y-m-d'),
            'status' => 'active',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'preferred_trainer_id' => $trainer->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('schedules', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_confirmation_auto_creates_schedule_if_trainer_is_available(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $memberUser = User::factory()->create(['role' => 'member']);
        $trainerUser = User::factory()->create(['role' => 'staff']);

        $member = Member::create(['user_id' => $memberUser->id, 'status' => 'active']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 3,
            'specialty' => 'General',
            'status' => 'active',
        ]);
        $package = GymPackage::create([
            'name' => 'Goi Auto Lich',
            'price' => 800000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'preferred_trainer_id' => $trainer->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->put(route('admin.registrations.update', $registration), [
            'preferred_trainer_id' => $trainer->id,
            'start_date' => $registration->start_date->format('Y-m-d'),
            'end_date' => $registration->end_date->format('Y-m-d'),
            'status' => 'paid',
        ])->assertStatus(302);

        $this->assertDatabaseHas('schedules', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'status' => 'pending',
        ]);
    }

    public function test_member_cannot_schedule_with_busy_trainer_at_same_time(): void
    {
        $user = User::factory()->create(['role' => 'member']);
        $member = Member::create(['user_id' => $user->id, 'status' => 'active']);

        $otherUser = User::factory()->create(['role' => 'member']);
        $otherMember = Member::create(['user_id' => $otherUser->id, 'status' => 'active']);

        $trainerUser = User::factory()->create(['role' => 'staff']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 2,
            'specialty' => 'Cardio',
            'status' => 'active',
        ]);

        $package = GymPackage::create([
            'name' => 'Goi Dat Lich',
            'price' => 500000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'paid',
        ]);

        Schedule::create([
            'member_id' => $otherMember->id,
            'trainer_id' => $trainer->id,
            'date' => now()->addDay()->toDateString(),
            'time' => '08:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->post(route('site.registrations.schedule', $registration), [
            'trainer_id' => $trainer->id,
            'date' => now()->addDay()->toDateString(),
            'time' => '08:00',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('trainer_id');

        $this->assertDatabaseCount('schedules', 1);
    }

    public function test_member_cannot_access_admin_routes(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    public function test_admin_is_redirected_from_member_pages_to_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('site.registrations'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_dashboard_can_search_registrations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $memberUser = User::factory()->create(['role' => 'member', 'name' => 'Nguyen Van A']);
        $member = Member::create(['user_id' => $memberUser->id, 'status' => 'active']);

        $packageA = GymPackage::create([
            'name' => 'Goi Tang Co',
            'price' => 500000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $packageB = GymPackage::create([
            'name' => 'Goi Cardio',
            'price' => 450000,
            'duration' => 30,
            'description' => 'test',
        ]);

        Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $packageA->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $packageB->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard', ['q' => 'Tang Co']));

        $response->assertOk();
        $response->assertSee('Goi Tang Co');
        $response->assertDontSee('Goi Cardio');
    }

    public function test_staff_is_redirected_to_today_schedule_page(): void
    {
        $trainerUser = User::factory()->create(['role' => 'staff']);

        $this->actingAs($trainerUser)
            ->get(route('dashboard'))
            ->assertRedirect(route('trainer.today-schedules'));
    }

    public function test_trainer_can_view_today_schedule(): void
    {
        $trainerUser = User::factory()->create(['role' => 'staff', 'name' => 'Coach An']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 4,
            'specialty' => 'Strength',
            'status' => 'active',
        ]);

        $memberUser = User::factory()->create(['role' => 'member', 'name' => 'Member Today']);
        $member = Member::create([
            'user_id' => $memberUser->id,
            'status' => 'active',
        ]);

        Schedule::create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'date' => now()->toDateString(),
            'time' => '08:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($trainerUser)->get(route('trainer.today-schedules'));

        $response->assertOk();
        $response->assertSee('Lịch tập hôm nay');
        $response->assertSee('Member Today');
        $response->assertSee('08:00');
    }

    public function test_trainer_page_shows_selected_registrations_and_can_auto_schedule(): void
    {
        $trainerUser = User::factory()->create(['role' => 'staff', 'name' => 'Coach Auto']);
        $trainer = Trainer::create([
            'user_id' => $trainerUser->id,
            'experience' => 6,
            'specialty' => 'Functional',
            'status' => 'active',
        ]);

        $memberUser = User::factory()->create(['role' => 'member', 'name' => 'Member Select']);
        $member = Member::create([
            'user_id' => $memberUser->id,
            'status' => 'active',
        ]);

        $package = GymPackage::create([
            'name' => 'Goi PT Rieng',
            'price' => 950000,
            'duration' => 30,
            'description' => 'test',
        ]);

        $registration = Registration::create([
            'member_id' => $member->id,
            'gym_package_id' => $package->id,
            'preferred_trainer_id' => $trainer->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'status' => 'paid',
        ]);

        $pageResponse = $this->actingAs($trainerUser)->get(route('trainer.today-schedules'));

        $pageResponse->assertOk();
        $pageResponse->assertSee('Hội viên đã chọn bạn');
        $pageResponse->assertSee('Member Select');
        $pageResponse->assertSee('Goi PT Rieng');

        $autoResponse = $this->actingAs($trainerUser)->post(route('trainer.auto-schedule', $registration));

        $autoResponse->assertStatus(302);
        $this->assertDatabaseHas('schedules', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'status' => 'pending',
        ]);
    }
}

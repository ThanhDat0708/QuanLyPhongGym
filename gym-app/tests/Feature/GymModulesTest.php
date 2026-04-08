<?php

namespace Tests\Feature;

use App\Models\GymPackage;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Registration;
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
        $member = Member::create(['user_id' => $user->id, 'status' => 'active']);
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
        $member = Member::create(['user_id' => $user->id, 'status' => 'active']);
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

    public function test_member_cannot_access_admin_routes(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get(route('admin.dashboard'))
            ->assertStatus(403);
    }
}

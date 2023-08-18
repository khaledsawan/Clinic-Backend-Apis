<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminTest extends TestCase
{
    use DatabaseTransactions; // Rollback database changes after each test
    use WithoutMiddleware;

    public function testRegisterPatient()
    {
        $response = $this->postJson('/api/registerPatient', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_num' => '123456789',
            'password' => 'password123',
            'gender' => 'male',
            'address' => '123 Main St',
            'birth_date' => '1990-01-01',
            'FCMToken' => 'fcm-token',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'role' => 'patient',
            ]);
    }

    public function testRegisterSecretary()
    {
        $response = $this->postJson('/api/registerSecretary', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone_num' => '987654321',
            'password' => 'password123',
            'department_id' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'role' => 'secretary',
            ]);
    }

    public function testRegisterDoctor()
    {
        $response = $this->postJson('/api/registerDoctor', [
            'first_name' => 'Dr. John',
            'last_name' => 'Smith',
            'email' => 'john.smith@example.com',
            'phone_num' => '555555555',
            'password' => 'password123',
            'department_id' => 1,
            'specialty' => 'Cardiology',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'role' => 'doctor',
            ]);
    }

    public function testLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'role' => $user->role,
            ]);
    }


    public function testRegisterAdmin()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone_num' => '1234567890',
            'password' => 'password123',
        ];

        $response = $this->post('/api/registerAdmin', $data);

        $response->assertStatus(200)
            ->assertJson([
                'role' => 'admin',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'admin',
        ]);
    }

    public function testLogout()
{
    Passport::actingAs(
        User::factory()->create()
    );

    $response = $this->get('/api/logout');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'You have been successfully logged out!',
        ]);
}
}

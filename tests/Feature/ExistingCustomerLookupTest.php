<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExistingCustomerLookupTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('customer_master', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_name');
            $table->string('customer_mobile');
            $table->string('customer_email');
            $table->string('password');
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->boolean('iStatus')->default(true);
            $table->boolean('isDelete')->default(false);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('customer_master');

        parent::tearDown();
    }

    public function testExistingCustomerDetailsAreReturnedForMobileNumber(): void
    {
        $customer = $this->createCustomer();

        $this->postJson(route('customer.registration.existing-customer'), [
            'customer_mobile' => $customer->customer_mobile,
        ])->assertOk()
            ->assertJsonPath('customer.customer_name', 'Existing Reader')
            ->assertJsonPath('customer.customer_mobile', '9876543210')
            ->assertJsonPath('customer.address_line_1', '10 Book Street')
            ->assertJsonMissingPath('customer.password');
    }

    public function testMobileNumberMustContainExactlyTenDigits(): void
    {
        $this->postJson(route('customer.registration.existing-customer'), [
            'customer_mobile' => '12345',
        ])->assertUnprocessable()
            ->assertJsonMissingPath('customer')
            ->assertJsonValidationErrors('customer_mobile');
    }

    public function testNewCustomerCanContinueToUseTheRegistrationForm(): void
    {
        $this->postJson(route('customer.registration.existing-customer'), [
            'customer_mobile' => '9123456789',
        ])->assertNotFound()
            ->assertJsonMissingPath('customer');
    }

    private function createCustomer(): Customer
    {
        return Customer::create([
            'customer_name' => 'Existing Reader',
            'customer_mobile' => '9876543210',
            'customer_email' => 'reader@example.com',
            'password' => Hash::make('secret123'),
            'address_line_1' => '10 Book Street',
            'address_line_2' => 'Near Library',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
            'iStatus' => 1,
            'isDelete' => 0,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        $key = env('ENCRYPTION_KEY', '256');

        $password = 'Admin123';

        $encrypted_password = $this->encrypt($password, $key);

        DB::table('users')->insert([
            [
                'name' => 'ADMIN',
                'phone_number' => '8522136455333',
                'password' => $encrypted_password,
                'hkid' => 'Tidak tersedia',
                'file_hkid' => 'Tidak tersedia',
                'file_bank_book' => 'Tidak tersedia',
                'banks_id' => '1',
                'bank_account_number' => 'Tidak tersedia',
                'bank_holder_name' => 'Tidak tersedia',
                'roles_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function encrypt($data, $key)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
}

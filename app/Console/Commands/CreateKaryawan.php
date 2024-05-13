<?php

namespace App\Console\Commands;

use Validator;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-karyawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat user karyawan untuk aplikasi Sripuja Elektronik MS';

    protected function isValid($value, $field, $rule)
    {
        $validator = Validator::make([
            $field => $value
        ], [
            $field => $rule
        ]);

        return !$validator->fails();
    }

    protected function askValid($question, $field, $rule, $errMsg)
    {
        $input = $this->ask($question);

        if (!$this->isValid($input, $field, $rule)) {
            $this->error($errMsg);

            return $this->askValid($question, $field,
                $rule, $errMsg);
        }

        return $input;
    }

    protected function createUsername($fullName)
    {
        $username = explode(' ', $fullName);
        $username = $username[0] . '.' . end($username);

        // Check if username exists
        if ($count = User::where('username', $username)->count()) {
            $username = $username . $count;
        }

        return $username;
    }

    protected function createPassword($phoneNum)
    {
        return Hash::make($phoneNum);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $karyawan = [
            'nama_lengkap' => $this->ask('Nama Lengkap'),
            'alamat' => $this->ask('Alamat'),
            'telepon' => $this->askValid('Telepon', 'telepon',
                'nullable|regex:/(^[(]?[0-9]{1,4}[)]?[0-9]+$)/u', 'Nomor telpon tidak valid'),
            'no_hp' => $this->askValid('Nomor Hp[08..]', 'no_hp',
                'required|numeric|regex:/^(08[1-9][0-9]{6,10}$)/u', 'Nomor hp tidak valid'),
            'gaji' => $this->askValid('Gaji', 'gaji', 'required|numeric|min:1',
                'Gaji tidak valid'),
            'tipe' => $this->askValid('Tipe Karyawan[Non-Kasir|Kasir]', 'tipe',
                'required|in:Non-Kasir,Kasir', 'Tipe karyawan tidak valid, gunakan yang ada')
        ];

        $user = [
            'username' => $this->createUsername($karyawan['nama_lengkap']),
            'password'=> $this->createPassword($karyawan['no_hp']),
        ];;

        $user = User::create($user);

        $karyawan['id_user'] = $user->id;

        Karyawan::create($karyawan);

        $this->info('Akun Karyawan baru berhasil dibuat');
    }
}

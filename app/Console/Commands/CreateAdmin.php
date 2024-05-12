<?php

namespace App\Console\Commands;

use Hash;
use App\Models\User;
use Illuminate\Console\Command;
use Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat user admin untuk aplikasi sripuja elektronik MS';

    protected function isValid($value, $field, $rule) {
        $validator = Validator::make([
            $field => $value
        ], [
            $field => $rule
        ]);

        return !$validator->fails();
    }

    protected function askValid($question, $field, $rule, $errMsg) {
        $input = $this->ask($question);

        if (!$this->isValid($input, $field, $rule)) {
            $this->error($errMsg);

            return $this->askValid($question, $field, $rule, $errMsg);
        }

        return $input;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data['username'] = $this->askValid('Username',
            'username', 'required|unique:users',
            'Username sudah dipakai, gunakan username lain.');

        $data['email'] = $this->askValid('Email',
            'email', 'required|email|unique:users',
            'Email sudah dipakai, gunakan email lain.');

        $data['password'] = $this->secret('Password');
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        $this->info('Akun admin baru berhasil dibuat');
    }
}

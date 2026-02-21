<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin
        {--name= : Nama admin}
        {--email= : Email admin}
        {--phone= : No. telepon admin}
        {--password= : Password admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat akun admin baru';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name') ?? text(
            label: 'Nama',
            required: 'Nama wajib diisi.',
            validate: fn (string $value) => match (true) {
                strlen($value) < 3 => 'Nama minimal 3 karakter.',
                strlen($value) > 255 => 'Nama maksimal 255 karakter.',
                default => null,
            },
        );

        $email = $this->option('email') ?? text(
            label: 'Email',
            required: 'Email wajib diisi.',
            validate: function (string $value) {
                $validator = Validator::make(
                    ['email' => $value],
                    ['email' => 'email|unique:users,email|max:255'],
                );

                if ($validator->fails()) {
                    return $validator->errors()->first('email');
                }

                return null;
            },
        );

        $phone = $this->option('phone') ?? text(
            label: 'No. Telepon',
            required: 'No. telepon wajib diisi.',
            validate: fn (string $value) => match (true) {
                strlen($value) < 10 => 'No. telepon minimal 10 karakter.',
                strlen($value) > 255 => 'No. telepon maksimal 255 karakter.',
                default => null,
            },
        );

        $pwd = $this->option('password') ?? password(
            label: 'Password',
            required: 'Password wajib diisi.',
            validate: fn (string $value) => match (true) {
                strlen($value) < 8 => 'Password minimal 8 karakter.',
                default => null,
            },
        );

        $validator = Validator::make(
            compact('name', 'email', 'phone', 'pwd'),
            [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'phone' => ['required', 'string', 'min:10', 'max:255'],
                'pwd' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'name.min' => 'Nama minimal 3 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'phone.required' => 'No. telepon wajib diisi.',
                'phone.min' => 'No. telepon minimal 10 karakter.',
                'pwd.required' => 'Password wajib diisi.',
                'pwd.min' => 'Password minimal 8 karakter.',
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $pwd,
            'role' => 'admin',
        ]);

        $user->markEmailAsVerified();

        $this->components->info("Akun admin [{$email}] berhasil dibuat.");

        return self::SUCCESS;
    }
}

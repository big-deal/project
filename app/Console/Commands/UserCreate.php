<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create
                            {--N|name=     : User name}
                            {--E|email=    : User email}
                            {--P|password= : User password}
                            {--T|team=*    : Attach team}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function getValidator(array $data = []): ValidatorContract
    {
        return Validator::make($data + [
                'name' => $this->option('name') ?? $this->ask('What is your name?'),
                'email' => $this->option('email') ?? $this->ask('What is your email?'),
                'password' => $this->option('password') ?? $this->ask('What is your password?', 'secret'),
                'team' => $this->option('team') ?? [],
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'team' => ['nullable', 'exists:teams,id'],
            ]
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $validator = $this->getValidator();

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("\t".$error);
            }

            return 1;
        }

        $data = collect($validator->getData());

        $user = tap(User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]))->markEmailAsVerified();

        return $this->call(
            'user:attach-team',
            $data->merge(['user' => $user->id])
                ->only('user', 'team')
                ->toArray()
        );
    }
}

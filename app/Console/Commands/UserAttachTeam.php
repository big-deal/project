<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class UserAttachTeam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:attach-team
                            {user  : User id}
                            {team* : Team ids} ';

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
                'user' => $this->argument('user'),
                'team' => $this->argument('team'),
            ],
            [
                'user' => ['required', 'exists:users,id'],
                'team' => ['required', 'exists:teams,id'],
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
            $this->info('Team(s) not attached. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("\t".$error);
            }

            return 1;
        }

        $data = $validator->getData();

        User::findOrFail($data['user'])
            ->teams()
            ->syncWithoutDetaching($data['team']);

        return 0;
    }
}

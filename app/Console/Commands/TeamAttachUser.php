<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;

class TeamAttachUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:attach-user
                            {team  : Team id} 
                            {user* : User ids}';

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
                'team' => $this->argument('team'),
                'user' => $this->argument('user'),
            ],
            [
                'team' => ['required', 'exists:teams,id',],
                'user' => ['required', 'exists:users,id',],
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
            $this->info('User(s) not attached. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("\t".$error);
            }
            return 1;
        }

        $data = $validator->getData();

        Team::findOrFail($data['team'])
            ->users()
            ->syncWithoutDetaching($data['user']);

        return 0;
    }
}

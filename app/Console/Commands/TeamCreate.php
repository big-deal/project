<?php

namespace App\Console\Commands;

use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Validator;

class TeamCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:create
                            {title : Team title}
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
                'title' => $this->argument('title'),
                'user' => $this->argument('user'),
            ],
            [
                'title' => ['required', 'string', 'max:100',],
                'user' => ['nullable', 'exists:users,id',],
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
            $this->info('Team not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error("\t".$error);
            }
            return 1;
        }

        $data = collect($validator->getData());

        $team = Team::create([
            'title' => $data['title'],
        ]);

        return $this->call(
            'team:attach-user',
            $data->merge(['user' => $team->id])
                ->only('team', 'user')
                ->toArray()
        );
    }
}

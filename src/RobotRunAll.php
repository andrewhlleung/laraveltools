<?php

namespace Andrewhlleung\Laraveltools;


use Illuminate\Console\Command;

class RobotRunAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run All';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('********************************************');
        $this->call('robot:mtop');
        $this->call('robot:cm');
        $this->info('********************************************');
    }

}

<?php

namespace Modules\Test\Console;

use Illuminate\Console\Command;
use Modules\System\Entities\Test;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command 任务测试';

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
        //
        $test = new Test();
        $test->fill([
            'title'   => '测试每6小时任务调度', // 'App req=>' . date('Y-m-d H:i:s'),
            'content' => date('Y-m-d H:i:s')
        ]);
        $test->save();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            // ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}

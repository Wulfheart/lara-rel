<?php

namespace App\Console\Commands;

use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class speed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'speed';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $num_docs = [10, 100, 500, 1000, 1500, 2000, 5000, 10000, 15000, 20000];
        logger()->info(now() . ' TimeTest');
        foreach ($num_docs as  $num) {
            // Reset everything
            \App\documentation::truncate();
            \App\deadline::truncate();
            $this->info('Testing for ' . $num);
            logger()->info('Testing for ' . $num);
            $seed_docs = $this->fn(function () use ($num) {
                factory(\App\documentation::class, $num)->create();
            });

            $docs = \App\documentation::all();
            $bar = $this->output->createProgressBar(count($docs));
            $seed_deadlines = $this->fn(function () use ($docs, $bar) {
                foreach ($docs as $doc) {
                    factory(\App\deadline::class, rand(0, 5))->create([
                        'documentation_id' => $doc->id
                    ]);
                    $bar->advance();
                }
                $bar->finish();
            });

            $first = now();
            $mem_bef = memory_get_usage();
            $data = DB::select('SELECT d.* FROM( SELECT MIN(due_until) AS di, documentation_id FROM deadlines GROUP BY documentation_id ORDER BY di ) AS X INNER JOIN documentations AS d ON d.id = x.documentation_id');
            $mem_after = memory_get_usage();
            $second = now();
            $mem_data = $mem_after - $mem_bef;
            $query_time = $this->td($first, $second);

            $first = now();
            $mem_bef = memory_get_usage();
            $hyd = \App\documentation::hydrate($data);
            $mem_after = memory_get_usage();
            $second = now();
            $mem_hyd = $mem_after - $mem_bef;
            $hydration_time = $this->td($first, $second);
            $arr = [
                ['Docs Seedtime', $seed_docs],
                ['Deadlines Seedtime', $seed_deadlines],
                ['Query Time', $query_time],
                ['Memory Query Data', $this->formatBytes($mem_data)],
                ['Hydration Time', $hydration_time],
                ['Memory Hydrated Data', $this->formatBytes($mem_hyd)],
            ];
            logger('Results for ' . $num, $arr);
            $this->line('');
            $this->table(null, $arr);
        }
    }

    public function fn($func)
    {
        $first = now();
        $func();
        $second = now();
        return $this->td($first, $second);
    }

    public function td($first, $second)
    {
        return $second->diff($first)->format('%H:%I:%S');
    }

    public function formatBytes($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        } else {
            return $size;
        }
    }
}

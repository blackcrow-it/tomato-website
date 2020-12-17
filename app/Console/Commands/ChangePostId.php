<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ChangePostId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post:change_id';

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
     * @return int
     */
    public function handle()
    {
        DB::transaction(function () {
            $targetId = 0;
            $destId = 0;

            $data = DB::table('posts')->find($targetId);
            $data->id = $destId;

            DB::table('posts')
                ->insert((array)$data);

            DB::table('post_position')
                ->where('post_id', $targetId)
                ->update([
                    'post_id' => $destId
                ]);

            DB::table('post_related_courses')
                ->where('post_id', $targetId)
                ->update([
                    'post_id' => $destId
                ]);

            DB::table('post_related_posts')
                ->where('post_id', $targetId)
                ->update([
                    'post_id' => $destId
                ]);

            DB::table('post_related_posts')
                ->where('related_post_id', $targetId)
                ->update([
                    'related_post_id' => $destId
                ]);

            DB::table('posts')
                ->where('id', $targetId)
                ->delete();
        });
    }
}

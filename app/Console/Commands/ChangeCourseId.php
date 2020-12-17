<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ChangeCourseId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:change_id';

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

            $data = DB::table('courses')->find($targetId);
            $data->id = $destId;

            DB::table('courses')
                ->insert((array)$data);

            DB::table('book_related_courses')
                ->where('related_course_id', $targetId)
                ->update([
                    'related_course_id' => $destId
                ]);

            DB::table('course_position')
                ->where('course_id', $targetId)
                ->update([
                    'course_id' => $destId
                ]);

            DB::table('course_related_books')
                ->where('course_id', $targetId)
                ->update([
                    'course_id' => $destId
                ]);

            DB::table('course_related_courses')
                ->where('course_id', $targetId)
                ->update([
                    'course_id' => $destId
                ]);

            DB::table('course_related_courses')
                ->where('related_course_id', $targetId)
                ->update([
                    'related_course_id' => $destId
                ]);

            DB::table('lessons')
                ->where('course_id', $targetId)
                ->update([
                    'course_id' => $destId
                ]);

            DB::table('post_related_courses')
                ->where('related_course_id', $targetId)
                ->update([
                    'related_course_id' => $destId
                ]);

            DB::table('courses')
                ->where('id', $targetId)
                ->delete();
        });
    }
}

<?php

namespace App\Console\Commands;

use App\Course;
use App\UserCourse;
use Illuminate\Console\Command;

class CopyUserCourseToCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:copy_user_course_to_course {id_course_from} {id_course_to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy học viên từ khoá học sang khoá học mới';

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
        try {
            $courseFrom = Course::find($this->argument('id_course_from'));
            $courseTo = Course::find($this->argument('id_course_to'));
            if (!$courseFrom) {
                $this->error("- Not found course from by id: {$this->argument('id_course_from')}");
            }
            if (!$courseTo) {
                $this->error("- Not found course to by id: {$this->argument('id_course_to')}");
            }
            if ($courseFrom && $courseTo) {
                $listUserCoursesFrom = $courseFrom->user_courses;
                $listUsersFrom = array();
                $listUserCoursesTo = $courseTo->user_courses;
                $listUsersTo = array();
                $listUsers = array();
                foreach ($listUserCoursesFrom as $index => $userCourse) {
                    array_push($listUsersFrom, $userCourse->user);
                }
                foreach ($listUserCoursesTo as $index => $userCourse) {
                    array_push($listUsersTo, $userCourse->user);
                }
                foreach ($listUsersFrom as $index => $user) {
                    if (!in_array($user, $listUsersTo)) {
                        array_push($listUsers, $user);
                        $userCourseNew = new UserCourse();
                        $userCourseNew->user_id = $user->id;
                        $userCourseNew->course_id = $courseTo->id;
                        $userCourseNew->save();
                    }
                }
                $this->info("Course '{$courseFrom->title}' has ".count($listUsersFrom)." (users)");
                $this->info("Course '{$courseFrom->title}' has ".count($listUsersTo)." (users)");
                $this->info("Copied ".count($listUsers)." (users)");
            }
        } catch (\Throwable $th) {
            $this->error("Command error");
        }
        return 0;
    }
}

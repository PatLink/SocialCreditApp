<?php namespace SocialCreditPointsApp\Http\Controllers;

use Request;
use DB;
use Auth;
use Input;
use Validator;
use Session;
use PDF;
use Image;
use SocialCreditPointsApp\User;

class ScpController extends Controller {

    public function __construct(User $user)
    {
        $this->middleware('auth');
        if(Auth::user()->userrole == 2){
            return redirect('user');
        }

        $user->Session();
        parent::__construct();
    }

    # SCP
    public function index(){

        $userid = DB::table('users')->where('email', Auth::user()->email)->pluck('id');

        //Student
        if (Auth::user()->userrole == 1) {
            $student = DB::table('students')->where('user_id', $userid)->first();
            $academic_years = DB::table('academic_years')->where('course_id', $student->course_id)->get();

            $years = [];
            foreach ($academic_years as $value) {
                array_push($years, [date_parse($value->start_date)['year'], date_parse($value->end_date)['year']]);
            }

            $period = $this->getPeriod($academic_years);

            foreach ($academic_years as $key => $value) {

                if ($value->start_date < date("Y-m-d") && date("Y-m-d") < $value->end_date) {
                    $filter_id = $key;
                }
            }

            if (Request::ajax()) {
                $filter_id = $_POST['filter_id'];
            }

            $projects = $this->getProjects($userid, $period, $filter_id);

            if (Request::ajax()) {
                return view('includes.user.project_table', compact('projects'));
            }

            //return Student View
            return view('users.scp.student.index', compact('projects', 'years', 'filter_id'));
        }

        //Studiengangsleiter
        if (Auth::user()->userrole == 3) {
            $professor = DB::table('professors')->where('user_id', $userid)->first();
            $courses = DB::table('courses')->where('name', $professor->course)->get();
            $filter_id = 0;

            $course_ids = $this->getCourseIds($courses);

            $abbreviation = array("ALLE");
            foreach($courses as $value) {
                array_push($abbreviation, $value->abbreviation);
            }

            if (Request::ajax()) {
                $filter_id = $_POST['filter_id'];
            }

            $studentsOfSgl = $this->getStudentsOfSgl($filter_id, $courses, $course_ids);

            if (Request::ajax()) {
                return view('includes.user.students_table', compact('studentsOfSgl'));
            }
            //return Studiengangsleiter View
            return view('users.scp.sgl.index', compact('abbreviation', 'studentsOfSgl', 'filter_id'));
        }
    }

    // PDF drucken
    public function printout($filter_id)
    {
        return $this->generate($filter_id)->stream();
    }

    // PDF downloaden
    public function download($filter_id)
    {
        return $this->generate($filter_id)->download('SocialCreditPoints.pdf');
    }

    // PDF generieren
    public function generate($filter_id)
    {
        $user = DB::table('users')->where('email', Auth::user()->email)->first();
        $userid = $user->id;

        if (Auth::user()->userrole == 1) {
            $student = DB::table('students')->where('students.user_id', $userid)->first();

            $course = DB::table('courses')
                ->where('id', $student->course_id)
                ->first();

            $academic_years = DB::table('academic_years')->where('course_id', $student->course_id)->get();

            $period = $this->getPeriod($academic_years);

            $projects = $this->getProjects($userid, $period, $filter_id);

            //return PDF Student
            $pdf = PDF::loadView('users.scp.student.pdf', compact('user', 'projects', 'student' , 'course' ), ['academic_year' => $academic_years[$filter_id]]);
            return $pdf;
        }

        if (Auth::user()->userrole == 3) {

            $professor = DB::table('professors')->where('user_id', $userid)->first();
            $courses = DB::table('courses')->where('name', $professor->course)->get();

            $course_ids = $this->getCourseIds($courses);

            $studentsOfSgl = $this->getStudentsOfSgl($filter_id, $courses, $course_ids);

            //return PDF Studiengangsleiter
            $pdf = PDF::loadView('users.scp.sgl.pdf', compact('studentsOfSgl'));
            return $pdf;
        }
    }

    private function getPeriod($academic_years)
    {
        $period = [];
        foreach ($academic_years as $value) {
            array_push($period, [$value->start_date, $value->end_date]);
        }
        return $period;
    }

    // Projekte ausgeben an denen angemeldeter Student teilgenommen hat, die abgeschlossen sind und deren Projektende innerhalb des ausgewählten Studienjahres liegt
    private function getProjects($userid, $period, $filter_id)
    {
        return DB::table('projects')
            ->join('project_participants', 'project_participants.project_id', '=', 'projects.id')
            ->join('students', 'students.user_id', '=', 'project_participants.user_id')
            ->where('students.user_id', $userid)
            ->where('projects.status', 'abgeschlossen')
            ->whereBetween('projects.end_date', array($period[$filter_id][0], $period[$filter_id][1]))
            ->get();
    }

    private function getCourseIds($courses)
    {
        $course_ids = [];
        foreach ($courses as $value) {
            array_push($course_ids, $value->id);
        }
        return $course_ids;
    }

    private function getStudentsOfSgl($filter_id, $courses, $course_ids)
    {
        if ($filter_id != 0) {
            $courses = $courses[$filter_id - 1];
            $course_ids = [$courses->id];
        }

        $studentsOfSgl = DB::table('users')
            ->join('students', 'students.user_id', '=', 'users.id')
            ->join('courses', 'courses.id', '=', 'students.course_id')
            ->join('academic_years', 'academic_years.course_id', '=', 'courses.id')
            ->whereIn('students.course_id', $course_ids)
            ->where('academic_years.start_date', '<', date("Y-m-d"))
            ->where('academic_years.end_date', '>', date("Y-m-d"))
            ->orderBy('users.last_name', 'asc')
            ->get();

        $studentsOfSgl_ids = [];
        foreach ($studentsOfSgl as $key => $value) {
            array_push($studentsOfSgl_ids, $value->user_id);
        }

        $studentprojects = DB::table('projects')
            ->join('project_participants', 'project_participants.project_id', '=', 'projects.id')
            ->join('students', 'students.user_id', '=', 'project_participants.user_id')
            ->whereIn('students.user_id', $studentsOfSgl_ids)
            ->where('projects.status', 'abgeschlossen')
            ->select('students.user_id', 'scp_reward', 'end_date' )
            ->get();

        foreach ($studentsOfSgl as $key => $value) {
            $value->scp_reward = "0";
            foreach ($studentprojects as $project) {
                if ($value->user_id ==  $project->user_id) {
                    if(($value->start_date < $project->end_date) && ($project->end_date < $value->end_date)) {
                        $value->scp_reward += $project->scp_reward;
                    }
                }
            }
        }
        return $studentsOfSgl;
    }

}

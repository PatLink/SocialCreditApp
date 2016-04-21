<?php namespace SocialCreditPointsApp\Http\Controllers;

use Request;
use Input;
use DB;
use Auth;
use Session;
use SocialCreditPointsApp\Project;
use SocialCreditPointsApp\User;

class DashboardController extends Controller {

    public function __construct(Project $project, User $user)
    {
        $this->middleware('auth');
        #$this->middleware('owner');
        $this->project = $project;
        $user->Session();
        parent::__construct();
    }

    public function index()
    {
        $user = DB::table('users')->where('email', Auth::user()->email)->first();
        $userid = $user->id;
        $userprojects = DB::table('projects')->where('created_by', $userid)->get();
        $projects = DB::table('projects')->where('status', 'bestätigt')->get();

        if(Auth::user()->userrole == '3'){

            $unconfirmed_projects = DB::table('projects')->where('status', 'unbestätigt')->get();
            $unconfirmed_student_projects = DB::table('project_participants')->where('status', 0)->get();

            $applyed_projects = [];
            for ($i= 0; $i < count($unconfirmed_student_projects); $i++) {
                $project = DB::table('projects')->where('id', $unconfirmed_student_projects[$i]->project_id)->first();
                $student = DB::table('users')->where('id', $unconfirmed_student_projects[$i]->user_id)->first();
                $applyed_projects[$i]['project_id'] = $project->id;
                $applyed_projects[$i]['project_name'] = $project->name;
                $applyed_projects[$i]['project_slug'] = $project->slug;
                $applyed_projects[$i]['description'] = $project->description;
                $applyed_projects[$i]['student_id'] = $student->id;
                $applyed_projects[$i]['student_first_name'] = $student->first_name;
                $applyed_projects[$i]['student_last_name'] = $student->last_name;
            }

            return view('users.index', [
                'projects' => $projects,
                'userprojects' => $userprojects,
                'unconfirmed_projects' => $unconfirmed_projects,
                'applyed_projects' => $applyed_projects
            ]);
        }

        return view('users.index', ['projects' => $projects, 'userprojects' => $userprojects]);
    }

    # My Projects
    public function myprojects(){
        $user = DB::table('users')->where('email', Auth::user()->email)->first();
        $user_id = $user->id;
        $student = DB::table('students')->where('user_id', $user_id)->first();

        $projects = DB::table('projects')
            ->where('status', 'bestätigt')
            ->orWhere('status', 'abgelehnt')
            ->orWhere('status', 'abgeschlossen')
            ->get();

        $user_projects = null;
        for($i = 0; $i < count($projects); $i++){

            if(DB::table('project_participants')
                ->where('user_id', $user_id)
                ->where('project_id', $projects[$i]->id)
                ->first()
            ){
                $user_projects[$i]['created_by'] = $projects[$i]->created_by;
                $user_projects[$i]['slug'] = $projects[$i]->slug;
                $user_projects[$i]['name'] = $projects[$i]->name;
                $user_projects[$i]['tutor'] = $projects[$i]->tutor;
                $user_projects[$i]['participants_capacity'] = $projects[$i]->participants_capacity;
                $user_projects[$i]['scp_reward'] = $projects[$i]->scp_reward;
                $user_projects[$i]['engagement'] = $projects[$i]->engagement;
                $user_projects[$i]['categories'] = $projects[$i]->categories;
                $user_projects[$i]['description'] = $projects[$i]->description;
                $user_projects[$i]['status'] = $projects[$i]->status;
                $user_projects[$i]['storage_id'] = $projects[$i]->storage_id;
                $user_projects[$i]['image'] = $projects[$i]->image;
                $user_projects[$i]['start_date'] = $projects[$i]->start_date;
                $user_projects[$i]['end_date'] = $projects[$i]->end_date;
            }
        }

        return view('users.projects.index', ['user_projects' => $user_projects]);
    }

    public function myprojects_show($project_name){
        $project = Project::where('slug', $project_name)->first();
        $user  = DB::table('users')->where('email', Auth::user()->email)->first();
	$statusiconname = 'ok';
	$participants = $project->getParticipants();

        $project_applied  =DB::table('project_participants')
            ->where('user_id', $user->id)
            ->where('project_id', $project->id)
            ->where('status', 0)
            ->first();

        return view('users.projects.show', ['project' => $project, 'participants' => $participants, 'statusiconname' => $statusiconname, 'project_applied' => $project_applied]);
    }

    public function myprojects_documentation($project_name){
        $project = DB::table('projects')->where('slug', $project_name)->first();
        return view('users.projects.user_documentation', ['project' => $project]);
    }

    public function myprojects_documentation_post($project_name){

        $user = DB::table('students')
            ->where('user_id', Auth::user()->id)
            ->first();

        $project = DB::table('projects')->where('slug', $project_name)->first();

        $file = Request::file('documentation');

        if($file){
            $extension = $file->getClientOriginalExtension();
            $file->move(public_path().'/user_files', $file->getFilename() . '.' . $extension);
            $project->storage_id = '';
        }

        $comment = Input::get('comment');

        DB::table('project_participants')
            ->where('project_id', $project->id)
            ->where('user_id', $user->id)
            ->update([
                'comment' => $comment,
                'documentation_file_id' => ''
            ]);


        DB::table('projects')
            ->where('slug', $project_name)
            ->update(['status' => 'abgeschlossen']);

        return redirect('user/projects');
    }

    # Userprofile
    public function userprofile(){
        return view('users.profile.index');
    }

    # Profile View for SGL
    public function  user_overview(){
        return view('users.profile.overview');
    }

    public function  userprofile_show($student_name){
        return view('users.profile.show', ['student_name' => $student_name ]);
    }

    public function reject($project_name){
        if(Auth::user()->userrole == '3'){
            DB::table('projects')
                ->where('slug', $project_name)
                ->update(array('status' => 'abgelehnt','seen' => false));
            return redirect('user');
        }

        return redirect('404');
    }

    public function confirm($project_name){

        if(Auth::user()->userrole == '3'){
            DB::table('projects')
                ->where('slug', $project_name)
                ->update(array('status' => 'bestätigt','seen' => false));
            return redirect('user');
        }

        return redirect('404');
    }

    public function reject_project_apply($project_name, $user_id){
        $projectid = DB::table('projects')->where('slug', $project_name)->pluck('id');

        if(Auth::user()->userrole == '3'){
            DB::table('project_participants')
                ->where('user_id', $user_id)
                ->where('project_id', $projectid)
                ->update(['status' => 1]);
            return redirect('user');
        }

        return redirect('404');
    }

    public function confirm_project_apply($project_name, $user_id){
        $projectid = DB::table('projects')->where('slug', $project_name)->pluck('id');

        if(Auth::user()->userrole == '3'){
            DB::table('project_participants')
                ->where('user_id', $user_id)
                ->where('project_id', $projectid)
                ->update(['status' => 2]);
            return redirect('user');
        }

        return redirect('404');
    }
}

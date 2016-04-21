<?php namespace SocialCreditPointsApp\Http\Controllers;

use DB;
use Auth;
use Request;
use Input;
use SocialCreditPointsApp\Project;
use SocialCreditPointsApp\User;
use Validator;
use Session;
use File;
use Storage;


class ProjectsController extends Controller {

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */

  public function __construct(Project $project, User $user)
  {
    $this->middleware('owner');
    $this->middleware('auth');
    $this->project = $project;
    $user->Session();
    parent::__construct();
    var_dump($project);
  }

  # list all projects of a certain kind
  public function index()
  {
    $projects = DB::table('projects')->where('status', 'bestätigt')->get();
    return view('projects.index', ['projects' => $projects]);
  }
  public function index_draft(){
    $user = DB::table('users')->where('email', Auth::user()->email)->first();
    $userid = $user->id;
    $user_project_drafts = $this->project->where('created_by', $userid)->where('status', 'draft')->get();
    return view('users.project-drafts.index', ['user_project_drafts' => $user_project_drafts]);
  }
  public function index_suggestion(){
    $user = DB::table('users')->where('email', Auth::user()->email)->first();
    $userid = $user->id;
    $user_projects = $this->project->where('created_by', $userid)->where('status', 'unbestätigt')->get();
    return view('users.project-suggestions.index', ['user_projects' => $user_projects]);
  }

  public function create(){
    $tutors  = DB::table('users')->where('userrole', 3)->get();
    $available_tutors = [];
    foreach($tutors as $tutor){
      $available_tutors[$tutor->id] = $tutor->first_name." ".$tutor->last_name;
    }
    return view('projects.create', ['available_tutors' => $available_tutors]);
  }

  /**
  * Store a newly created resource in storage.
  * POST /projects
  *
  * @return Response
  */
  public function store()
  {
    $messages = [
      'required' => 'Bitte füllen Sie das Feld :attribute aus.',
    ];

    $v = Validator::make(Input::all(), [
      'name' => 'required|max:255',
      'tutor' => 'required',
      'participants_capacity' => 'required',
      'scp_reward' => 'required',
      'engagement' => 'required',
      'categories' => 'required',
      'description' => 'required'
    ], $messages);

    if ($v->fails())
    {
      return redirect()->back()->withInput()->withErrors($v->errors());
    }

    $user = DB::table('users')->where('email', Auth::user()->email)->first();
    $userid = $user->id;

    $project = new Project;
    $project->created_by    		= $userid;
    $project->name          		= Input::get('name');
    $project->tutor           		= Input::get('tutor');
    $project->participants_capacity = Input::get('participants_capacity');
    $project->scp_reward    		= Input::get('scp_reward');
    $project->engagement		    = Input::get('engagement');
    $project->categories			= Input::get('categories');
    $project->description			= Input::get('description');

    if(Input::get('draft')) {
      $project->status = 'draft';
    }

    if(Input::get('suggest')) {
      $project->status = 'unbestätigt';
    }

    if(Input::get('insert')) {
      $project->status = 'bestätigt';
    }

    $file = Request::file('storage_id');

    if(!$file == null){
      $extension = $file->getClientOriginalExtension();
      $file->move(public_path().'/user_files', $file->getFilename() . '.' . $extension);
      $project->storage_id = '';
    }

    $image = Request::file('storage_id');
    if(!$image == null){
      $image_extension = $file->getClientOriginalExtension();
      $image->move(public_path().'/user_files', $image->getFilename() . '.' . $image_extension);
      $project->image	= $image->getFilename() . '.' . $image_extension;
    }

    $project->save();

    if(Input::get('draft')) {
      return redirect('user/project-drafts');
    }
    return redirect('user/project-suggestions');
  }

  public function show($project_name){
    $project = Project::where('slug', $project_name)->first();
    $user  = DB::table('users')->where('email', Auth::user()->email)->first();
    $statusiconname = 'ok';
    $participants = $project->getParticipants();

    $project_applied  =DB::table('project_participants')
      ->where('user_id', $user->id)
      ->where('project_id', $project->id)
      ->where('status', 0)
      ->first();

    $project->tutor = $project->tutorName();
    return view('users.projects.show', ['project' => $project, 'participants' => $participants, 'statusiconname' => $statusiconname, 'project_applied' => $project_applied]);
  }
  public function show_suggestion($project_name){
    $project = DB::table('projects')->where('slug', $project_name)->first();
    $user  = DB::table('users')->where('email', Auth::user()->email)->first();

    $project_applied  =DB::table('project_participants')
    ->where('user_id', $user->id)
    ->where('project_id', $project->id)
    ->where('status', 0)
    ->first();

    return view('users.project-suggestions.show', ['project' => $project, 'project_applied' => $project_applied]);
  }
  public function show_draft($project_name){
    $project = DB::table('projects')->where('slug', $project_name)->first();
    return view('users.project-drafts.show', ['project' => $project]);
  }

  public function apply($project_name){
    $project = DB::table('projects')->where('slug', $project_name)->first();
    $student = DB::table('students')->where('user_id', Auth::user()->id)->first();

    DB::table('project_participants')->insert(
    array(
      'project_id' => $project->id,
      'user_id' => $student->id,
      'status' => 0
    ));

    return redirect('projects/' . $project->slug);
  }


  public function withdraw($project_name){
    $project = DB::table('projects')->where('slug', $project_name)->first();
    $student = DB::table('students')->where('user_id', Auth::user()->id)->first();

    DB::table('project_participants')
    ->where('project_id',  $project->id)
    ->where('user_id',  $student->id)
    ->delete();

    return redirect('projects/' . $project->slug);
  }

  // Projects
  public function documentation(){
    return view('users.projects.sgl_documentation');
  }

  public function documentation_show(){
    return view('users.projects.sgl_documentation_show');
  }
  # Project drafts


  public function edit_draft($project_name){
    $this_project = DB::table('projects')->where('slug', $project_name)->first();
    $id = $this_project->id;
    return view('users.project-drafts.edit')
    ->with('project', Project::find($id));
  }

  public function update_draft($project_name){
    $this_project = DB::table('projects')->where('slug', $project_name)->first();
    $project_id = $this_project->id;

    $messages = [
      'required' => 'Bitte füllen Sie das Feld :attribute aus.',
    ];

    $v = Validator::make(Input::all(), [
      'name' => 'required|max:255',
      'tutor' => 'required',
      'participants_capacity' => 'required',
      'scp_reward' => 'required',
      'engagement' => 'required',
      'categories' => 'required',
      'description' => 'required'
    ], $messages);

    if ($v->fails())
    {
      return redirect()->back()->withInput()->withErrors($v->errors());
    }

    $user = DB::table('users')->where('email', Auth::user()->email)->first();
    $userid = $user->id;

    $project = Project::find($project_id);
    $project->created_by    		= $userid;
    $project->name          		= Input::get('name');
    $project->tutor           		= Input::get('tutor');
    $project->participants_capacity = Input::get('participants_capacity');
    $project->scp_reward    		= Input::get('scp_reward');
    $project->engagement		    = Input::get('engagement');
    $project->categories			= Input::get('categories');
    $project->description			= Input::get('description');
    $project->storage_id = '';
    $project->image	= '';
    $project->resluggify();
    $project->save();

    return redirect('user/project-drafts');
  }

  public function release_draft($project_name){
    DB::table('projects')
    ->where('slug', $project_name)
    ->update(array('status' => 'unbestätigt'));
    return redirect('user/project-suggestions');
  }

  public function destroy_draft($project_name){
    DB::table('projects')->where('slug', $project_name)->delete();
    return redirect('user/project-drafts');
  }

  # Project suggestions


  public function edit_suggestion($project_name){
    $this_project = DB::table('projects')->where('slug', $project_name)->first();
    $id = $this_project->id;
    return view('users.project-suggestions.edit')
    ->with('project', Project::find($id));
  }

  public function update_suggestion($project_name){
    $this_project = DB::table('projects')->where('slug', $project_name)->first();
    $project_id = $this_project->id;

    $messages = [
      'required' => 'Bitte füllen Sie das Feld :attribute aus.',
    ];

    $v = Validator::make(Input::all(), [
      'name' => 'required|max:255',
      'tutor' => 'required',
      'participants_capacity' => 'required',
      'scp_reward' => 'required',
      'engagement' => 'required',
      'categories' => 'required',
      'description' => 'required'
    ], $messages);

    if ($v->fails())
    {
      return redirect()->back()->withInput()->withErrors($v->errors());
    }

    $user = DB::table('users')->where('email', Auth::user()->email)->first();
    $userid = $user->id;

    $project = Project::find($project_id);
    $project->created_by    		= $userid;
    $project->name          		= Input::get('name');
    $project->tutor           		= Input::get('tutor');
    $project->participants_capacity = Input::get('participants_capacity');
    $project->scp_reward    		= Input::get('scp_reward');
    $project->engagement		    = Input::get('engagement');
    $project->categories			= Input::get('categories');
    $project->description			= Input::get('description');
    $project->storage_id = '';
    $project->image	= '';
    $project->resluggify();
    $project->save();

    return redirect('user/project-suggestions');
  }

  public function withdraw_suggestion($project_name){
    DB::table('projects')
    ->where('slug', $project_name)
    ->update(array('status' => 'draft'));
    return redirect('user/project-suggestions');
  }
}

<?php namespace SocialCreditPointsApp\Http\Controllers;

use SocialCreditPointsApp\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;
use Input;
use SocialCreditPointsApp\Project;
use Validator;

use Illuminate\Http\Request;

class AjaxController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    #Search
    public function search()
    {
        if (!Request::isMethod('post')) {
            return null;
        }

        $input = Input::get('query');
        $projects = DB::table('projects')->where('projektname', 'LIKE', '%' . $input . '%')->get();
        return $projects;
    }

}

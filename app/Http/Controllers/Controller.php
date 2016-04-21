<?php namespace SocialCreditPointsApp\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use SocialCreditPointsApp\Project;
use DB;
use Auth;
use App;
use View;
use SocialCreditPointsApp\User;


abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;
	public function __construct()
	{
		$this->shareNotifications();
	}

	protected function shareNotifications(){
	
		if(Auth::check()){
			$projects = DB::table('projects')->where('seen',false)->where('created_by',Auth::user()->id)->get();
			$notifications = [];
			foreach($projects as $project){
				$notification = array(
						"subject" => $project->name,
						"state" => $project->status,
						"url" => App::make('url')->to('/')."/projects/".$project->slug,
						"icon" => $this->getIconName($project->status)
						);
				array_push($notifications, $notification);
			}
			View::share('notifications',$notifications);
		}
	}
	protected function getIconName($status){
			$icon_name_map = array(
				'draft' =>'ok',
				'abgelehnt' =>'remove',
				'bestätigt' =>'ok',
				'unbestätigt'=>'hourglass',
				'abgeschlossen'=>'ban-circle');
		return $icon_name_map[$status];
	}
}

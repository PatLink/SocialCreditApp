<?php namespace SocialCreditPointsApp\Http\Middleware;

use Illuminate\Http\RedirectResponse;
use Closure;
use SocialCreditPointsApp\Project;
use Auth;

class OwnershipMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//check if we are even hitting the projects section
		if(strpos($request->segment(2),'project') > -1 && $request->segment(3) != null | 'reject' | 'confirm'){
			//get params
			$slug = $request->segment(3);
			$project = Project::where('slug',$slug)->first();
			//make sure we don't hit nuffin
			if($project == null){
				return new RedirectResponse(url('/dashboard'));
			}
			$owner_id = $project->created_by;
			$status = $project->status;
			$user_id = Auth::user()->id;

			//yay or nay
			if($owner_id == $user_id && $status == "draft" && $request->segment(3) == "destroy"){
				return $next($request);
			}
			if($owner_id == $user_id | $status == "bestätigt"){
				return $next($request);
			}
			return new RedirectResponse(url('/dashboard'));
		}

		return $next($request);

		// 1. get resource
		// 2. check if owner
		// 3. check if approved
	}

}

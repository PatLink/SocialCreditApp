<?php namespace SocialCreditPointsApp;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Auth;
use DB;
use Session;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * For soft deletes
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	/**
	 * Hash the users password
	 *
	 * @param $value
	 */
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = \Hash::make($value);
	}

	public function Session(){
		# Save Social Credit Point Score for
		// the current year in Session
		if(Auth::user() != null && Auth::user()->userrole == 1) {

			$student = DB::table('students')
				->where('user_id', Auth::user()->id)
				->first();

			$projektids = DB::table('project_participants')
				->where('user_id', $student->user_id)
				->get();

			$scp_year = DB::table('academic_years')
				->where('start_date', '<=', date("Y-m-d"))
				->where('end_date', '>=', date("Y-m-d"))
				->where('course_id', $student->course_id)
				->first();

			$student_scps = 0;
			foreach ($projektids as $projectid) {
				$projects_scps = DB::table('projects')
					->where('id', $projectid->project_id)
					->whereBetween('end_date',array($scp_year->start_date,$scp_year->end_date))
					->where('status', 'abgeschlossen')
					->get();

				foreach ($projects_scps as $projects_scp) {
					$student_scps += $projects_scp->scp_reward;
				}
			}

			Session::put('student_scps', $student_scps);
			Session::put('scp_current_year', $scp_year->workload);
            $percentage = $student_scps / $scp_year->workload * 100;
            Session::put('scp_percentage', $percentage);
		}
	}
}

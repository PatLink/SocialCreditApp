<?php namespace SocialCreditPointsApp;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use DB;

class Project extends Model implements SluggableInterface{

	protected $table = 'projects';

    use SluggableTrait;

    protected $sluggable = array(
        'build_from' => 'name',
        'save_to'    => 'slug',
    );

    public $timestamps = true;
    public function getParticipants(){
        $participations = DB::table('project_participants')->where('project_id', $this->id)->get();
        $users = DB::table('users')->get();
        $participant_ids = array_map(function($item){
            return $item->user_id;
        }, $participations);
	$participants = array_filter($users, function($item) use($participant_ids){
		return is_int(array_search($item->id, $participant_ids)) ? $item : null;
		});
        return $participants;
    }
    public function tutorName(){
        $rawTutor = DB::table('users')->where('id', $this->tutor)->first();
        return $rawTutor->first_name." ".$rawTutor->last_name;
    }
}

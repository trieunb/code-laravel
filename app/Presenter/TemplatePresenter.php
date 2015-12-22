<?php
namespace App\Presenter;

use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Reference;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserQuestion;
use App\Models\UserSkill;
use App\Models\UserWorkHistory;
use Laracasts\Presenter\Presenter;

class TemplatePresenter extends Presenter
{
	public function createMenuProfile($id, $section)
	{
		try {
			$html = '';
		 	switch ($section) {
	            case 'education':
	            	foreach (UserEducation::whereUserId($id)->get() as $key => $education) {
	            		$html .= '<optgroup label="Education .'.($key + 1).'">"';
	            		$html .= '<option>Title:'.$education->title.'</option>';
	            		$html .= '<option>School:'.$education->school_name.'</option>';
	            		$html .= '<option>Start:'.$education->start.'</option>';
	            		$html .= '<option>End:'.$education->end.'</option>';
	            		$html .= '<option>Degree:'.$education->degree.'</option>';
	            		$html .= '<option>Result:'.$education->result.'</option>';
	            		$html .= '</ul>';
	            		$html .= '</div>';
	            		$html .= '</optgroup>';
	            	}

	                return $html;
	                break;
                case 'skill': 
                	foreach (UserSkill::whereUserId($id)->get() as $key => $skill) {
                		$html .= '<optgroup label="Skills .'.($key + 1).'">"';
	            		$html .= '<option>Name:'.$skill->name.'</option>';
	            		$html .= '<option>Experience:'.$skill->experience.'</option>';
	            		$html .= '</optgroup>';
                	}
	            case 'personal_test':
	            	foreach (UserQuestion::whereUserId($id)->get() as $key => $personal_test) {
	            		$html .= '<optgroup label="Personal Test .'.($key + 1).'">"';
	            		$html .= '<option>Content:'.$personal_test->content.'</option>';
	            		$html .= '<option>Point:'.$personal_test->point.'</option>';
	            		$html .= '</optgroup>';
	            	}
	            	
	                return $html;
	                break;
	            case 'work':
	            	foreach (UserWorkHistory::whereUserId($id)->get() as $key => $work) {
	            		$html .= '<optgroup label="Experience .'.($key + 1).'">"';
	            		$html .= '<option>Company:'.$work->company.'</option>';
	            		$html .= '<option>Title:'.$work->sub_title.'</option>';
	            		$html .= '<option>Start:'.$work->start.'</option>';
	            		$html .= '<option>End:'.$work->end.'</option>';
	            		$html .= '<option>Job title::'.$work->job_title.'</option>';
	            		$html .= '<option>Job description:'.$work->job_description.'</option>';
	            		$html .= '</optgroup>';
	            	}
	            	
	                return $html;
	                break;
	            case 'reference':
	            	foreach (Reference::whereUserId($id)->get() as $key => $reference) {
	            		$html .= '<optgroup label="Reference .'.($key + 1).'">"';
	            		$html .= '<option>Reference:'.$reference->reference.'</option>';
	            		$html .= '<option>Content::'.$reference->content.'</option>';
	            		$html .= '</optgroup>';
	            	}
	            	
	                return $html;
	                break;
	            case 'key_qualification':
	            	foreach (Qualification::whereUserId($id)->get() as $key => $qualification) {
	            		$html .= '<optgroup label="Qualification .'.($key + 1).'">"';
	            		$html .= '<option>Content:'.$qualification->content.'</option>';
	            		$html .= '</optgroup>';
	            	}
	                
	                return $html;
	                break;
	            case 'objective':
	            	foreach (Objective::whereUserId($id)->get() as $key => $objective) {
	            		$html .= '<optgroup label="Objective .'.($key + 1).'">"';
	            		$html .= '<option>Title:'.$objective->title.'</option>';
	            		$html .= '<option>Content:'.$objective->content.'</option>';
	            		$html .= '</optgroup>';
	            	}
	                
	                return $html;
	                break;
	            case 'name': 
	            	$html .= '<optgroup label="Name">';
	                $html .= '<option>'.User::findOrFail($id)->present()->name().'</option>';
	        		$html .= '</optgroup>';

	                return $html;
	                break;
	            case 'profile_website':
	            case 'linkedin':
	            	$html .= '<optgroup label="Link Profile">';
	                $html .= '<option>'.User::findOrFail($id)->link_profile.'</option>';
	        		$html .= '</optgroup>';

	                return $html;
	                break;
	            case 'availability':
	                $status = null;
	                foreach (\Setting::get('user_status') as $k => $v) {
	                    if ($v['id'] == User::findOrFail($id)->status)
	                        $status = $v;
	                }
	                $html .= '<optgroup label="Availability">';
	                $html .= '<option>'.$status.'</option>';
	        		$html .= '</optgroup>';

	                return $html;
	                break;
	            case 'phone':
	            	$html .= '<optgroup label="Mobile Phone">';
	                $html .= '<option>'.User::findOrFail($id)->mobile_phone.'</option>';
	        		$html .= '</optgroup>';

	                return $html;
	                break;
	            default:
	            	$html .= '<optgroup label="'.ucfirst($section).'">';
	                $html .= '<option>'.User::findOrFail($id)->$section.'</option>';
	        		$html .= '</optgroup>';

	                return $html;
	                break;
	        }
		} catch (\Exception $e) {
			return response('Section Not found', 404);
		}
		
	}
}
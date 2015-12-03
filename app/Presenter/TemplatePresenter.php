<?php
namespace App\Presenter;

use App\Models\Objective;
use App\Models\Qualification;
use App\Models\Reference;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserWorkHistory;
use Laracasts\Presenter\Presenter;

class TemplatePresenter extends Presenter
{
	public function createMenuProfile($id, $section)
	{
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
                // return json_encode(['data' => ['education' => UserEducation::whereUserId($id)->get()]]);
                break;
            case 'work':
            	foreach (UserWorkHistory::whereUserId($id)->get() as $key => $work) {
            		$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Work .'.($key + 1).'
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
            		$html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
            		$html .= '<ul class="list list-unstyled">';
            		$html .= '<li>Company:'.$work->company.'</li>';
            		$html .= '<li>Title:'.$work->sub_title.'</li>';
            		$html .= '<li>Start:'.$work->start.'</li>';
            		$html .= '<li>End:'.$work->end.'</li>';
            		$html .= '<li>Job title::'.$work->job_title.'</li>';
            		$html .= '<li>Job description:'.$work->job_description.'</li>';
            		$html .= '</ul>';
            		$html .= '</div>';
            		$html .= '</li>';
            	}
            	
                return $html;
                // return json_encode(['data' => ['work' => UserWorkHistory::whereUserId($id)->get()]]);
                break;
            case 'reference':
            	foreach (Reference::whereUserId($id)->get() as $key => $reference) {
            		$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Reference .'.($key + 1).'
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
            		$html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
            		$html .= '<ul class="list list-unstyled">';
            		$html .= '<li>Reference:'.$reference->reference.'</li>';
            		$html .= '<li>Content::'.$reference->content.'</li>';
            		$html .= '</ul>';
            		$html .= '</div>';
            		$html .= '</li>';
            	}
            	
                return $html;
                // return json_encode(['data' => ['reference' => Reference::whereUserId($id)->get()]]);
                break;
            case 'key_qualification':
            	foreach (Qualification::whereUserId($id)->get() as $key => $qualification) {
            		$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Qualification .'.($key + 1).'
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
            		$html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
            		$html .= '<ul class="list list-unstyled">';
            		$html .= '<li>Content:'.$qualification->content.'</li>';
            		$html .= '</ul>';
            		$html .= '</div>';
            		$html .= '</li>';
            	}
                
                return $html;
                // return json_encode(['data' => ['key_qualification' => Qualification::whereUserId($id)->get()]]);
                break;
            case 'objective':
            	foreach (Objective::whereUserId($id)->get() as $key => $objective) {
            		$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Objective .'.($key + 1).'
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
            		$html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
            		$html .= '<ul class="list list-unstyled">';
            		$html .= '<li>Title:'.$objective->title.'</li>';
            		$html .= '<li>Content:'.$objective->content.'</li>';
            		$html .= '</ul>';
            		$html .= '</div>';
            		$html .= '</li>';
            	}
                
                return $html;

                // return json_encode(['data' => ['objective' => Objective::whereUserId($id)->get()]]);
                break;
            case 'name': 
            	$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Name
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
        		$html .= '<ul class="list list-unstyled">';
                $html .= '<li>'.User::findOrFail($id)->present()->name().'</li>';
                $html .= '</ul>';
        		$html .= '</div>';
        		$html .= '</li>';

                return $html;
                // return json_encode(['data' => $this->getById($id)->present()->name()]);
                break;
            case 'profile_website':
            case 'linkedin':
            	$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Name
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
        		$html .= '<ul class="list list-unstyled">';
                $html .= '<li>'.User::findOrFail($id)->link_profile.'</li>';
                $html .= '</ul>';
        		$html .= '</div>';
        		$html .= '</li>';

                return $html;
                // return json_encode(['data' => $this->getById($id)->link_profile]);
                break;
            case 'availability':
                $status = null;
                foreach (\Setting::get('user_status') as $k => $v) {
                    if ($v['id'] == $this->getById($id)->status)
                        $status = $v;
                }
                $html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Name
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
        		$html .= '<ul class="list list-unstyled">';
                $html .= '<li>'.$status.'</li>';
                $html .= '</ul>';
        		$html .= '</div>';
        		$html .= '</li>';

                return $html;
                // return json_encode(['data' => $status]);
                break;
            case 'phone':
            	$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Name
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
        		$html .= '<ul class="list list-unstyled">';
                $html .= '<li>'.User::findOrFail($id)->mobile_phone.'</li>';
                $html .= '</ul>';
        		$html .= '</div>';
        		$html .= '</li>';

                return $html;
                // return json_encode(['data' => $this->getById($id)->mobile_phone]);
                break;
            default:
            	$html .= '<li><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown">
                              Name
                              <span class="arrow right pull-right"><i class="fa fa-chevron-right"></i></span>
                            </a>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dLabel">';
        		$html .= '<ul class="list list-unstyled">';
                $html .= '<li>'.User::findOrFail($id)->pluck($section).'</li>';
                $html .= '</ul>';
        		$html .= '</div>';
        		$html .= '</li>';

                return $html;
                // return json_encode(['data' =>$this->getById($id)->pluck($section)]);
                break;
        }
	}
}
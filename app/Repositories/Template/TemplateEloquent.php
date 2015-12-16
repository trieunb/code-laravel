<?php
namespace App\Repositories\Template;

use App\Events\RenderImageAfterCreateTemplate;
use App\Models\Template;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\Template\TemplateInterface;
use Khill\Lavacharts\Lavacharts;
use Lava;
use App\Models\User;
use App\Models\TemplateMarket;
use Carbon\Carbon;
use DB;

class TemplateEloquent extends AbstractDefineMethodRepository implements TemplateInterface
{
    use SaveFromApiTrait;

	protected $model;

    /**
     * Fields for update data
     * @var $field_work_save
     */
    protected $field_work_save = ['user_id', 'title', 'content',
    'image', 'type'];

	public function __construct(Template $template)
	{
		$this->model = $template;
	}
    /**
     * create 
     * @param $date
     * @return mixed
     */
    public function saveTemplate($data, $user_id)
    {
        if (count($data) == 1) {
            $this->saveOneRecord($data, $user_id);
        } 

        $ids = [];
        $dataPrepareForCreate = [];
        foreach ($data as $value) {
            if ($value['id'] != null && $value['id'] != '') {
                $ids[] = $value['id'];
            } else {
                $dataPrepareForCreate[] = $value;
            }
        }

        if (count($dataPrepareForCreate) == 1) 
            $this->saveOneRecord($dataPrepareForCreate, $user_id);
        else 
            $this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
    }

    /**
     * Save Or Update One Record
     * @param  mixed $data    
     * @param  int $user_id 
     * @return mixed          
     */
    public function saveOneRecord($data, $user_id)
    {
        $dataPrepareSave = isset($data[0]) ? isset($data) : $data;
        $template = isset($dataPrepareSave['id']) ? $this->getById($dataPrepareSave['id']) : new Template;
        if (!isset($dataPrepareSave['id']) || $dataPrepareSave['id'] == null) $template->user_id = $user_id;

        $template->title = $dataPrepareSave['title'];
        $template->content = $dataPrepareSave['content'];
        $template->image = $dataPrepareSave['image'];
        $template->type = $dataPrepareSave['type'];
        $template->source_file_pdf = $dataPrepareSave['source_file_pdf'];
        Template::makeSlug($template, false);

        return $template->save();
    }

    /**
     * Get template for user
     * @param  int $id      
     * @param  int $user_id 
     * @return mixed          
     */
    public function forUser($id, $user_id)
    {
        return $this->model->whereUserId($user_id)->findOrFail($id);

    }

    /**
     * Create template
     * @param  int $user_id  
     * @param  mixed $request
     * @param  int $type 
     * @return mixed           
     */
    public function createTemplate($user_id, $request)
    {
        $template = new Template;
        $template->user_id = $user_id;
        $template->title = $request->get('title');
        $template->content = $request->get('content') != '' 
            ? preg_replace('/\t|\n+/', '', $request->get('content'))
            : '<div contenteditable="true></div>';
        $template->type = $request->get('type');
        Template::makeSlug($template, false);
        
        return $template->save() ? $template : null;
    }

    /**
     * Edit template
     * @param  int $id      primary key
     * @param  int $user_id   
     * @param  string $section   
     * @param  mixed $request 
     * @return mixed          
     */
    public function editTemplate($id, $user_id, $section, $request)
    {
        $template = $this->forUser($id, $user_id);
        if ( ! isset($template->section[$section])) return;
        $sec = $template->section;
        $tmp = '';
        if ($section == 'availability') {

            foreach (\Setting::get('user_status') as $status) {
                if ($status['id'] == $request->get('content'))
                    $tmp = $template->type == 2 
                        ? '<div class="availability content-box" contenteditable="true">'
                            .'<div class="header-title" style="color: red;font-weight:600;padding:15px;">'
                            .'<span>Availability</span></div>'
                            .'<div class="box" style="background: #f3f3f3;padding: 15px;border-top: 3px solid #D8D8D8;border-bottom: 3px solid #D8D8D8;">'
                            .'<p>'.$status['value'].'</p></div></div>'
                        : '<div class="availability" contenteditable="true"><h3 style="font-weight:600">Availability</h3><p style="font-weight:600">'.$status['value'].'</p></div>';
            }

            $user = \App\Models\User::find($user_id);
            $user->status = $request->get('content');

            if ( ! $user->save()) {
                return null;    
            }
            
            $data = editSection($section, $tmp, $template->content);
            
            
        } else {
            $data = editSection($section, $request->get('content'), $template->content);
        }

        $template->content = $data['content'];
        $template->section = array_set($sec, $section, $data['section']);
        
        return $template->save() ? $template : null;
    }

    public function editPhoto($id, $user_id, $file)
    {
        $template = $this->getById($id);

        if ( ! isset($template->section['photo'])) return null;

        $user = \App\Models\User::find($user_id);

        if ($user->avatar['origin'] == null || $user->avatar['origin'] == '') return null;

        $user->avatar = \App\Models\User::uploadAvatar($file);
      
        if ( !$user->save()) return;
        
        $data = editSection('photo', 
            '<div class="photo"><img src="'.asset($user->avatar['origin']).'" width="100%"></div>',
            $template->content);
        $sec = $template->section;

        $template->content = $data['content'];
        $template->section = array_set($sec, 'photo', $data['section']);

        return $template->save() ? ['avatar' => $user->avatar['origin'], 'template' => $template ]: null;
    }

    /**
     * Create template basic
     * @param  int $user_id 
     * @param  array $data 
     * @return mixed          
     */
    public function createTemplateBasic($user_id, $data)
    {
        $template = $this->model->whereUserId($user_id)
            ->whereType('2')
            ->first();

        if ( ! $template) {
            $template = new Template();
            $template->user_id = $user_id;
            $template->title = "Basic Template";
            $template->type = 2;
            Template::makeSlug($template);
        }

        $template->content = $data['content'];
        unset($data['content']);
        $template->section = $data;
        
        return $template->save() ? $template : null;
    }

    /**
     * Delete template
     * @param  int $id
     * @param  int $user_id
     * @param  string $content 
     * @return mixed          
     */
    public function deleteTemplate($id, $user_id)
    {
        $template = $this->forUser($id, $user_id);
       
        return $template->delete();
    }

     /**
     * Create template from market place
     * @param  array $data 
     * @return bool       
     */
    public function createTemplateFromMarket(array $data)
    {
        $template = new Template;
        $template->user_id = $data['user_id'];
        $template->title = $data['title'];
        $template->content = $data['content'];
        $template->image = $data['image'] != null && $data['image'] != '' ? $data['image']: ['origin' => '', 'thumb' => ''];
        $template->type = 0;
        $template->source_file_pdf = $data['source_file_pdf'] != null ? $data['source_file_pdf']: '';
        $template->version = $data['version'];
        $template->clone = $data['clone'];
        $template->section = $data['section'];
        Template::makeSlug($template, false);

        return $template->save();
    }

     /**
     * Apply data into infomation section
     * @param  Template $template 
     * @param  string $section 
     * @param  array $data     
     * @return bool           
     */
    public function applyForInfo($template, $section, $data)
    {
    
        $template->content = $data['content'];
        $sec = $template->section;

        $template->section = array_set($sec, $section, $data['section']);

        return $template->save() 
            ? ['section' => $section == 'availability' ? $template->user->status : $data['section'], 'template' => $template] 
            : false;
    }

    /**
     * Edit full screen template
     * @param  int $id      
     * @param  int $user_id 
     * @param  mixed $request 
     * @return bool          
     */
    public function editFullScreenTempalte($id, $user_id, $request)
    {
        $template = $this->forUser($id, $user_id);
        $sections = createClassSection();
        $result = createSection($request->get('content'), $sections);
        $template->content = $request->get('content');
        unset($result['content']);
        $template->section = $result;
        
        return $template->save()
            ? event(new RenderImageAfterCreateTemplate($template->id, $template->content, $template->slug))
            : null;
    }

    public function getMyTemplates($user_id, $page, $search)
    {
        $templates = $this->model->whereUserId($user_id);
        $offset = ($page - 1) * 10;
        if ($search != null && $search != '') {
            $templates->where('title', 'LIKE', "%{$search}%");
        }
        return $templates->skip($offset)
                ->take(10)
                ->get();
    }

    public function reportTemplateMonth()
    {
        $lava = new Lavacharts;
        $templateTable = $lava->DataTable();

        $templateTable->addStringColumn('Date of Month')
                    ->addNumberColumn('Templates');

        $templates_m = Template::select('*', DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(id) AS count'))->groupBy('month')->orderBy('month', 'ASC')->get();

        foreach ($templates_m as $temp_m) {
            $rowData = array(
                date_format($temp_m->created_at, 'Y-m'), $temp_m->count
            );
            $templateTable->addRow($rowData);
        }

        $chart_month = $lava->ColumnChart('TemplateChart')->setOptions([
                'datatable' => $templateTable,
                'title' => 'Month',
                'titleTextStyle' => $lava->TextStyle(array(
                        'color' => '#eb6b2c',
                        'fontSize' => 14
                      ))
            ]);

        return $lava->render('ColumnChart', 'TemplateChart', 'chart_month', true);
    }

    public function reportTemplateGender()
    {
        $lava = new Lavacharts;
        $templateTable = $lava->DataTable();
        $templateTable->addStringColumn('Gender')
                    ->addNumberColumn('Templates');

        $templates_m = User::select('*', DB::raw('COUNT(id) AS count'))->with('templates')->groupBy('gender')->orderBy('created_at', 'ASC')->get();
        foreach ($templates_m as $temp_m) {

            $templates_c = Template::with(['user' => function($q) use ($temp_m) {
                $q->whereGender($temp_m->gender);
            }])->get();

            $count = 0;
            foreach ($templates_c as $value) {
                if(!is_null($value->user)) $count ++;   
            }

            $gender = '';             
            switch ($temp_m->gender) {
                case 0:
                    $gender = 'Male';
                    break;
                case 1:
                    $gender = 'Female';
                    break;
                case 2:
                    $gender = 'Other';
                    break;
                
                default:
                    $gender = 'Other';
                    break;
            }
            $rowData = array(
                $gender, $count
            );
            
            $templateTable->addRow($rowData);
        }

        $chart_gender = $lava->ColumnChart('TemplateChart')->setOptions([
                'datatable' => $templateTable,
                'title' => 'Gender',
                'titleTextStyle' => $lava->TextStyle(array(
                        'color' => '#eb6b2c',
                        'fontSize' => 14
                      )),
                'width' => 988
            ]);

        return $lava->render('ColumnChart', 'TemplateChart', 'chart_gender', true);
    }
    
}
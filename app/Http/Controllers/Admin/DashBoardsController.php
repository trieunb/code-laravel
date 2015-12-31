<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Template;
use App\Models\TemplateMarket;
use App\Models\Invoice;
use App\Models\Job;
use Auth;
use Validator;

class DashBoardsController extends Controller
{
    private $user;
    private $template;
    private $resume;

    public function __construct(UserInterface $user, 
        TemplateMarketInterface $template, 
        TemplateInterface $resume)
    {
        $this->middleware('csrf');
        $this->user = $user;
        $this->template = $template;
        $this->resume = $resume;
    }

	public function index(Request $request)
	{
        $users = User::whereDoesntHave('roles', function($q) {
            $q->where('roles.slug', 'admin');
        });

        $last_users = $users->orderBy('created_at', 'DESC')->take(10)->get();
        $count_user = $users->count();
        $count_template = TemplateMarket::count();
        $resumes = Template::where('type', '<>', 2);
        $last_resume = $resumes->orderBy('created_at', 'DESC')->take(10)->get();
        $count_resume = Invoice::count();
        $count_job = Job::count();
        
		return view('admin.dashboard.index', 
            compact('count_user', 
                'last_users', 
                'count_template', 
                'last_resume', 
                'count_resume', 
                'count_job')
            );
	}

    public function getDetailResume($id)
    {
        $resume = $this->resume->getById($id);
        return view('admin.resume.detail', compact('resume'));
    }

    public function getLogin()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        
        $remember = $request->input('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect('admin/login')
            ->withErrors(['message' => 'Wrong email or password.'])
            ->withInput();
        
    }

    public function getLogout(Request $request)
    {
        Auth::logout();

        return redirect('admin/login');
    }
} 
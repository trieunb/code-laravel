<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateQuestionFormRequest;
use App\Repositories\Question\QuestionEloquent;
use App\Repositories\Question\QuestionInterface;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * QuestionInterface
     * @var $question
     */
    private $question;
    private $user;

    public function __construct(QuestionInterface $question,UserInterface $user)
    {
        $this->question = $question;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.question.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuestionFormRequest $request)
    {
        return $this->question->saveFromAdminArea($request)
            ? redirect()->route('admin.question.get.index')->with('message', 'Create Question successfully!')
            : redirect()->back()->with('message', 'Error when create question!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = $this->question->getById($id);
        
        return view('admin.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function answer($id, UserInterface $userInterface)
    {
        $user = $userInterface->getById($id);

        $answers = $userInterface->answerForUser($id);
        
        return view('admin.question.answer', compact('user', 'answers'));
    }
}

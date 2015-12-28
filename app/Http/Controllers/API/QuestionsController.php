<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Question\QuestionInterface;
use App\Repositories\UserQuestion\UserQuestionInterface;
use Illuminate\Http\Request;
use App\Models\UserQuestion;
use App\Models\Question;

class QuestionsController extends Controller
{
    /**
     * QuestionInterface
     * @var $question
     */
    private $question;

    /**
     * UserQuestionInterface
     * @var $question
     */
    private $user_question;

    public function __construct(QuestionInterface $question, UserQuestionInterface $user_question)
    {
        $this->question = $question;
        $this->user_question = $user_question;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));  
        $user_answers = $this->user_question->getDataWhereClause('user_id', '=', $user->id);
        if (count($user_answers) > 0) {
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'data' => $user_answers
            ]);
        } else {
            foreach ($this->question->getQuestions() as  $question) {
                $data[] = [
                    'question_id' => $question['id'],
                    'user_id' => $user->id,
                    'content' => $question['content'],
                    'point' => $question['point']
                ];
            }
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'data' => $data
            ]);
        }  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return $this->question->delete($id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function showDataTableForAdmin()
    {
        return $this->question->dataTable();
    }

    public function postEditAdmin(Request $request)
    {
        $request->merge(array_map('trim', $request->all()));
        $ids = $request->get('id');
        UserQuestion::where('question_id', $ids)
                ->update(['content' => $request->content]);
        return $this->question->saveFromAdminArea($request)
            ? redirect()->route('admin.question.get.index')->with('message', 'Updated Question successfully!')
            : redirect()->back()->with('message', 'Error when update question!');
    }

    public function postAnswerOfUser(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $user_answers = $this->user_question->getDataWhereClause('user_id', '=', $user->id);
       if (count($user_answers) > 0) {
        foreach ($request->get('answers') as $value) {
            $question_id = [
                'question_id' => $value['question_id']
            ];
            UserQuestion::where('question_id', $question_id)
                ->where('user_id', $user->id)
                ->update(['point' => $value['point']]);
        }
        } else {
            foreach ($request->get('answers') as $value) {
                $data[] = [
                    'question_id' => $value['question_id'],
                    'user_id' => $user->id,
                    'point' => $value['point'],
                ] ;
            }
            $this->user_question->saveUserAnswer($data, $user->id);
        }
        return response()->json([ 'status_code' => 200, 'status' => true ]); 
    }
}

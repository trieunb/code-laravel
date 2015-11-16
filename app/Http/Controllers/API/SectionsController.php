<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\Section\SectionInterface;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    /**
     * SectionInterface
     * @var $section
     */
    private $section;

    public function __construct(SectionInterface $section)
    {
        $this->section = $section;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $section = $this->section->forUser($id, \Auth::user()->id);

        foreach ($section->section as $k => $v) {

            switch ($v) {

                case 'name':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['name'] = '';
                    unset($section->section[$k]);
                    break;
                case 'address':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['address'] = 'Address';
                    unset($section->section[$k]);
                    break;
                case 'photo':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['photo'] = 'Photos';
                    unset($section->section[$k]);
                    break;
                case 'email':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['email'] = 'Email Address';
                    unset($section->section[$k]);
                    break;
                case 'profile_website':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['profile_website'] = 'My Profile Website';
                    unset($section->section[$k]);
                    break;
                case 'linkedin':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['linkedin'] = 'My LinkedIn Profile';
                    unset($section->section[$k]);
                    break;
                case 'phone':
                    $section->section['contact'] = 'Contact Information';
                    $section->section['contact']['phone'] = 'Phone Number';
                    unset($section->section[$k]);
                    break;
                default:
                    $section->section['contact'] = 'Contact Information';
                    break;
            }
        }
        return view('api.section.section', compact('section'));
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
        //
    }

    public function getNames()
    {
        return response()->json(['data' => $this->section->getNameSections()]);
    }
}

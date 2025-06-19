<?php

namespace App\Http\Controllers;

use App\Models\ExperienceUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExperienceUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try { 
            
            // Validasi data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'review' => 'required|string|max:1000',
                'rating' => 'required|integer|min:1|max:5',
            ]); 
            
            // Simpan data
            $experienceUser = ExperienceUser::create([
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'name' => $request->name,
                'review' => $request->review,
                'star' => $request->rating,
            ]);

            return redirect()->back()->with('success', 'Thank you for sharing your experience! Your review has been submitted successfully.');
      
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to submit your review. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExperienceUser  $experienceUser
     * @return \Illuminate\Http\Response
     */
    public function show(ExperienceUser $experienceUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExperienceUser  $experienceUser
     * @return \Illuminate\Http\Response
     */
    public function edit(ExperienceUser $experienceUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExperienceUser  $experienceUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExperienceUser $experienceUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExperienceUser  $experienceUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExperienceUser $experienceUser)
    {
        //
    }

    /**
     * Test method untuk berbagai jenis session alerts
     */
    public function testAlert(Request $request)
    {
        $type = $request->query('type', 'success');
        
        switch ($type) {
            case 'success':
                return redirect()->back()->with('success', 'This is a test success message! Everything worked perfectly.');
                
            case 'error':
                return redirect()->back()->with('error', 'This is a test error message! Something went wrong.');
                
            case 'warning':
                return redirect()->back()->with('warning', 'This is a test warning message! Please be careful.');
                
            case 'info':
                return redirect()->back()->with('info', 'This is a test info message! Here is some information.');
                
            case 'validation':
                return redirect()->back()->withErrors([
                    'test_field' => 'The test field is required.',
                    'email' => 'Please enter a valid email address.',
                    'number' => 'The number must be at least 10.'
                ])->withInput();
                
            default:
                return redirect()->back()->with('info', 'Unknown test type.');
        }
    }
}

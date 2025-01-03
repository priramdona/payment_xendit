<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This method will show our home page
    public function index() {

        // $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();

        // $newCategories = Category::where('status',1)->orderBy('name','ASC')->get();

        // $featuredJobs = Job::where('status',1)
        //                 ->orderBy('created_at','DESC')
        //                 ->with('applications')
        //                 ->with('jobType')
        //                 ->where('isFeatured',1)->take(10)->get();

        // $latestJobs = Job::where('status',1)
        //                 ->with('jobType')
        //                 ->with('applications')
        //                 ->orderBy('created_at','DESC')
        //                 ->take(10)->get();

        // $countProgram = Job::where('status',1)
        //                 ->count();

        // $countSaving = JobApplication::where('type', 'SAVING')
        //                 ->where('status','Paid')
        //                 ->count();

        // $amountSaving = JobApplication::where('type', 'SAVING')
        //                 ->where('status','Paid')
        //                 ->sum('amount');

        // $amountDonasi = JobApplication::where('type', 'DONASI')
        //                 ->where('status','Paid')
        //                 ->sum('amount');

        // $amountProgram = JobApplication::where('type', 'PROGRAM')
        //                 ->where('status','Paid')
        //                 ->sum('amount');

        // $applications = JobApplication::with('user')
        //                 ->where('status','Paid')
        //                 ->orderBy('created_at','DESC')
        //                 ->take(10)->get();

        // $applications10 = JobApplication::with('user')
        //                 ->where('status','Paid')
        //                 ->orderBy('amount','DESC')
        //                 ->take(10)->get();

        // return view('front.home',[
        //     'categories' => $categories,
        //     'featuredJobs' => $featuredJobs,
        //     'latestJobs' => $latestJobs,
        //     'newCategories' => $newCategories,
        //     'applications' => $applications,
        //     'applications10' => $applications10,
        //     'amountProgram' => $amountProgram,
        //     'countProgram' => $countProgram,
        //     'countSaving' => $countSaving,
        //     'amountSaving' => $amountSaving,
        //     'amountDonasi' => $amountDonasi,
        // ]);

        return redirect()->route('payment.method', 'SAVING');
    }
}

<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\PaymentMethod;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;
use Milon\Barcode\Facades\DNS2DFacade;
use Milon\Barcode\Facades\DNS1DFacade;
use Intervention\Image\ImageManagerStatic as Image;
class AccountController extends Controller
{
    // This method will show user registration page
    public function registration() {
        return view('front.account.registration');
    }
    public function contactus() {
        return view('front.contactus');
    }
    public function contacts()
    {
        $contacts = Contact::all();
        return view('contact.contacts',compact('contacts'));
    }
    public function sendcontactus(Request $request) {


        $validator = Validator::make($request->all(),[
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $failedRules = $validator->failed();

            if (isset($failedRules['fname'])) {
                return redirect()->back()
                ->withErrors('Silakan cek Nama Depan')
                ->withInput();
            }

            if (isset($failedRules['lname'])) {
                return redirect()->back()
                ->withErrors('Silakan cek Nama Belakang')
                ->withInput();
            }

            if (isset($failedRules['email'])) {
                return redirect()->back()
                ->withErrors('Silakan cek Email')
                ->withInput();
            }

            if (isset($failedRules['subject'])) {
                return redirect()->back()
                ->withErrors('Silakan cek Judul')
                ->withInput();
            }
            if (isset($failedRules['message'])) {
                return redirect()->back()
                ->withErrors('Silakan cek Pesan')
                ->withInput();
            }
        }

        $contact = new Contact();
        $contact->fname = $request->fname;
        $contact->lname = $request->lname;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        // Optionally, you can redirect the user to a thank you page or any other page

        $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();

        $newCategories = Category::where('status',1)->orderBy('name','ASC')->get();

        $featuredJobs = Job::where('status',1)
                        ->orderBy('created_at','DESC')
                        ->with('applications')
                        ->with('jobType')
                        ->where('isFeatured',1)->take(10)->get();

        $latestJobs = Job::where('status',1)
                        ->with('jobType')
                        ->with('applications')
                        ->orderBy('created_at','DESC')
                        ->take(10)->get();

        $countProgram = Job::where('status',1)
                        ->count();

        $countSaving = JobApplication::where('type', 'SAVING')
                        ->count();

        $amountSaving = JobApplication::where('type', 'SAVING')
                        ->sum('amount');

        $amountProgram = JobApplication::where('type', 'PROGRAM')
                        ->sum('amount');

        $applications = JobApplication::with('user')
                        ->orderBy('created_at','DESC')
                        ->take(10)->get();

        $applications10 = JobApplication::with('user')
                        ->orderBy('amount','DESC')
                        ->take(10)->get();

        return view('front.home',[
            'categories' => $categories,
            'featuredJobs' => $featuredJobs,
            'latestJobs' => $latestJobs,
            'newCategories' => $newCategories,
            'applications' => $applications,
            'applications10' => $applications10,
            'amountProgram' => $amountProgram,
            'countProgram' => $countProgram,
            'countSaving' => $countSaving,
            'amountSaving' => $amountSaving,
        ]);
    }
    public function paymentMethod($type){
        $paymentMethods = PaymentMethod::query()
        ->orderBy('id') // Mengurutkan secara ascending berdasarkan id
        ->get();

        return view('front.account.paymentmethode', compact( ['paymentMethods','type']));

    }
    public function paymentJob($id){
        $job = Job::find($id);
        $paymentMethods = PaymentMethod::query()
        ->orderBy('id') // Mengurutkan secara ascending berdasarkan id
        ->get();

        return view('front.account.paymentjob', compact( ['paymentMethods','job']));

    }
    public function processPayment($id, Request $request)
    {

            $validator = Validator::make($request->all(),[
                'nominal' => 'required|numeric|min:10000',
                'payment_method' => 'required|string',
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'message' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $failedRules = $validator->failed();

                if (isset($failedRules['nominal'])) {
                    return redirect()->back()
                    ->withErrors('Silakan cek Nominal')
                    ->withInput();
                }

                if (isset($failedRules['payment_method'])) {
                    return redirect()->back()
                    ->withErrors('Silakan cek metode pembayaran')
                    ->withInput();
                }
            }

            if (Auth::check()) {
                $userId =Auth::user()->id;
                $name = Auth::user()->name;
                $email = Auth::user()->email;
                $mobile = Auth::user()->mobile;
                $foruserid = Auth::user()->foruserid;
                $keyprivate = Auth::user()->keyprivate;
                $keypublic = Auth::user()->keypublic;

                if ( blank($foruserid)){
                    return redirect()->back()
                    ->withErrors('Periksa ID User atau ID Bisnis anda di pengaturan Akun')
                    ->withInput();
                }
                if ( blank($keyprivate)){
                    return redirect()->back()
                    ->withErrors('Periksa Key Private anda di pengaturan Akun')
                    ->withInput();
                }
                // if ( blank($keypublic)){
                //     return redirect()->back()
                //     ->withErrors('Periksa Key Public anda di pengaturan Akun')
                //     ->withInput();
                // }
            }
            else{
                return redirect()->back()
                    ->withErrors('Silakan Login atau Daftar Akun')
                    ->withInput();
            }

            $selectedMethod = $request->payment_method;
            $paymentChannelData = PaymentMethod::find($selectedMethod);
            $numberOVO = null;
            $customVA = $request->customva ?? null;

            if ($paymentChannelData){
                if ($paymentChannelData->code === 'OVO'){
                    $numberOVO = $request->nomorovo;

                    if ( blank($numberOVO)){
                        return redirect()->back()
                        ->withErrors('Masukan nomor OVO anda')
                        ->withInput();
                    }
                }

                if ($paymentChannelData->type === 'VIRTUAL_ACCOUNT'){

                    if ( blank($customVA)){
                        return redirect()->back()
                        ->withErrors('Masukan Nama Custom VA Anda')
                        ->withInput();
                    }
                }

            }else{
                return redirect()->back()
                ->withErrors('Pembayaran bermasalah')
                ->withInput();
            }

            $hideName = $request->has('hide_name') ? true : false;

            // Atau dapatkan nilai langsung
            $hideNameValue = $request->input('hide_name', false); // Nilai default false jika tidak dicentang

            if ($id === 'DONASI' || $id === 'SAVING'){
                $employer_id = null;
                $type = $id;
                $jobId = null;
            }else{
                $job = Job::find($id);
                $jobId = $id;
                $type = "PROGRAM";
                $employer_id = $job->user_id;
            }

            $application = new JobApplication();
            $application->job_id = $jobId;
            $application->user_id = $userId;
            $application->employer_id = $employer_id;
            $application->applied_date = now();
            $application->email = $email;
            $application->name = $name;
            $application->phone = $mobile;
            $application->type = $type;
            $application->hide_name = $hideName;
            $application->amount = $request->nominal;
            $application->message = $request->message;
            $application->save();


            $paymentGateway = New PaymentGatewayController();
            $paymentMethod = PaymentMethod::find($selectedMethod);

            $cretePayment = $paymentGateway->createPaymentGatewayRequest(
                $selectedMethod,
                $request->nominal,
                $application->id,
                $foruserid,
                $keyprivate,
                $customVA,
                $numberOVO,
            );

            $cretePaymentData = $cretePayment->getData(true); // Data sebagai array
            $valueResponse = $cretePaymentData['value_response'];
            $valueResponseOriginal = $cretePaymentData['value_response'];
            $createPaymentId = $cretePaymentData['payment_request_id'];
            $nameResponse = $cretePaymentData['name_response'];
            $nominalResponse = $cretePaymentData['nominal_information'];
            $expiredResponse = $cretePaymentData['expired_response'];
            $typeResponse = $cretePaymentData['response_type'];

            if ($paymentMethod['action'] === 'qrcode'){
                $valueResponse = DNS2DFacade::getBarcodePNG($valueResponseOriginal, 'QRCODE', 8,8 );
            }

            $transaction = [
                'payment_method' => $paymentMethod,
                'value_response' => $valueResponse, // Simulasi nomor VA
                'name_response' => $nameResponse, // Simulasi nomor VA
                'nominal_response' => $nominalResponse,
                'expired_response' => $expiredResponse,
                'createPaymentId' => $createPaymentId,
            ];

            if ($paymentMethod['action'] === 'qrcode'){
                $valueResponse = DNS2DFacade::getBarcodeHTML($valueResponseOriginal, 'QRCODE', 8,8 );
            }

            $mailData = [
                'name' => $name,
                'payment_method' => $paymentMethod,
                'value_response' => $valueResponse,
                'name_response' => $nameResponse,
                'nominal_response' => $nominalResponse,
                'expired_response' => $expiredResponse,
            ];

            // Mail::to($email)->send(new JobNotificationEmail($mailData));

            if ($typeResponse === 'url'){
                return redirect($valueResponse);
            }
            // Redirect ke success page dengan membawa data transaksi
            // return redirect()->route('success-payment-process')->with('transaction', $transactionData);
            return view('front.paymentprocess', compact( ['transaction','id']));
    }
    public function successPaymentProcess(Request $request)
    {
        // Ambil data transaksi dari session
        $transaction = $request->session()->get('transaction');

        $link = 'Some qr string';

        // $qrCode = DNS1DFacade::getBarcodePNG('4', 'C39+',3,33,array(1,1,1));
        $qrCode = DNS2DFacade::getBarcodePNG($link, 'QRCODE', 8,8 );

        // Redirect jika tidak ada data transaksi
        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Data transaksi tidak ditemukan.');
        }

        return view('front.paymentprocess', compact( ['transaction','qrCode']));
    }

    public function paymentMethodVA(){
        return view('front.paymentva');
    }

    // This method will save a user
    public function processRegistration(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if ($validator->passes()) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->name = $request->name;
            $user->save();

            session()->flash('success','Anda telah berhasil mendaftar.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // This method will show user login page
    public function login() {
        return view('front.account.login');
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')->with('error','Email atau Kata sandi salah');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile() {


        $id = Auth::user()->id;

        $user = User::where('id',$id)->first();

        return view('front.account.profile',[
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request) {

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            // 'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);


        if ($validator->passes()) {
            User::where('id',$id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'designation' => $request->designation,
                'keyprivate' => $request->keyprivate,
                'foruserid' => $request->foruserid,
            ]);

            session()->flash('success','Akun telah dirubah.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function updateProfilePic(Request $request) {
        //dd($request->all());

        $id = Auth::user()->id;

        $validator = Validator::make($request->all(),[
            'image' => 'required|image'
        ]);

        if ($validator->passes()) {

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'.'.$ext;
            $image->move(public_path('/profile_pic/'), $imageName);


            // Create a small thumbnail
            $sourcePath = public_path('/profile_pic/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($sourcePath);

            // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
            $image->cover(150, 150);
            $image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

            // Delete Old Profile Pic
            File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
            File::delete(public_path('/profile_pic/'.Auth::user()->image));

            User::where('id',$id)->update(['image' => $imageName]);

            session()->flash('success','Foto telah dirubah.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function createJob() {

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();

        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.create',[
            'categories' =>  $categories,
            'jobTypes' =>  $jobTypes,
        ]);
    }

    public function saveJob(Request $request) {

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'image' => 'required|image',
            'salary' => 'required|numeric'
            // 'company_name' => 'required|min:3|max:75',

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $job = new Job();
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id  = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;

            if ($request->hasFile('image')) {

                if ($job->image) {
                    $imagePath = 'images/' . $job->image; // Pastikan path sesuai dengan yang ada di storage
                    // Hapus gambar jika ada
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }

                $image = $request->file('image');
                $filename = $job->id . '.' . $image->getClientOriginalExtension(); // Nama file unik
                $request->image->move(public_path('images/products'), $filename);  // simpan ke folder 'images'

                $job->image = 'products/' . $filename;
            }
            $job->save();
            session()->flash('success','Galang Donasi berhasil ditambahkan');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function myJobs() {
        $jobs = Job::where('user_id',Auth::user()->id)->with('applications')

                    ->orderBy('created_at','DESC')->paginate(10);
        return view('front.account.job.my-jobs',[
            'jobs' => $jobs
        ]);
    }

    public function editJob(Request $request, $id) {

        $categories = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name','ASC')->where('status',1)->get();

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $id
        ])->first();

        if ($job == null) {
            abort(404);
        }

        return view('front.account.job.edit',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'job' => $job,
        ]);
    }

    public function updateJob(Request $request, $id) {

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobType' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:75',

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $job = Job::find($id);
            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id  = $request->jobType;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->website;
            $job->save();

            session()->flash('success','Program diperbarui.');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteJob(Request $request) {

        $job = Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
        ])->first();


        if ($job == null) {
            session()->flash('error','Program tidak ditemukan.');
            return response()->json([
                'status' => true
            ]);
        }

        Job::where('id',$request->jobId)->delete();
        session()->flash('success','Program dihapus.');
        return response()->json([
            'status' => true
        ]);

    }

    public function myJobApplications(){
        $jobApplications = JobApplication::where('user_id',Auth::user()->id)
                ->with(['job','job.jobType','job.applications'])
                ->orderBy('created_at','DESC')
                ->paginate(10);

        return view('front.account.job.my-job-applications',[
            'jobApplications' => $jobApplications
        ]);
    }

    public function removeJobs(Request $request){
        $jobApplication = JobApplication::where([
                                    'id' => $request->id,
                                    'user_id' => Auth::user()->id]
                                )->first();

        if ($jobApplication == null) {
            session()->flash('error','Donasi tidak ditemukan.');
            return response()->json([
                'status' => false,
            ]);
        }

        JobApplication::find($request->id)->delete();
        session()->flash('success','Donasi telah dihapus.');

        return response()->json([
            'status' => true,
        ]);

    }

    public function savedJobs(){
        // $jobApplications = JobApplication::where('user_id',Auth::user()->id)
        //         ->with(['job','job.jobType','job.applications'])
        //         ->paginate(10);

        $savedJobs = SavedJob::where([
            'user_id' => Auth::user()->id
        ])->with(['job','job.jobType','job.applications'])
        ->orderBy('created_at','DESC')
        ->paginate(10);

        return view('front.account.job.saved-jobs',[
            'savedJobs' => $savedJobs
        ]);
    }

    public function removeSavedJob(Request $request){
        $savedJob = SavedJob::where([
                                    'id' => $request->id,
                                    'user_id' => Auth::user()->id]
                                )->first();

        if ($savedJob == null) {
            session()->flash('error','Program tidak ditemukan');
            return response()->json([
                'status' => false,
            ]);
        }

        SavedJob::find($request->id)->delete();
        session()->flash('success','Program telah dihapus.');

        return response()->json([
            'status' => true,
        ]);

    }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        if (Hash::check($request->old_password, Auth::user()->password) == false){
            session()->flash('error','Kata sandi lama Anda salah.');
            return response()->json([
                'status' => true
            ]);
        }


        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        session()->flash('success','Kata sandi berhasil dirubah.');
        return response()->json([
            'status' => true
        ]);

    }

    public function forgotPassword() {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->where('email',$request->email)->delete();

        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send Email here
        $user = User::where('email',$request->email)->first();
        $mailData =  [
            'token' => $token,
            'user' => $user,
            'subject' => 'Anda telah meminta Reset Kata Sandi.'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));

        return redirect()->route('account.forgotPassword')->with('success','Permintaan perbarui kata sandi sudah dikirimkan melalui Email');

    }

    public function resetPassword($tokenString) {
        $token = \DB::table('password_reset_tokens')->where('token',$tokenString)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }

        return view('front.account.reset-password',[
            'tokenString' => $tokenString
        ]);
    }

    public function processResetPassword(Request $request) {

        $token = \DB::table('password_reset_tokens')->where('token',$request->token)->first();

        if ($token == null) {
            return redirect()->route('account.forgotPassword')->with('error','Invalid token.');
        }

        $validator = Validator::make($request->all(),[
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.resetPassword',$request->token)->withErrors($validator);
        }

        User::where('email',$token->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('account.login')->with('success','Anda telah berhasil merubah Kata Sandi');

    }
}

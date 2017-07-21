<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\RecruiterRepository;
use App\Mail\JobPortalRecruiterConfirmationEmail;
use App\Models\JobsModel;
use App\Models\RecruiterModel;
use App\Models\RecruiterProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Response;


class RecruiterController extends Controller
{
    protected function createRecruiter(array $data)
    {
        return RecruiterModel::create([
            'organisation_name' => $data['organisation_name'],
            'recruiter_email' => $data['recruiter_email'],
            'recruiter_mobile_no' => $data['recruiter_mobile_no'],
            'password' => bcrypt($data['password'])
        ]);
    }

    public function registerRecruiter(Request $request)
    {

        try {

            $user = $this->createRecruiter($request->all());

            Mail::to($user->recruiter_email)->send(new JobPortalRecruiterConfirmationEmail($user));

            if (count(Mail::failures()) == 0) {
                return Response::json(['code' => 200, 'status' => true, 'message' => trim(Lang::get('recruiter.register-succees'))]);
            }
        } catch (\Exception $exception) {
            return Response::json(['code' => 500, 'status' => false, 'message' => $exception->getMessage()]);
        }


    }

    public function recruiterConfirmEmail($token){
        try {
            $confirm = RecruiterModel::whereToken($token)->firstOrFail()->hasVerified();
            return Response::json(['code' => 200, 'status' => true, 'message' => trim(Lang::get('recruiter.register-confirm'))]);
        } catch (\Exception $exception) {
            return Response::json(['code' => 500, 'status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function loginRecruiter(Request $request){
        try {
            $recruiter = RecruiterModel::GetRecruiterByMobOrEmail($request->value_recruiter)->first();

            if ($recruiter == null) {
                return Response::json(['code' => 200, 'status' => false, 'message' => trim(Lang::get('recruiter.recruiter-not-register'))]);
            }
            if ($recruiter->recruiter_verified == false) {
                return Response::json(['code' => 200, 'status' => false, 'message' => trim(Lang::get('recruiter.recruiter-not-verified'))]);
            }
            if (Hash::check($request->password, $recruiter->password)) {
                return Response::json(['code' => 200, 'status' => true, 'data' => $recruiter]);
            } else {
                return Response::json(['code' => 200, 'status' => false, 'message' => trim(Lang::get('recruiter.recruiter-password'))]);
            }
        } catch (\Exception $exception) {
            return Response::json(['code' => 500, 'status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function fillRecruiterProfile(Request $request,RecruiterRepository $repository){
        $save = $repository->fillRecruiterProfile($request, new RecruiterProfile());
        if ($save['code'] == 400)
            return Response::json(['code' => 400, 'status' => false, 'message' => $save['message']]);

        if($save['code'] == 101)
            return Response::json(['code' => $save['code'], 'status' => $save['status'], 'message' => $save['message']]);

        if($save['code'] == 500)
            return Response::json(['code' => $save['code'], 'status' => $save['status'], 'message' => $save['message']]);
    }

    public function postNewJob(Request $request,RecruiterRepository $repository){
        $save = $repository->saveNewJob($request, new JobsModel());
        if ($save['code'] == 400)
            return Response::json(['code' => 400, 'status' => false, 'message' => $save['message']]);

        if($save['code'] == 101)
            return Response::json(['code' => $save['code'], 'status' => $save['status'], 'message' => $save['message']]);

        if($save['code'] == 500)
            return Response::json(['code' => $save['code'], 'status' => $save['status'], 'message' => $save['message']]);
    }




}

<?php

namespace App\Http\Repository;


use App\Models\ApplyOnJobModel;
use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SeekerRepository
{

    public function fillSeekerProfile($data = [], $model){
        try{
            $temp_data = $data->all();

            if(isset($data->gender))
                $temp_data['gender']= $data->gender;
            else
                return ['code' => 400, 'message' => trim(Lang::get('seeker.seeker-profile-gender'))];

            if(isset($data->job_type))
                $temp_data['job_type'] = $data->job_type;
            else
                return ['code' => 400, 'message' => trim(Lang::get('seeker.seeker-profile-job-type'))];

            if(isset($data->work_experience))
                $temp_data['work_experience'] = $data->work_experience;
            else
                return ['code' => 400, 'message' => trim(Lang::get('seeker.seeker-profile-work-experience'))];

            if($data->specialization == '')
                $temp_data['specialization'] = $data->specialization;
            else
                $temp_data['specialization'] = null;

            if($data->role_type == '')
                $temp_data['role_type'] = $data->role_type;
            else
                $temp_data['role_type'] = null;

            if($data->hasFile('resume')) {
                $ext = $data->resume->getClientOriginalExtension();

                $path = Storage::putFileAs('resumes', $data->resume,time().$data->seeker_id .".".$ext);
                $temp_data['resume'] = $path;
            }
            else{
                return ['code' => 400, 'message' => trim(Lang::get('seeker.seeker-profile-resume'))];
            }

            $temp_data['created_at'] = Carbon::now();
            $model->insert($temp_data);
            return ['code' => 101,'status'=>true, 'message' => 'Profile Update Successfully'];

        }
        catch (\Exception $exception){
            return ['code' => 500, 'status' => false, 'message' => $exception->getMessage()];
        }
    }


    public function applyjob($data = [], $model){

        try {
            $check = ApplyOnJobModel::GetJobApplication($data['job_id'], $data['seeker_id'])->get()->toArray();
            if (count($check) > 0) {
                return ['code' => 400, 'message' => trim(Lang::get('seeker.already-apply'))];
            } else {
                $data['created_at'] = Carbon::now();
                $model->insert($data);
                return ['code' => 101, 'status' => true, 'message' => trim(Lang::get('seeker.apply-success'))];
            }
       }
        catch (\Exception $exception){
            return ['code' => 500, 'status' => false, 'message' => $exception->getMessage()];
        }
    }
}
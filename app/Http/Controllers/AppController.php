<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClasssRepository;
use App\Repositories\StudentRepository;
use App\Repositories\BatchRepository;
use App\Repositories\UserRepository;


class AppController extends Controller
{
    protected $classsRepo;
    protected $studentRepo;
    protected $batchRepo;
    protected $userRepo;

    public function __construct(ClasssRepository $classsRepo,StudentRepository $studentRepo,BatchRepository $batchRepo,UserRepository $userRepo)
    {
        $this->classsRepo=$classsRepo;
        $this->studentRepo=$studentRepo;
        $this->batchRepo=$batchRepo;
        $this->userRepo=$userRepo;
    }
    public function api_test(){
        //$return_total=array();
        $return=array();
        $return['api_test']="ok";
        //array_push($return_total,$return);
        return json_encode($return);
    }
    public function login(Request $request){
        $user=$this->userRepo->app_login($request->all());
        $return=array();
        if($user){
            $return['success']=true;
            $return['api_token']=$user->api_token;
            $return['school_name']=$user->school->School_Name;
        }else{
            $return['success']=false;
        }
        return json_encode($return);

    }
    public function batch_find_class(Request $request,$id){
        $class=$request->user()->school->classs;
        $class_a=array();
        foreach($class as $c){
            if($c->Batch_id==$id){
                array_push($class_a,$c);
            }
        }
        return $class_a;
    }
    public function class_find_student(Request $request,$id){
        $student= $request->user()->school->student;
        $student_a=array();
        foreach($student as $st){
            if($st->Classs_id==$id){
                array_push($student_a,$st);
            }
        }
        return $student_a;
    }
    public function line_notify(Request $request){
        $school=$request->user()->school;
        $id=$request['id'];
        $student=$this->studentRepo->find($id);
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($school->LineChannelAccessToken);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $school->LineChannelSecret]);

        $message="您的孩子".$student->name."已經到班囉!";
        $push_build = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
        $result=$bot->pushMessage($student->parent_line,$push_build);
        return $result->getHTTPStatus();
    }

}

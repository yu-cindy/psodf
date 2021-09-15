<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Repositories\ClasssRepository;
use App\Repositories\BatchRepository;
use App\Repositories\StudentRepository;
use Auth;

class SettingController extends Controller
{
    protected $classsRepo;
    protected $batchRepo;
    protected $studentRepo;

    public function __construct(ClasssRepository $classsRepo,BatchRepository $batchRepo,StudentRepository $studentRepo)
    {
        $this->middleware(['auth','verified']);
        $this->classsRepo=$classsRepo;
        $this->batchRepo=$batchRepo;
        $this->studentRepo=$studentRepo;
    }

    public function classs_store(Request $request){
        $result=$this->classsRepo->store($request->all());
        if($result){
            return redirect()->route('classs.classs')->with('success_msg', '班級已創建！');
        }else{
            return redirect()->route('classs.classs')->with('error_msg', '班級創建失敗！');
        }
    }
    public function classs_update(Request $request){
        $id=$request['classs_update_id'];
        $result = $this->classsRepo->update($request->all(),$id);
        if($result){
            return redirect()->route('classs.classs')->with('success_msg', '班級已編輯！');
        }else{
            return redirect()->route('classs.classs')->with('error_msg', '班級編輯失敗！');
        }
    }
    /*public function classs_delete(Request $request){
        if (Hash::check($request['password'], Auth::user()->password)){
            $id=$request['confirm_delete_id'];
            $result=$this->classsRepo->delete($id);
        }else{
            $result=false;
        }

        if($result){
            return redirect()->route('classs.classs')->with('success_msg', '班級已刪除！');
        }else{
            return redirect()->route('classs.classs')->with('error_msg', '班級刪除失敗！');
        }
    }*/
    public function classs_delete(Request $request){
        $id=$request['id'];
        $student=$this->classsRepo->find($id)->student;
        if(count($student)==0){
            $result=$this->classsRepo->delete($id);
            if($result){
                $return['result']="success";
                $return['msg']="班級已刪除！";
            }else{
                $return['result']="error";
                $return['msg']="班級刪除失敗！";
            }

        }else{
            $return['result']="error";
            $return['msg']="班級刪除失敗！此梯次含有學生";
        }
        return json_encode($return);
    }
    public function student_update(Request $request,$id){

       $classs=$this->classsRepo->find($id);
       $classs_id=$classs->id;
       $count=1;
       if($request['Student_List']!=null){
            if($request['Student_List_old']!=null){
                foreach($request['Student_List_old'] as $student_old){
                    $old_id=$student_old['id'];
                    $found=false;
                    foreach($request['Student_List'] as $student){
                        if($student['id']==$old_id){
                            $found=true;
                            break;
                        }
                    }
                    if(!$found){
                        $this->studentRepo->delete($old_id);
                    }
                }
            }

            foreach($request['Student_List'] as $student){
                if($student['name']!=null){
                    $classs_id=(isset($student['Classs_id'])) ? $student['Classs_id'] : $classs_id;
                    if($student['id']==null){
                        $this->studentRepo->store($student,$classs_id,$count);
                    }else{
                        $this->studentRepo->update($student,$classs_id,$count,$student['id']);
                    }
                    $count++;
                }
            }
       }else{
            foreach($request['Student_List_old'] as $student_old){
                $old_id=$student_old['id'];
                $this->studentRepo->delete($old_id);
            }
       }

        //$result = $this->classsRepo->update($request->all(),$id);
        $result=true;
        if($result){
            $return['result']="success";
            $return['msg']="學生名單已編輯！";
            $return['id']=$id;
        }else{
            $return['result']="error";
            $return['msg']="學生名單編輯失敗！";
            $return['id']=$id;
        }
        return json_encode($return);
    }
    public function student_search(Request $request){
        $result = $this->studentRepo->search_student($request['student_name']);
        return json_encode($result);
    }
    public function batch_store(Request $request){
        $result=$this->batchRepo->store($request->all());
        if($result){
            return redirect()->route('batch')->with('success_msg', '梯次已創建！');
        }else{
            return redirect()->route('batch')->with('error_msg', '梯次創建失敗！');
        }
    }
    public function batch_update(Request $request){
        $id=$request['batch_update_id'];
        $result=$this->batchRepo->update($request->all(),$id);
        if($result){
            return redirect()->route('batch')->with('success_msg', '梯次已編輯！');
        }else{
            return redirect()->route('batch')->with('error_msg', '梯次編輯失敗！');
        }
    }
    public function batch_delete(Request $request){
        $id=$request['id'];
        $classs=$this->batchRepo->find($id)->classs;
        if(count($classs)==0){
            $result=$this->batchRepo->delete($id);
            if($result){
                $return['result']="success";
                $return['msg']="梯次已刪除！";
            }else{
                $return['result']="error";
                $return['msg']="梯次刪除失敗！";
            }

        }else{
            $return['result']="error";
            $return['msg']="梯次刪除失敗！此梯次含有班級";
        }
        return json_encode($return);

    }
    public function perstudent_update(Request $request,$id){
        //return json_encode($request->all());
       $result=$this->studentRepo->single_update($request['student'],$id);
       if($result){
            $return['result']="success";
            $return['msg']="學生資料已更新！";
        }else{
            $return['result']="error";
            $return['msg']="學生資料更新失敗！";
        }
        return json_encode($return);
    }
    public function perstudent_delete($id){
        $result=$this->studentRepo->delete($id);
       if($result){
            $return['result']="success";
            $return['msg']="學生資料已刪除！";
        }else{
            $return['result']="error";
            $return['msg']="學生資料刪除失敗！";
        }
        return json_encode($return);
    }

}

<?php

namespace App\Http\Controllers\Web;

use App\Models\License;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\createCodeLicenseRequest;

class ManagingLicenseController extends Controller
{
    protected $license;
    public function __construct(License $license)
    {
        $this->license=$license;   
    }
    public function all(){
        $listLicense = $this->license->all();
        return view('license')->with(['listLicense' => $listLicense]);
    }

    public function isActive(Request $request){
        $status = $request->status;

        $licenId = $request->id;

        if($status == $this->license::ACTIVE) {
            $checkUpdate = $this->updateStatus($licenId, $this->license::UNACTIVE);
            if ($checkUpdate == true) Session::flash('notifi', 'Đã vô hiêu hóa license !');
            else $this->responseFail("update false");
            
        }else {
            $checkUpdate = $this->updateStatus($licenId, $this->license::ACTIVE);
            if($checkUpdate == true )  Session::flash('notifi', 'Đã active license !');
            else $this->responseFail("update false");
           
        }
        return redirect()->back();
    
    }

    public function updateStatus($licenseId, $status){
        $dataLicenseUpdate = $this->license->where('id', $licenseId)->update(['status' => $status]);

        if ($dataLicenseUpdate != 1) return false;
        else return true;
    }

    public function create(){
        return view('createLicense');
    }

    public function store(createCodeLicenseRequest $request){

        $license = $this->license->create($request->all());
        if(!empty($license)){
            Session::flash('notifi', 'Tạo thành công!');
            return redirect()->back();
            
        }
        
    }
}

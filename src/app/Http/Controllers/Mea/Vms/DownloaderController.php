<?php

namespace App\Http\Controllers\Mea\Vms;

//use App\Entities\Secdbms\Ims\ImsAttachment;
//use App\Entities\Secdbms\Ims\ImsIncidence;
//use App\Entities\Secdbms\Ims\ImsInvestigation;
use App\Contracts\Mea\Vms\CommonContract;
use App\Enums\FileTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Security\HasPermission;

class DownloaderController extends Controller
{
    use HasPermission;
    private $commonVmsManager;
    public function __construct(CommonContract $commonVmsManager)
    {
        $this->commonVmsManager    = $commonVmsManager;
    }

    public function vehiclesAttachments(Request $request, $id)
    {
        $insertedData = $this->commonVmsManager->findInsertedData("MEA.VM_DOCUMENT_PKG.get_document",$id);
        if($insertedData) {
            if($insertedData->doc_file && $insertedData->doc_type && $insertedData->doc_file_name) {
                $content = base64_decode($insertedData->doc_file);

                //DOC_FILE_PATH
                $fileNameArr = explode('.',$insertedData->doc_file_path);
                return response()->make($content, 200, [
                    'Content-Type' => $insertedData->doc_type,
                    'Content-Disposition' => 'attachment; filename="'.(isset($fileNameArr[1])?$insertedData->doc_file_name.'.'.$fileNameArr[1]:$insertedData->doc_file_path).'"'
                ]);
            }
        }
    }

    public function driverPhoto(Request $request, $id)
    {
        $insertedData = $this->commonVmsManager->findInsertedData("MEA.VM_DRIVER_PKG.get_driver_list",$id);
        if($insertedData) {
            if($insertedData->driver_photo && $insertedData->photo_doc_type && $insertedData->photo_doc_name) {
                $content = base64_decode($insertedData->driver_photo);

                //DOC_FILE_PATH
                $fileNameArr = explode('.',$insertedData->photo_doc_name);
                return response()->make($content, 200, [
                    'Content-Type' => $insertedData->photo_doc_type,
                    'Content-Disposition' => 'attachment; filename="'.(isset($fileNameArr[1])?$insertedData->driver_name.'.'.$fileNameArr[1]:$insertedData->photo_doc_name).'"'
                ]);
            }
        }
    }

    public function fuelVoucherAttachment(Request $request, $id)
    {
        $insertedData = $this->commonVmsManager->findInsertedData("MEA.VM_FUEL_PKG.get_fuel_consume",$id);
        if($insertedData) {
            if($insertedData->fuel_voucher && $insertedData->fuel_voucher_doc_type && $insertedData->fuel_voucher_doc_name) {
                $content = base64_decode($insertedData->fuel_voucher);

                //DOC_FILE_PATH
                $fileNameArr = explode('.',$insertedData->fuel_voucher_doc_name);
                return response()->make($content, 200, [
                    'Content-Type' => $insertedData->fuel_voucher_doc_type,
                    'Content-Disposition' => 'attachment; filename="'.(isset($fileNameArr[1])?$insertedData->fuel_voucher_no.'.'.$fileNameArr[1]:$insertedData->fuel_voucher_doc_name).'"'
                ]);
            }
        }
    }

    public function supplierAttachments(Request $request, $id)
    {
        $insertedData = $this->commonVmsManager->findInsertedData("MEA.VM_DOCUMENT_PKG.get_document",$id);
        if($insertedData) {
            if($insertedData->doc_file && $insertedData->doc_type && $insertedData->doc_file_name) {
                $content = base64_decode($insertedData->doc_file);

                //DOC_FILE_PATH
                $fileNameArr = explode('.',$insertedData->doc_file_path);
                return response()->make($content, 200, [
                    'Content-Type' => $insertedData->doc_type,
                    'Content-Disposition' => 'attachment; filename="'.(isset($fileNameArr[1])?$insertedData->doc_file_name.'.'.$fileNameArr[1]:$insertedData->doc_file_path).'"'
                ]);
            }
        }
    }

}

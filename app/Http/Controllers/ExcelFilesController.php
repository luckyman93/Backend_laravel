<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\UserSheets;
use Illuminate\Support\Facades\Storage;
use  Illuminate\Http\File;

class ExcelFilesController extends Controller
{
    public function SelectUserCompanySheets($user_id, $company_id) {
        //return response()->json([$user_id, $company_id]);
        $sheets = DB::table('user_sheets')
        ->where('user_id', $user_id)
        ->where('company_id', $company_id)
        ->get();

        return response()->json($sheets);
    }

    public function saveInitUserSheet(Request $request) {
        $prev = $request->input('init_file');
        $user_id = $request->input('user_id');
        $new = $request->input('file_name');
        $company_id = $request->input('company_id');
        $tab_id = $request->input('tab_id');

        Storage::copy('public/excels/'.$prev, 'public/excels/'.$user_id.'/'.$new.'.xlsx');

        $newSheet = new UserSheets();

        $newSheet->tab_id = $tab_id;
        $newSheet->user_id = $user_id;
        $newSheet->company_id = $company_id;
        $newSheet->file_name = $new;

        $newSheet->save();

        return $request->all();
    }

    public function updateFileName(Request $request) {
        $id = $request->id;
        $name = $request->name;

        $f = DB::table('user_sheets')->where('_id', '=', $id)->update(['file_name' => $name]);
        return response()->json(['message' => 'File name updated successfully'], 200);
    }

    public function deleteSheetVersion(Request $request) {
        $id = $request->id['$oid'];
        //return response()->json($id);
        //$tab = UserSheets::find($id)->delete();
        DB::table('user_sheets')->where('id', '=', $id)->delete();
        $tab = UserSheets::where('_id', '=', $id)->delete();
        return response()->json(['message' => 'Sheet deleted successfully'], 200);
    }



    public function getFilePath() {
        //$file = public_path('/excels/ValuationModelEndeavour.xlsx');
        //$file = new File(public_path('excels/ValuationModelEndeavour.xlsx'));
        //return response()->stream(file_get_contents($file));
        //return response()->json(["file"=>new file(public_path('excels/').'ValuationModelEndeavour.xlsx')]);

        //$x = Storage::disk('local')->get('ValuationModelEndeavour.xlsx');


        /*$fileName = new File(public_path('excels/ValuationModelEndeavour.xlsx'));
        $testV1 = '';
        $headers = [
            'Content-Type' =>  'application/octet-stream',
            'Content-Disposition' => 'attachment; filename='.$fileName,
        ];
        $response = response()->json($fileName, 200, $headers);
        return $response;*/

        $file = public_path('ValuationModelEndeavour.xlsx');
        $filename = basename($file);
        return response()->json($file); //$file, $filename
    }
}

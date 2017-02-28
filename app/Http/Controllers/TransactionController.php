<?php

namespace App\Http\Controllers;
use DB;
use Excel;
use Session;
use Redirect;
use App\City;
use App\User;
use App\Reservasi;
use App\ReservasiDetail;
use App\Room;
use App\Studio;
use App\Jadwal;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    function export(Request $request){
    	$hak = Session::get('hak');
		$studio_id = $request->input('studio_id');
		if($request->input('hari')!=null){
			$query = $request->input('hari');
		} elseif ($request->input('bulan')!=null){
			$query = $request->input('bulan');
		} elseif ($request->input('tahun')!=null){
			$query = $request->input('tahun');
		}
		if($hak == 'ADMIN_ZUPER'){
			$reservasi = Reservasi::where('reservasi_tanggal','like',$query.'%')
									->where('reservasi_status','1')
									->orWhere('reservasi_status','2')
									->get();
		} else {
			$reservasi = Reservasi::where('studio_id',$studio_id)
									->where('reservasi_tanggal','like',$query.'%')
									->where('reservasi_status','1')
									->orWhere('reservasi_status','2')
									->get();
		}
		if($reservasi->count()==0){
			$code['c']='0';
			$code['m']='There is no reservation with that criteria';
			Session::flash('msg',$code);
			return Redirect::back();
		}
    	Excel::create('Transaction Report', function($excel) use ($request,$reservasi) {

    	//jadwal
    		$jadwal = new Jadwal();
    		$jadwal_list = $jadwal->getJadwal();
    	//detail
			$id = array();
    		foreach ($reservasi as $r) {
    			array_push($id, $r['reservasi_id']);
    		}
    		$detail = new ReservasiDetail();
    		$detail_list = $detail->getDetail($id,$jadwal_list);
    	//studio
    		$studio = new Studio();
    		$id = array();
    		foreach ($reservasi as $r) {
    			array_push($id, $r['studio_id']);
    		}
    		$studio_list = $studio->getStudio($id);
    		$studio = $studio_list[$r['studio_id']];
    	//room
    		$room = new Room();
    		$id = array();
    		foreach ($reservasi as $r) {
    			array_push($id, $r['room_id']);
    		}
    		$room_list = $room->getRoom($id);
    	//city
    		$city = new City();
    		$city_list = $city->getCity();
    	//user
    		$user = new User();
    		$id=array();
    		foreach ($reservasi as $r) {
    			array_push($id, $r['user_id']);
    		}
    		$user_list = $user->getUser($id);
    	//status
    		$status = array(
    			'1'=>'Booked',
    			'2'=>'Canceled',
    		);

    		$toExport = array();
    		foreach ($reservasi as $r) {
    			$ref_status = '';
    			if($r['reservasi_status']=='2' && $r['refund_status']==null) $ref_status = "Waiting";
    			elseif($r['reservasi_status']=='2' && $r['refund_status']!=null) $ref_status = "Refunded";
    			else $ref_status = "-";
    			array_push($toExport, [
    				$r['reservasi_nomor_booking'],
    				$room_list[$r['room_id']],
    				$r['reservasi_tanggal'],
    				$detail_list[$r['reservasi_id']],
    				$user_list[$r['user_id']]['nama'],
    				$user_list[$r['user_id']]['email'],
    				$status[$r['reservasi_status']],
    				$r['reservasi_tagihan'],
    				$ref_status,
    				$r['reservasi_refund']
    			]);
    		}
    		$row = count($toExport);

			$reservasiArray = [];
	    	$reservasiArray[] = ['Reservasi Number','Room','Date','Schedule','Client','Client Email','Status','Bill','Refund Status','Refund Cost'];
	    	foreach($toExport as $r){
	    		$reservasiArray[] = $r;
	    	}

    		$excel->setTitle('Our new awesome title');
    		$excel->setCreator('aghaghamaulana@gmail.com')
          			->setCompany('agha maulana');
          	$excel->setDescription('A demonstration to change the file properties');
          	$excel->sheet('New sheet', function($sheet) use ($reservasiArray, $studio) {
          		//JUDUL
          		$sheet->mergeCells('A1:J1');
          		$sheet->setFreeze('A3');
          		$sheet->cell('A1',function($cell) use ($studio){
          			$cell->setValue('Transaction Report for '.$studio);
          			$cell->setFontSize(20);
          			$cell->setFontWeight('bold');
          			$cell->setAlignment('center');
          		});

          		$sheet->cells('A2:J2', function($cells) {
          			$cells->setFontSize(15);
          			$cells->setFontWeight('bold');
          			$cells->setAlignment('center');
				});

          		$sheet->fromArray($reservasiArray, null, 'A2',false,false);
          		$row = count($reservasiArray); $row+=1;
          		$sheet->setBorder('A2:J'.$row, 'thin');
          		$sheet->setBorder('A2:J2', 'thick');
          		$sheet->setAutoSize(true);

		    });
		})->export('xls');

		// or
		// ->download('xls');
    }
}

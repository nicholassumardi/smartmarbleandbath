<?php

namespace App\Http\Controllers\Admin;

use App\Models\AlChecklistProject;
use App\Models\AlChecklist;
use App\Models\AlProject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AlChecklistProjectController extends Controller {
    
    public function index()
    {
        $data = [
            'title'    	=> 'Al Checklist Proyek',
            'content'  	=> 'admin.al.checklist_proyek'
        ];

        return view('admin.layouts.index', ['data' => $data]);
    }
	
	public function datatable(Request $request){
		$column = [
            'id',
            'code',
            'name',
			'al_customer_id',
            'date',
			'note'
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = AlProject::count();
        
        $query_data = AlProject::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = AlProject::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
							->orWhere('date', 'like', "%$search%")
							->orWhere('note', 'like', "%$search%")
							->orWhereHas('alCustomer', function($query) use ($search) {
                                $query->where('name', 'like', "%$search%");
                            });
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
				$notif = '';
				
				if(count($val->alSph) == 0){
					$notif = 'blink-notification';
				}
				
                $response['data'][] = [
					'<span class="pointer-element badge badge-success" data-id="' . $val->id . '"><i class="icon-plus3"></i></span>',
                    $val->code,
                    $val->name,
					$val->alCustomer->name,
                    date('d M Y',strtotime($val->date)),
					$val->note
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
	}
	
	public function rowDetail(Request $request)
    {
		$alprojectid = $request->id;
        $data   = AlChecklist::orderBy('order_data')->get();
        $string = '<table class="table table-bordered">
					<thead class="table-secondary">
						<tr class="text-center">
							<th>No.Urut</th>
							<th>Nama Checklist</th>
							<th>Standar Operasional Prosedur</th>
							<th width="150px">Status</th>
							<th width="150px">Tanggal</th>
							<th width="300px">Catatan</th>
							<th>Simpan</th>
						</tr>
					</thead>
					<tbody class="bg-primary">';
		if(count($data) > 0){
			foreach($data as $pp) {
				$checklist = NULL;
				$checklist = AlChecklistProject::where('al_project_id',$alprojectid)->where('al_checklist_id',$pp->id)->first();
				$status = '
					<select class="form-control" id="opsi'.$pp->id.'_'.$alprojectid.'" '.($checklist ? 'disabled' : '').'>
						<option value="" '.(!$checklist ? 'selected' : '').' disabled>Kosong</option>
						<option value="0" '.($checklist ? ($checklist->status == '0' ? 'selected' : '') : '').'>Skip</option>
						<option value="1" '.($checklist ? ($checklist->status == '1' ? 'selected' : '') : '').'>Finish</option>
					</select>
				';
				$button = $checklist ? '<button class="btn btn-success d-none" id="btnadd'.$pp->id.'_'.$alprojectid.'" onclick="saveRowChecklist('.$pp->id.','.$alprojectid.')"><i class="icon-floppy-disk"></i></button>
						<button class="btn btn-warning" id="btnedit'.$pp->id.'_'.$alprojectid.'" onclick="showRowChecklist('.$pp->id.','.$alprojectid.')"><i class="icon-pencil7"></i></button>' : '<button class="btn btn-success" id="btnadd'.$pp->id.'_'.$alprojectid.'" onclick="saveRowChecklist('.$pp->id.','.$alprojectid.')"><i class="icon-floppy-disk"></i></button>
						<button class="btn btn-warning d-none" id="btnedit'.$pp->id.'_'.$alprojectid.'" onclick="showRowChecklist('.$pp->id.','.$alprojectid.')"><i class="icon-pencil7"></i></button>';
				$string .= '
					<tr>
						<td class="text-center">'.($pp->order_data + 1).'</td>
						<td>'.$pp->name.'</td>
						<td>'.$pp->description.'</td>
						<td class="text-center">'.$status.'</td>
						<td class="text-center"><input class="form-control" type="date" id="date'.$pp->id.'_'.$alprojectid.'" value="'.($checklist ? $checklist->date : date('Y-m-d')).'" '.($checklist ? 'disabled' : '').'></td>
						<td class="text-center"><textarea class="form-control" rows="1" id="note'.$pp->id.'_'.$alprojectid.'" '.($checklist ? 'disabled' : '').'>'.($checklist ? $checklist->note : '').'</textarea></td>
						<td class="text-center">'.$button.'</td>
					</tr>
				';
			}
		}else{
			$string .= '
				<tr>
					<td colspan="6"><div class="alert bg-warning text-white alert-styled-left alert-dismissible">There is no PO data.</div></td>
				</tr>
			';
		}

        $string .= '</tbody></table>';
		
        return response()->json($string);
    }
	
	public function create(Request $request){
		
		$al_checklist_id = $request->al_checklist_id;
		$al_project_id = $request->al_project_id;
		$status = $request->status;
		$date = $request->date;
		$note = $request->note ? $request->note : '';
		
		$cek = AlChecklistProject::where('al_project_id',$al_project_id)->where('al_checklist_id',$al_checklist_id)->first();
		
		if($cek){
			$cek->update([
				'user_id'			=> session('bo_id'),
				'al_project_id'		=> $al_project_id,
				'al_checklist_id'	=> $al_checklist_id,
				'date'            	=> $date,
				'note'				=> $note,
				'status'			=> $status
			]);
		}else{
			$cek = AlChecklistProject::create([
				'user_id'			=> session('bo_id'),
				'al_project_id'		=> $al_project_id,
				'al_checklist_id'	=> $al_checklist_id,
				'date'            	=> $date,
				'note'				=> $note,
				'status'			=> $status
			]);
		}

		if($cek) {
			activity()
				->performedOn(new AlChecklistProject())
				->causedBy(session('bo_id'))
				->withProperties($cek)
				->log('Add Checklist Project AL data');

			$response = [
				'status'  	=> 200,
				'message' 	=> 'Data added successfully.'
			];
		} else {
			$response = [
				'status'  => 500,
				'message' => 'Data failed to add.'
			];
		}

        return response()->json($response);
	}
}
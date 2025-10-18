<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model {

    use HasFactory;

    protected $table      = 'approvals';
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'approvalable_type',
        'approvalable_id',
		'column_name',
        'reference',
        'approved_by',
        'seen',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function references()
    {
        return $this->belongsTo('App\Models\User', 'reference','id');
    }

    public function approvedBy()
    {
        return $this->belongsTo('App\Models\User', 'approved_by');
    }

    public function approvalable()
    {
        return $this->morphTo();
    }

    public function type() 
    {
        switch($this->approvalable_type) {
            case 'orders':
                $type = 'Order';
                break;
            case 'projects':
                $type = 'Project';
                break;
			case 'project_quotations':
                $type = 'Project Quotation';
                break;
			case 'project_samples':
                $type = 'Project Sample';
                break;
			case 'project_sales':
                $type = 'Project Sale';
                break;
			case 'project_payments':
                $type = 'Project Payment Purchase';
                break;
			case 'project_pays':
                $type = 'Project Payment Sale';
                break;
            case 'project_purchases':
                $type = 'Project Purchase';
                break;
			case 'project_deliveries':
                $type = 'Project Sale Delivery';
                break;
			case 'project_sale_returns':
                $type = 'Project Sale Return';
                break;
			case 'project_purchase_returns':
                $type = 'Project Purchase Return';
                break;
			case 'project_bills':
                $type = 'Project Bill';
                break;
			case 'payment_requests':
                $type = 'Payment Request Approval';
                break;
			case 'purchase_requests':
                $type = 'Purchase Request Approval';
                break;
			case 'project_main_payments':
                $type = 'Multi Payment Project Request Approval';
                break;
			case 'leave_requests':
                $type = 'Leave Request Permission';
                break;
			case 'receivable_payments':
                $type = 'Receivable Payment Approval';
                break;
			case 'project_sales_temps':
                $type = 'Sales Order Revision';
                break;
			case 'project_from_stocks':
                $type = 'Project Sales From Stock Approval';
                break;
			case 'budgeting_projects':
                $type = 'Budgeting Project Approval';
                break;
			case 'attendances':
                $type = 'Attendance Selfie Approval';
                break;
			case 'samples':
                $type = 'Sample Approval';
                break;
			case 'service_costs':
                $type = 'Service Charge Approval';
                break;
			case 'service_cost_payments':
                $type = 'Service Charge Payment Approval';
                break;
			case 'project_purchase_quotations':
                $type = 'Request Quotation Approval';
                break;
			default:
                $type = 'Invalid';
                break;
        }

        return $type;
    }

    public function status() 
    {
        switch($this->status) {
            case '1':
                $status = 'Need Approval';
                break;
            case '2':
                $status = 'Approval';
                break;
            case '3':
                $status = 'Rejected';
                break;
            default:
                $status = 'Invalid';
                break;
        }

        return $status;
    }
	
	public static function sendApproval($role,$app_type,$app_id,$column,$reference)
    {
		
		if(is_array($role)){
		
			$row = UserRole::whereIn('role',$role)->groupBy('user_id')->get();

			foreach($row as $r){
				if($r->user->status == '1'){
					Approval::where('user_id',$r->user_id)->where('approvalable_type',$app_type)->where('approvalable_id',$app_id)->where('column_name',$column)->delete();
					
					Approval::create([
						'user_id' 			=> $r->user_id,
						'approvalable_type'	=> $app_type,
						'approvalable_id'	=> $app_id,
						'column_name'		=> $column,
						'reference'			=> $reference,
						'seen'				=> 0,
						'status'			=> '1'
					]);
				}
			}
			
		}else{
			
			Approval::where('user_id',$role)->where('approvalable_type',$app_type)->where('approvalable_id',$app_id)->where('column_name',$column)->delete();
					
			Approval::create([
				'user_id' 			=> $role,
				'approvalable_type'	=> $app_type,
				'approvalable_id'	=> $app_id,
				'column_name'		=> $column,
				'reference'			=> $reference,
				'seen'				=> 0,
				'status'			=> '1'
			]);
			
		}
    }

}

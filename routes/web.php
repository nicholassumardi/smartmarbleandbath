<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::prefix('webhook')->group(function() {
    Route::post('xendit', 'WebHookController@xendit');
});

Route::prefix('product')->group(function() {
    Route::get('/', 'ProductController@index');
    Route::get('detail/{id}', 'ProductController@detail');
    Route::get('check_stock', 'ProductController@checkStock');
    Route::post('add_to_cart', 'ProductController@addToCart');
    Route::post('cart_qty', 'ProductController@cartQty');
    Route::get('cart_destroy/{id}', 'ProductController@cartDestroy');
    Route::post('add_to_wishlist', 'ProductController@addToWishlist');
    Route::get('wishlist_to_cart/{id}', 'ProductController@wishlistToCart');
    Route::get('wishlist_destroy/{id}', 'ProductController@wishlistDestroy');
	Route::get('karya_modern', 'ProductController@karyaModern');
});

Route::prefix('account')->group(function() {
    Route::match(['get', 'post'], 'login', 'AccountController@login');
    Route::match(['get', 'post'], 'register', 'AccountController@register');
    Route::get('verification', 'AccountController@verification');
    Route::post('forgot_password', 'AccountController@forgotPassword');
    Route::match(['get', 'post'], 'reset_password', 'AccountController@resetPassword');
    Route::post('login_social_media', 'AccountController@loginSocialMedia');
    Route::get('login_social_media_callback/{param}', 'AccountController@loginSocialMediaCallback');
    Route::get('logout', 'AccountController@logout');
    Route::get('cart', 'AccountController@cart');
    Route::get('wishlist', 'AccountController@wishlist');
    Route::get('history_order', 'AccountController@historyOrder');
    Route::get('history_order/detail/{id}', 'AccountController@historyOrderDetail');
    Route::post('history_order/confirmation_delivery', 'AccountController@confirmationDelivery');
    Route::match(['get', 'post'], 'profile', 'AccountController@profile');
    Route::get('voucher', 'AccountController@voucher');
    Route::get('points', 'AccountController@points');
});

Route::prefix('information')->group(function() {
    Route::get('how_to_buy', 'InformationController@howToBuy');
    Route::get('faq', 'InformationController@faq');
    Route::match(['get', 'post'], 'contact', 'InformationController@contact');
    Route::get('store', 'InformationController@store');
    Route::get('product_catalog', 'InformationController@productCatalog');
    Route::get('pay_in_store', 'InformationController@payInStore');
    Route::get('about_us', 'InformationController@aboutUs');
    Route::get('terms_of_use', 'InformationController@termsOfUse');
    Route::get('privacy_policy', 'InformationController@privacyPolicy');
    Route::get('terms_of_delivery', 'InformationController@termsOfDelivery');
	
	Route::prefix('tracking')->group(function() {
		Route::get('/', 'TrackingProjectController@index');
		Route::get('progress/{code}', 'TrackingProjectController@trackingProgress');
		Route::post('get_url', 'TrackingProjectController@convertUrl');
	});
});

Route::prefix('cogs_calculator')->group(function() {
    Route::get('/', 'CogsCalculatorController@index');
    Route::match(['get', 'post'], 'create', 'CogsCalculatorController@create');
});


Route::prefix('test')->group(function() {
	Route::get('/', function () {
		return view('test');
	});
});


Route::prefix('news')->group(function() {
    Route::get('/', 'NewsController@index');
    Route::get('detail/{slug}', 'NewsController@detail');
});

Route::prefix('career')->group(function() {
    Route::get('/', 'CareerController@index');
});

Route::prefix('checkout')->group(function() {
    Route::match(['get', 'post'], '/', 'CheckoutController@index');
    Route::get('notif/{param}', 'CheckoutController@notif');

    Route::prefix('process')->group(function() {
        Route::get('get_delivery', 'CheckoutController@getDelivery');
        Route::get('grandtotal', 'CheckoutController@grandtotal');
    });
});

Route::prefix('project')->namespace('Admin')->group(function() {
    Route::prefix('tracking')->group(function() {
		Route::get('shipment/{id}/{code}', 'ProjectController@trackingShipment');
		Route::get('delivery/{id}/{code}', 'ProjectController@trackingDelivery');
    });
});

Route::prefix('attendance')->namespace('Attendance')->group(function() {
	Route::match(['get', 'post'], 'login', 'CodeController@login');
	
	Route::middleware('attendance.login')->group(function() {
		 Route::get('code', 'QrCodeController@code');
		 Route::post('generate', 'QrCodeController@generate');
		 Route::get('logout', 'CodeController@logout');
	});
});

Route::prefix('admin')->namespace('Admin')->group(function() {
    Route::match(['get', 'post'], 'login', 'AuthController@login');
    Route::get('verification', 'AuthController@verification');
    Route::post('forgot_password', 'AuthController@forgotPassword');
    Route::match(['get', 'post'], 'reset_password', 'AuthController@resetPassword');
	Route::get('repair_fee_pta','AuthController@repairFeePta');
	
    Route::middleware('admin.login')->group(function() {
        Route::match(['get', 'post'], 'profile', 'AuthController@profile');
		Route::post('profile/uploadSign', 'AuthController@uploadSign');
		Route::post('profile/getNotification', 'AuthController@getNotification');
        Route::match(['get', 'post'], 'my_activity', 'AuthController@myActivity');
        Route::get('logout', 'AuthController@logout');
		
		Route::prefix('my_attendance')->group(function() {
            Route::get('/', 'AttendanceController@index');
			Route::post('scan', 'AttendanceController@scan');
			Route::post('selfie', 'AttendanceController@selfie');
			Route::post('get_attendance', 'AttendanceController@getAttendance');
			Route::get('datatable', 'AttendanceController@datatable');
        });
		
		Route::prefix('price_list')->middleware('admin.role:1|3|4|5|6|10')->group(function() {
            Route::get('/', 'PriceListController@index');
			Route::get('datatable', 'PriceListController@datatable');
			Route::get('datatable_buy_price', 'PriceListController@datatableBuyPrice');
        });
        
		Route::prefix('message')->group(function() {
            Route::get('/', 'MessageController@index');
        });
		
        Route::prefix('dashboard')->group(function() {
            Route::get('/', 'DashboardController@index');
			Route::post('/get_dashboard_data', 'DashboardController@getDashboardData');
			Route::post('/get_dashboard_detail', 'DashboardController@getDashboardDetail');
			Route::post('/get_dashboard_approval', 'DashboardController@getDashboardApproval');
			Route::post('/get_dashboard_done_sales', 'DashboardController@getDashboardDoneSales');
			Route::post('/get_dashboard_profit_loss', 'DashboardController@getDashboardProfitLoss');
			Route::post('/get_cash_flow', 'DashboardController@getDashboardCashFlow');
			Route::get('dashboard_cashflow', 'DashboardController@dashboardCashFlow');
			Route::get('print_sales_report', 'DashboardController@printSalesReport');
        });
		
		Route::prefix('folder')->group(function() {
            Route::get('/', 'FolderController@index');
			Route::get('datatable', 'FolderController@datatable');
			Route::get('datatable_detail', 'FolderController@datatableDetail');
			Route::post('create', 'FolderController@create');
			Route::get('detail/{id}', 'FolderController@detail');
			Route::post('add_files','FolderController@addFiles');
			Route::post('delete_file','FolderController@deleteFile');
			Route::post('delete_folder','FolderController@deleteFolder');
			Route::get('row_detail', 'FolderController@rowDetail');
			Route::get('{id}/download', 'FolderController@downloadFile');
        });
		
		Route::prefix('purchase_request')->group(function() {
            Route::get('/', 'PurchaseRequestController@user');
			Route::post('create', 'PurchaseRequestController@userAdd');
			Route::get('user_datatable', 'PurchaseRequestController@userDatatable');
			Route::post('userShow', 'PurchaseRequestController@userShow');
			Route::post('showInformation', 'PurchaseRequestController@showInformation');
			Route::post('userUpdate/{id}', 'PurchaseRequestController@userUpdate');
			Route::post('userDestroy', 'PurchaseRequestController@userDestroy');
        });
		
		Route::prefix('leave_request')->group(function() {
            Route::get('/', 'LeaveRequestController@user');
			Route::post('create', 'LeaveRequestController@userAdd');
			Route::get('user_datatable', 'LeaveRequestController@userDatatable');
			Route::get('get_leave', 'LeaveRequestController@getLeave');
			Route::post('userShow', 'LeaveRequestController@userShow');
			Route::post('showInformation', 'LeaveRequestController@showInformation');
			Route::post('userUpdate/{id}', 'LeaveRequestController@userUpdate');
			Route::post('userDestroy', 'LeaveRequestController@userDestroy');
        });

        Route::prefix('all_activities')->group(function() {
            Route::get('/', 'NotificationController@index');
			Route::get('datatable', 'NotificationController@datatable');
        });

        Route::prefix('approval')->group(function() {
            Route::get('/', 'ApprovalController@index');
            Route::get('datatable', 'ApprovalController@datatable');
            Route::match(['get', 'post'], 'detail/{id}', 'ApprovalController@detail');
			Route::post('project', 'ApprovalController@project');
			Route::post('reject', 'ApprovalController@reject');
			Route::post('multi_approve', 'ApprovalController@multiApprove');
        });

        Route::prefix('select2')->group(function() {
            Route::get('type', 'Select2Controller@type');
            Route::get('product', 'Select2Controller@product');
			Route::get('al_product', 'Select2Controller@alProduct');
			Route::get('stock', 'Select2Controller@stock');
			Route::get('user', 'Select2Controller@user');
			Route::get('warehouse', 'Select2Controller@warehouse');
			Route::get('supplier', 'Select2Controller@supplier');
			Route::get('customer', 'Select2Controller@customer');
			Route::get('customer_unpaid', 'Select2Controller@customerUnpaid');
			Route::get('city', 'Select2Controller@city');
			Route::get('country', 'Select2Controller@country');
			Route::get('currency', 'Select2Controller@currency');
			Route::get('vendor', 'Select2Controller@vendor');
			Route::get('loginAndroid', 'Select2Controller@loginAndroid');
			Route::get('project', 'Select2Controller@project');
			Route::get('purchase_request', 'Select2Controller@purchaseRequest');
			Route::get('purchase_request_available', 'Select2Controller@purchaseRequestAvailable');
			Route::get('sales_order', 'Select2Controller@salesOrder');
			Route::get('sales_order_for_purchase', 'Select2Controller@salesOrderForPurchaseFromStock');
			Route::get('project_purchase', 'Select2Controller@projectPurchase');
			Route::get('po_from_stock', 'Select2Controller@projectPurchaseFromStock');
        });
		
		Route::prefix('al')->middleware('admin.role:1|9|10|16')->group(function() {
			Route::prefix('master_data')->group(function() {
				Route::prefix('customer')->group(function() {
					Route::get('/', 'AlCustomerController@index');
					Route::get('datatable', 'AlCustomerController@datatable');
					Route::get('datatable_institute', 'AlCustomerController@datatableInstitute');
					Route::post('create', 'AlCustomerController@create');
					Route::post('create_institute', 'AlCustomerController@createInstitute');
					Route::post('show', 'AlCustomerController@show');
					Route::get('show_institute', 'AlCustomerController@showInstitute');
					Route::post('update/{id}', 'AlCustomerController@update');
					Route::post('destroy', 'AlCustomerController@destroy');
					Route::get('export','AlCustomerController@export');
					Route::get('print','AlCustomerController@print');
				});
				
				Route::prefix('supplier')->group(function() {
                    Route::get('/', 'AlSupplierController@index');
                    Route::get('datatable', 'AlSupplierController@datatable');
                    Route::post('create', 'AlSupplierController@create');
                    Route::get('show', 'AlSupplierController@show');
                    Route::post('update/{id}', 'AlSupplierController@update');
                    Route::post('destroy', 'AlSupplierController@destroy');
                });
				
				Route::prefix('barang')->group(function() {
                    Route::get('/', 'AlProductController@index');
                    Route::get('datatable', 'AlProductController@datatable');
					Route::get('datatable_category', 'AlProductController@datatableCategory');
					Route::get('datatable_parent', 'AlProductController@datatableParent');
                    Route::post('create', 'AlProductController@create');
					Route::post('create_category', 'AlProductController@createCategory');
					Route::post('create_parent', 'AlProductController@createParent');
                    Route::get('show', 'AlProductController@show');
					Route::get('show_category', 'AlProductController@showCategory');
					Route::get('list_category', 'AlProductController@listCategory');
					Route::get('show_parent', 'AlProductController@showParent');
                    Route::post('update/{id}', 'AlProductController@update');
                    Route::post('destroy', 'AlProductController@destroy');
					Route::post('destroy_category', 'AlProductController@destroyCategory');
					Route::post('destroy_parent', 'AlProductController@destroyParent');
					Route::post('get_pictures', 'AlProductController@getPictures');
					Route::post('add_pictures','AlProductController@addPictures');
					Route::post('delete_picture','AlProductController@deletePictures');
                });
				
				Route::prefix('checklist')->group(function() {
                    Route::get('/', 'AlChecklistController@index');
                    Route::get('datatable', 'AlChecklistController@datatable');
                    Route::post('create', 'AlChecklistController@create');
                    Route::get('show', 'AlChecklistController@show');
                    Route::post('update', 'AlChecklistController@update');
                    Route::post('destroy', 'AlChecklistController@destroy');
                });
				
				Route::prefix('dokumen')->group(function() {
					Route::get('/', 'AlDocumentController@index');
					Route::post('ckeditor', 'AlDocumentController@ckeditorUpload');
					Route::get('tambah', 'AlDocumentController@create');
					Route::post('create', 'AlDocumentController@save');
					Route::get('print/{id}', 'AlDocumentController@print');
					Route::get('edit/{id}', 'AlDocumentController@edit');
					Route::post('destroy', 'AlDocumentController@destroy');
				});
			});
			
			Route::prefix('sph_dkh')->group(function() {
				Route::get('/', 'AlSphController@index');
				Route::get('datatable', 'AlSphController@datatable');
				Route::post('create', 'AlSphController@create');
				Route::get('show', 'AlSphController@show');
				Route::get('edit_sph/{id}', 'AlSphController@editSph');
				Route::get('detail/edit', 'AlSphController@showEditSph');
				Route::post('edit_sph/get_list_sph', 'AlSphController@getListSph');
				Route::post('edit_sph/show', 'AlSphController@showSph');
				Route::post('edit_sph/create', 'AlSphController@createSph');
				Route::post('edit_sph/destroy', 'AlSphController@destroySph');
				Route::get('sph/print/{id}', 'AlSphController@printSph');
				Route::get('sph/print_2/{id}', 'AlSphController@printSph2');
				Route::get('sph/print_profit/{id}', 'AlSphController@printSphProfit');
				Route::get('sph/print_pembelian/{id}', 'AlSphController@printSphPembelian');
				Route::get('sph/print_pembelian_2/{id}', 'AlSphController@printSphPembelian2');
				Route::post('destroy', 'AlSphController@destroy');
			});
			
			Route::prefix('rab')->group(function() {
				Route::get('/', 'AlBudgetingController@index');
				Route::get('get_customer_sph', 'AlBudgetingController@getCustomerSph');
				Route::get('datatable', 'AlBudgetingController@datatable');
				Route::post('create', 'AlBudgetingController@create');
				Route::get('show', 'AlBudgetingController@show');
				Route::get('analysis/{id}', 'AlBudgetingController@analysis');
				Route::post('update_tax', 'AlBudgetingController@updateTax');
				Route::get('print/{id}', 'AlBudgetingController@print');
				Route::post('destroy', 'AlBudgetingController@destroy');
			});
			
			Route::prefix('kelengkapan_data')->group(function() {
				Route::get('/', 'AlFormCompletenessController@index');
				Route::get('tambah', 'AlFormCompletenessController@create');
				Route::post('add_document', 'AlFormCompletenessController@addDocument');
				Route::post('create', 'AlFormCompletenessController@save');
				Route::get('project/print/{id}', 'AlFormCompletenessController@print');
				Route::get('edit/{id}', 'AlFormCompletenessController@edit');
				Route::post('destroy', 'AlFormCompletenessController@destroy');
			});
			
			Route::prefix('surat_permohonan')->group(function() {
				Route::get('/', 'AlApplicationLetterController@index');
				Route::post('create', 'AlApplicationLetterController@create');
				Route::get('datatable', 'AlApplicationLetterController@datatable');
				Route::get('print/{id}', 'AlApplicationLetterController@print');
				Route::get('show', 'AlApplicationLetterController@show');
				Route::post('destroy', 'AlApplicationLetterController@destroy');
			});
			
			Route::prefix('po')->group(function() {
				Route::get('/', 'AlPurchaseController@index');
				Route::post('create', 'AlPurchaseController@create');
				Route::post('get_supplier_sph', 'AlPurchaseController@getSupplierSph');
				Route::get('datatable', 'AlPurchaseController@datatable');
				Route::get('print/{id}', 'AlPurchaseController@print');
				Route::get('print_no_price/{id}', 'AlPurchaseController@printNoPrice');
				Route::get('show', 'AlPurchaseController@show');
				Route::post('destroy', 'AlPurchaseController@destroy');
			});
			
			Route::prefix('surat_jalan')->group(function() {
				Route::get('/', 'AlDeliveryController@index');
				Route::post('create', 'AlDeliveryController@create');
				Route::get('datatable', 'AlDeliveryController@datatable');
				Route::get('print/{id}', 'AlDeliveryController@print');
				Route::get('show', 'AlDeliveryController@show');
				Route::post('destroy', 'AlDeliveryController@destroy');
				Route::post('get_sph_product', 'AlDeliveryController@getSphProduct');
				Route::post('add_received_date','AlDeliveryController@addReceivedDate');
			});
			
			Route::prefix('faktur_barang')->group(function() {
				Route::get('/', 'AlInvoiceController@index');
				Route::post('create', 'AlInvoiceController@create');
				Route::get('datatable', 'AlInvoiceController@datatable');
				Route::get('print/{id}', 'AlInvoiceController@print');
				Route::get('show', 'AlInvoiceController@show');
				Route::post('destroy', 'AlInvoiceController@destroy');
			});
			
			Route::prefix('finance')->group(function() {
				Route::prefix('pemasukan')->group(function() {
					Route::get('/', 'AlFinanceIncomeController@index');
					Route::get('datatable', 'AlFinanceIncomeController@datatable');
					Route::post('create', 'AlFinanceIncomeController@create');
					Route::get('show', 'AlFinanceIncomeController@show');
					Route::post('destroy', 'AlFinanceIncomeController@destroy');
					Route::get('print/{id}', 'AlFinanceIncomeController@print');
				});
				Route::prefix('pengeluaran')->group(function() {
					Route::get('/', 'AlFinanceExpenseController@index');
					Route::get('datatable', 'AlFinanceExpenseController@datatable');
					Route::get('detail/{id}', 'AlFinanceExpenseController@detailIndex');
					Route::get('detail/{id}/datatable', 'AlFinanceExpenseController@detailDatatable');
					Route::post('create', 'AlFinanceExpenseController@create');
					Route::get('show', 'AlFinanceExpenseController@show');
					Route::get('get_budgeting_expense', 'AlFinanceExpenseController@getBudgetingExpense');
					Route::post('destroy', 'AlFinanceExpenseController@destroy');
					Route::post('create_pay', 'AlFinanceExpenseController@createPay');
					Route::post('get_payment', 'AlFinanceExpenseController@getPayment');
					Route::post('delete_payment', 'AlFinanceExpenseController@deletePayment');
					Route::get('print', 'AlFinanceExpenseController@print');
					Route::post('get_pictures','AlFinanceExpenseController@getPictures');
					Route::post('add_pictures','AlFinanceExpenseController@addPictures');
					Route::post('delete_picture','AlFinanceExpenseController@deletePictures');
					Route::post('update_payment','AlFinanceExpenseController@updatePayment');
					Route::post('upload_pay_proof','AlFinanceExpenseController@uploadPayProof');
				});
			});
			
			Route::prefix('arsip')->group(function() {
				Route::prefix('supplier')->group(function() {
					Route::get('/', 'AlArchiveController@index');
					Route::get('datatable', 'AlArchiveController@datatable');
					Route::post('create', 'AlArchiveController@create');
					Route::post('get_supplier', 'AlArchiveController@getSupplier');
					Route::post('get_pictures','AlArchiveController@getPictures');
					Route::get('show', 'AlArchiveController@show');
					Route::post('destroy', 'AlArchiveController@destroy');
					Route::post('add_pictures','AlArchiveController@addPictures');
					Route::post('delete_picture','AlArchiveController@deletePictures');
				});
				
				Route::prefix('spk_tni')->group(function() {
					Route::get('/', 'AlSpkController@index');
					Route::get('datatable', 'AlSpkController@datatable');
					Route::post('create', 'AlSpkController@create');
					Route::get('show', 'AlSpkController@show');
					Route::post('destroy', 'AlSpkController@destroy');
					Route::post('get_pictures','AlSpkController@getPictures');
					Route::post('add_pictures','AlSpkController@addPictures');
					Route::post('delete_picture','AlSpkController@deletePictures');
				});
			});
			
			Route::prefix('checklist_proyek')->group(function() {
				Route::get('/', 'AlChecklistProjectController@index');
				Route::post('create', 'AlChecklistProjectController@create');
				Route::get('datatable', 'AlChecklistProjectController@datatable');
				Route::get('row_detail', 'AlChecklistProjectController@rowDetail');
			});
			
			Route::prefix('report')->group(function() {
				Route::prefix('accounting')->group(function() {
					Route::get('/', 'AlReportAccountingController@index');
					Route::post('get_report', 'AlReportAccountingController@getReport');
				});
				
				Route::prefix('proyek')->group(function() {
					Route::get('/', 'AlReportProjectController@index');
					Route::get('print', 'AlReportProjectController@print');
				});
			});
		});
		
        Route::prefix('master_data')->group(function() {
            Route::prefix('product')->middleware('admin.role:1|5|6|9|10|11')->group(function() {
				Route::prefix('city_currency_warehouse')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CityCurrencyWarehouseController@index');
                });
				
                Route::prefix('company')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CompanyController@index');
                    Route::get('datatable', 'CompanyController@datatable');
                    Route::post('create', 'CompanyController@create');
                    Route::get('show', 'CompanyController@show');
                    Route::post('update/{id}', 'CompanyController@update');
                    Route::post('destroy', 'CompanyController@destroy');
                });

                Route::prefix('division')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'DivisionController@index');
                    Route::get('datatable', 'DivisionController@datatable');
                    Route::post('create', 'DivisionController@create');
                    Route::get('show', 'DivisionController@show');
                    Route::post('update/{id}', 'DivisionController@update');
                    Route::post('destroy', 'DivisionController@destroy');
                });

                Route::prefix('country')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CountryController@index');
                    Route::get('datatable', 'CountryController@datatable');
                    Route::post('create', 'CountryController@create');
                    Route::get('show', 'CountryController@show');
                    Route::post('update/{id}', 'CountryController@update');
                    Route::post('destroy', 'CountryController@destroy');
                });

                Route::prefix('city')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CityController@index');
                    Route::get('datatable', 'CityController@datatable');
                    Route::post('create', 'CityController@create');
                    Route::get('show', 'CityController@show');
                    Route::post('update/{id}', 'CityController@update');
                    Route::post('destroy', 'CityController@destroy');
                });

                Route::prefix('currency')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CurrencyController@index');
                    Route::get('datatable', 'CurrencyController@datatable');
                    Route::post('create', 'CurrencyController@create');
                    Route::get('show', 'CurrencyController@show');
                    Route::post('update/{id}', 'CurrencyController@update');
                    Route::post('destroy', 'CurrencyController@destroy');
                });

                Route::prefix('supplier')->middleware('admin.role:1|5|9|10|11')->group(function() {
                    Route::get('/', 'SupplierController@index');
                    Route::get('datatable', 'SupplierController@datatable');
                    Route::post('create', 'SupplierController@create');
                    Route::get('show', 'SupplierController@show');
                    Route::post('update/{id}', 'SupplierController@update');
                    Route::post('destroy', 'SupplierController@destroy');
                });

                Route::prefix('brand')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'BrandController@index');
                    Route::get('datatable', 'BrandController@datatable');
                    Route::post('create', 'BrandController@create');
                    Route::get('show', 'BrandController@show');
                    Route::post('update/{id}', 'BrandController@update');
                    Route::post('destroy', 'BrandController@destroy');
                });

                Route::prefix('category')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CategoryController@index');
                    Route::get('datatable', 'CategoryController@datatable');
                    Route::post('create', 'CategoryController@create');
                    Route::get('show', 'CategoryController@show');
                    Route::post('update/{id}', 'CategoryController@update');
                    Route::post('destroy', 'CategoryController@destroy');
                });

                Route::prefix('surface')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'SurfaceController@index');
                    Route::get('datatable', 'SurfaceController@datatable');
                    Route::post('create', 'SurfaceController@create');
                    Route::get('show', 'SurfaceController@show');
                    Route::post('update/{id}', 'SurfaceController@update');
                    Route::post('destroy', 'SurfaceController@destroy');
                });

                Route::prefix('color')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'ColorController@index');
                    Route::get('datatable', 'ColorController@datatable');
                    Route::post('create', 'ColorController@create');
                    Route::get('show', 'ColorController@show');
                    Route::post('update/{id}', 'ColorController@update');
                    Route::post('destroy', 'ColorController@destroy');
                });

                Route::prefix('pattern')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'PatternController@index');
                    Route::get('datatable', 'PatternController@datatable');
                    Route::post('create', 'PatternController@create');
                    Route::get('show', 'PatternController@show');
                    Route::post('update/{id}', 'PatternController@update');
                    Route::post('destroy', 'PatternController@destroy');
                });

                Route::prefix('grade')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'GradeController@index');
                    Route::get('datatable', 'GradeController@datatable');
                    Route::post('create', 'GradeController@create');
                    Route::get('show', 'GradeController@show');
                    Route::post('update/{id}', 'GradeController@update');
                    Route::post('destroy', 'GradeController@destroy');
                });

                Route::prefix('hs_code')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'HsCodeController@index');
                    Route::get('datatable', 'HsCodeController@datatable');
                    Route::post('create', 'HsCodeController@create');
                    Route::get('show', 'HsCodeController@show');
                    Route::post('update/{id}', 'HsCodeController@update');
                    Route::post('destroy', 'HsCodeController@destroy');
                });

                Route::prefix('unit')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'UnitController@index');
                    Route::get('datatable', 'UnitController@datatable');
                    Route::post('create', 'UnitController@create');
                    Route::get('show', 'UnitController@show');
                    Route::post('update/{id}', 'UnitController@update');
                    Route::post('destroy', 'UnitController@destroy');
                });

                Route::prefix('loading_limit')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'LoadingLimitController@index');
                    Route::get('datatable', 'LoadingLimitController@datatable');
                    Route::post('create', 'LoadingLimitController@create');
                    Route::get('show', 'LoadingLimitController@show');
                    Route::post('update/{id}', 'LoadingLimitController@update');
                    Route::post('destroy', 'LoadingLimitController@destroy');
                });

                Route::prefix('warehouse')->middleware('admin.role:1|4|5|9|10')->group(function() {
                    Route::get('/', 'WarehouseController@index');
                    Route::get('datatable', 'WarehouseController@datatable');
					Route::post('create', 'WarehouseController@create');
					Route::get('show', 'WarehouseController@show');
					Route::post('destroy', 'WarehouseController@destroy');
                });

                Route::prefix('product_type')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'TypeController@index');
                    Route::get('datatable', 'TypeController@datatable');
                    Route::post('create', 'TypeController@create');
                    Route::get('show', 'TypeController@show');
                    Route::post('update/{id}', 'TypeController@update');
                    Route::post('destroy', 'TypeController@destroy');
					Route::post('show_in_store', 'TypeController@showInStore');
                    Route::get('detail/{id}', 'TypeController@detail');
                });

                Route::prefix('product_code')->middleware('admin.role:1|5|9|10')->group(function() {
                    Route::get('/', 'CodeController@index');
                    Route::get('datatable', 'CodeController@datatable');
                    Route::get('generate_code', 'CodeController@generateCode');
                    Route::get('formula', 'CodeController@formula');
                    Route::post('create', 'CodeController@create');
                    Route::get('show', 'CodeController@show');
					Route::get('repair', 'CodeController@repairShading');
                    Route::post('update/{id}', 'CodeController@update');
                    Route::post('destroy', 'CodeController@destroy');
                    Route::get('detail/{id}', 'CodeController@detail');
                });
            });

            Route::prefix('cogs_master')->middleware('admin.role:1|5|9|10')->group(function() {
                Route::prefix('buy_exchange_rate')->group(function() {
                    Route::get('/', 'CurrencyRateController@index');
                    Route::get('datatable', 'CurrencyRateController@datatable');
                    Route::post('create', 'CurrencyRateController@create');
                    Route::get('show', 'CurrencyRateController@show');
                    Route::post('update/{id}', 'CurrencyRateController@update');
                    Route::post('destroy', 'CurrencyRateController@destroy');
                });

                Route::prefix('purchase_price')->group(function() {
                    Route::get('/', 'CurrencyPriceController@index');
                    Route::get('datatable', 'CurrencyPriceController@datatable');
                    Route::post('create', 'CurrencyPriceController@create');
                    Route::get('show', 'CurrencyPriceController@show');
                    Route::post('update/{id}', 'CurrencyPriceController@update');
                    Route::post('destroy', 'CurrencyPriceController@destroy');
                });

                Route::prefix('freight')->group(function() {
                    Route::get('/', 'FreightController@index');
                    Route::get('datatable', 'FreightController@datatable');
                    Route::post('create', 'FreightController@create');
                    Route::get('show', 'FreightController@show');
                    Route::post('update/{id}', 'FreightController@update');
                    Route::post('destroy', 'FreightController@destroy');
                });

                Route::prefix('import_system')->group(function() {
                    Route::get('/', 'ImportController@index');
                    Route::get('datatable', 'ImportController@datatable');
                    Route::post('create', 'ImportController@create');
                    Route::get('show', 'ImportController@show');
                    Route::post('update/{id}', 'ImportController@update');
                    Route::post('destroy', 'ImportController@destroy');
                });

                Route::prefix('import_estimation_rate')->group(function() {
                    Route::get('/', 'EmklController@index');
                    Route::get('datatable', 'EmklController@datatable');
                    Route::post('create', 'EmklController@create');
                    Route::get('show', 'EmklController@show');
                    Route::post('update/{id}', 'EmklController@update');
                    Route::post('destroy', 'EmklController@destroy');
                });

                Route::prefix('import_custom_rate')->group(function() {
                    Route::get('/', 'EmklRateController@index');
                    Route::get('datatable', 'EmklRateController@datatable');
                    Route::post('create', 'EmklRateController@create');
                    Route::get('show', 'EmklRateController@show');
                    Route::post('update/{id}', 'EmklRateController@update');
                    Route::post('destroy', 'EmklRateController@destroy');
                });
				
				Route::prefix('landed_cost')->group(function() {
                    Route::get('/', 'LandedCostController@index');
                });

                Route::prefix('marketing_cost')->group(function() {
                    Route::get('/', 'MarketingStructureController@index');
                    Route::get('datatable', 'MarketingStructureController@datatable');
                    Route::get('row_detail', 'MarketingStructureController@rowDetail');
                    Route::post('create', 'MarketingStructureController@create');
                    Route::get('show', 'MarketingStructureController@show');
                    Route::post('update/{id}', 'MarketingStructureController@update');
                    Route::post('destroy', 'MarketingStructureController@destroy');
                });

                Route::prefix('cogs_sales')->group(function() {
                    Route::get('/', 'CogsController@index');
                    Route::get('datatable', 'CogsController@datatable');
                    Route::get('get_complete_data', 'CogsController@getCompleteData');
                    Route::match(['get', 'post'], 'create', 'CogsController@create');
                    Route::get('show', 'CogsController@show');
                });

                Route::prefix('pricing_sales')->group(function() {
                    Route::get('/', 'PricingPolicyController@index');
                    Route::get('datatable', 'PricingPolicyController@datatable');
                    Route::get('row_detail', 'PricingPolicyController@rowDetail');
                    Route::post('create', 'PricingPolicyController@create');
                    Route::get('show', 'PricingPolicyController@show');
                    Route::post('update/{id}', 'PricingPolicyController@update');
                    Route::post('destroy', 'PricingPolicyController@destroy');
                });
            });

            Route::prefix('delivery')->middleware('admin.role:1|5|11')->group(function() {
				Route::prefix('dropshipper')->group(function() {
                    Route::get('/', 'DropshipperController@index');
                    Route::get('datatable', 'DropshipperController@datatable');
                    Route::post('create', 'DropshipperController@create');
                    Route::get('show', 'DropshipperController@show');
                    Route::post('update/{id}', 'DropshipperController@update');
                    Route::post('destroy', 'DropshipperController@destroy');
                });
				
                Route::prefix('delivery_company')->group(function() {
                    Route::get('/', 'VendorController@index');
                    Route::get('datatable', 'VendorController@datatable');
                    Route::post('create', 'VendorController@create');
                    Route::get('show', 'VendorController@show');
                    Route::post('update/{id}', 'VendorController@update');
                    Route::post('destroy', 'VendorController@destroy');
                });

                Route::prefix('mode_of_transport')->group(function() {
                    Route::get('/', 'TransportController@index');
                    Route::get('datatable', 'TransportController@datatable');
                    Route::post('create', 'TransportController@create');
                    Route::get('show', 'TransportController@show');
                    Route::post('update/{id}', 'TransportController@update');
                    Route::post('destroy', 'TransportController@destroy');
                });

                Route::prefix('delivery_cost')->group(function() {
                    Route::get('/', 'PriceController@index');
                    Route::get('datatable', 'PriceController@datatable');
                    Route::post('create', 'PriceController@create');
                    Route::get('show', 'PriceController@show');
                    Route::post('update/{id}', 'PriceController@update');
                    Route::post('destroy', 'PriceController@destroy');
                });
            });

            Route::prefix('finance_accounting')->middleware('admin.role:1|2|3|4')->group(function() {
                Route::prefix('coa')->group(function() {
                    Route::get('/', 'CoaController@index');
                    Route::get('datatable', 'CoaController@datatable');
                    Route::post('create', 'CoaController@create');
					Route::get('show', 'CoaController@show');
                    Route::post('show', 'CoaController@show');
                    Route::post('update/{id}', 'CoaController@update');
                    Route::post('destroy', 'CoaController@destroy');
					Route::get('export','CoaController@export');
					Route::get('print','CoaController@print');
                });
            });
			
			Route::prefix('hrd')->middleware('admin.role:1|2|14')->group(function() {
                Route::prefix('asset')->group(function() {
                    Route::get('/', 'AssetController@index');
                    Route::get('datatable', 'AssetController@datatable');
                    Route::post('create', 'AssetController@create');
					Route::post('show', 'AssetController@show');
                    Route::post('destroy', 'AssetController@destroy');
                });
				
				Route::prefix('allowance')->group(function() {
                    Route::get('/', 'AllowanceController@index');
                    Route::get('datatable', 'AllowanceController@datatable');
                    Route::post('create', 'AllowanceController@create');
					Route::post('create_rule', 'AllowanceController@createRule');
					Route::post('show', 'AllowanceController@show');
					Route::post('show_rules', 'AllowanceController@showRules');
                    Route::post('destroy', 'AllowanceController@destroy');
					Route::post('destroy_rule', 'AllowanceController@destroyRule');
                });
				
				Route::prefix('time_management')->group(function() {
					Route::get('/', 'TimeController@index');
					Route::get('datatable_schedule', 'TimeController@datatableSchedule');
					Route::get('datatable_holiday', 'TimeController@datatableHoliday');
					Route::get('datatable_company', 'TimeController@datatableCompany');
					Route::post('create_schedule', 'TimeController@createSchedule');
					Route::post('create_holiday', 'TimeController@createHoliday');
					Route::post('create_company', 'TimeController@createCompany');
					Route::post('show_schedule', 'TimeController@showSchedule');
					Route::post('show_holiday', 'TimeController@showHoliday');
					Route::post('show_company', 'TimeController@showCompany');
					Route::post('destroy_schedule', 'TimeController@destroySchedule');
					Route::post('destroy_holiday', 'TimeController@destroyHoliday');
					Route::post('destroy_company', 'TimeController@destroyCompany');
				});
            });

            Route::prefix('digital')->middleware('admin.role:1|2|5|8')->group(function() {
                Route::prefix('banner')->group(function() {
                    Route::get('/', 'BannerController@index');
                    Route::get('datatable', 'BannerController@datatable');
                    Route::post('create', 'BannerController@create');
                    Route::get('show', 'BannerController@show');
                    Route::post('update/{id}', 'BannerController@update');
                    Route::post('destroy', 'BannerController@destroy');
                });

                Route::prefix('career')->group(function() {
                    Route::get('/', 'CareerController@index');
                    Route::get('datatable', 'CareerController@datatable');
                    Route::match(['get', 'post'], 'create', 'CareerController@create');
                    Route::match(['get', 'post'], 'update/{id}', 'CareerController@update');
                    Route::post('destroy', 'CareerController@destroy');
                    Route::get('detail/{id}', 'CareerController@detail');
                });

                Route::prefix('news_category')->group(function() {
                    Route::get('/', 'NewsCategoryController@index');
                    Route::get('datatable', 'NewsCategoryController@datatable');
                    Route::post('create', 'NewsCategoryController@create');
                    Route::get('show', 'NewsCategoryController@show');
                    Route::post('update/{id}', 'NewsCategoryController@update');
                    Route::post('destroy', 'NewsCategoryController@destroy');
                });

                Route::prefix('news')->group(function() {
                    Route::get('/', 'NewsController@index');
                    Route::get('datatable', 'NewsController@datatable');
                    Route::match(['get', 'post'], 'create', 'NewsController@create');
                    Route::match(['get', 'post'], 'update/{id}', 'NewsController@update');
                    Route::post('destroy', 'NewsController@destroy');
                    Route::get('detail/{id}', 'NewsController@detail');
                });
            });

            Route::prefix('voucher')->middleware('admin.role:1|2|5|8')->group(function() {
                Route::prefix('brand')->group(function() {
                    Route::get('/', 'VoucherBrandController@index');
                    Route::get('datatable', 'VoucherBrandController@datatable');
                    Route::match(['get', 'post'], 'create', 'VoucherBrandController@create');
                    Route::match(['get', 'post'], 'update/{id}', 'VoucherBrandController@update');
                    Route::post('destroy', 'VoucherBrandController@destroy');
                    Route::get('detail/{id}', 'VoucherBrandController@detail');
                });

                Route::prefix('category')->group(function() {
                    Route::get('/', 'VoucherCategoryController@index');
                    Route::get('datatable', 'VoucherCategoryController@datatable');
                    Route::match(['get', 'post'], 'create', 'VoucherCategoryController@create');
                    Route::match(['get', 'post'], 'update/{id}', 'VoucherCategoryController@update');
                    Route::post('destroy', 'VoucherCategoryController@destroy');
                    Route::get('detail/{id}', 'VoucherCategoryController@detail');
                });

                Route::prefix('global')->group(function() {
                    Route::get('/', 'VoucherGlobalController@index');
                    Route::get('datatable', 'VoucherGlobalController@datatable');
                    Route::match(['get', 'post'], 'create', 'VoucherGlobalController@create');
                    Route::match(['get', 'post'], 'update/{id}', 'VoucherGlobalController@update');
                    Route::post('destroy', 'VoucherGlobalController@destroy');
                    Route::get('detail/{id}', 'VoucherGlobalController@detail');
                });
            });

            Route::prefix('customer')->middleware('admin.role:1|5|6|10|11')->group(function() {
                Route::get('/', 'CustomerController@index');
                Route::get('datatable', 'CustomerController@datatable');
                Route::post('create', 'CustomerController@create');
                Route::post('show', 'CustomerController@show');
                Route::post('update/{id}', 'CustomerController@update');
                Route::post('destroy', 'CustomerController@destroy');
				Route::get('export','CustomerController@export');
				Route::get('print','CustomerController@print');
				Route::get('repair_balance','CustomerController@repairBalance');
            });
        });
		
		Route::prefix('inventory')->group(function() {
            Route::prefix('purchase')->middleware('admin.role:1|3|4|9')->group(function() {
				Route::get('/', 'PurchaseController@index');
				Route::get('datatable', 'PurchaseController@datatable');
				Route::post('create', 'PurchaseController@create');
				Route::post('create_proforma', 'PurchaseController@createProforma');
				Route::post('create_bill', 'PurchaseController@createBill');
				Route::post('create_production', 'PurchaseController@createProduction');
				Route::post('create_delivery', 'PurchaseController@createDelivery');
				Route::post('create_warehouse', 'PurchaseController@createWarehouse');
				Route::post('create_cost', 'PurchaseController@createCost');
				Route::get('show', 'PurchaseController@show');
				Route::get('get_proforma', 'PurchaseController@getProforma');
				Route::get('get_bill', 'PurchaseController@getBill');
				Route::get('get_warehouse', 'PurchaseController@getWarehouseReceive');
				Route::get('get_purchase_cost', 'PurchaseController@getPurchaseCost');
				Route::post('update/{id}', 'PurchaseController@update');
				Route::post('destroy', 'PurchaseController@destroy');
				Route::get('row_detail', 'PurchaseController@rowDetail');
				Route::get('/repair', 'PurchaseController@repair');
				Route::get('repair_cb', 'PurchaseController@RepairCBInventory');
				Route::get('get_wip_coa', 'PurchaseController@getPurchaseRequestFromCoa');
				Route::post('delete_cost', 'PurchaseController@deleteCost');
			});

            Route::prefix('request_quotation')->middleware('admin.role:1|3|4|9')->group(function() {
				Route::get('datatable', 'PurchaseController@datatableQuotation');
				Route::post('create', 'PurchaseController@createQuotation');
				Route::get('show', 'PurchaseController@show');
				Route::post('destroy', 'PurchaseController@destroyQuotation');
				Route::get('row_detail', 'PurchaseController@rowDetailQuotation');
				Route::get('get_quotation', 'PurchaseController@getPurchaseQuotation');
			});
			
			Route::prefix('stock')->middleware('admin.role:1|3|4|5|6|9')->group(function() {
				Route::get('/', 'InventoryStockController@index');
				Route::get('datatable', 'InventoryStockController@datatable');
				Route::get('row_detail', 'InventoryStockController@rowDetail');
				Route::get('row_detail_jkt', 'InventoryStockController@rowDetailJkt');
				Route::get('report/{branch}', 'InventoryStockController@report');
				Route::get('report_in_rp/{branch}', 'InventoryStockController@reportInRp');
				Route::get('report_real/{branch}', 'InventoryStockController@reportInReal');
				Route::get('report_real_in_rp/{branch}', 'InventoryStockController@reportInRealIdr');
			});

			Route::prefix('internal_memo')->middleware('admin.role:1|4|5|6|9|10|11')->group(function() {
				Route::get('/', 'InternalMemoController@index');
				Route::get('datatable', 'InternalMemoController@datatable');
				// Route::get('datatable', 'StockController@datatable');
				// Route::get('export','StockController@export');
				// Route::get('print','StockController@print');
				// Route::get('check','StockController@check');
			});
			
			Route::prefix('ventura')->middleware('admin.role:1|4|5|6|9|10|11')->group(function() {
				Route::get('/', 'StockController@index');
				Route::get('datatable', 'StockController@datatable');
				Route::get('export','StockController@export');
				Route::get('print','StockController@print');
				Route::get('check','StockController@check');
			});
			
			Route::prefix('transfer')->middleware('admin.role:1|3|4|9')->group(function() {
				Route::get('/', 'WarehouseTransferController@index');
				Route::get('datatable', 'WarehouseTransferController@datatable');
				Route::get('row_detail', 'WarehouseTransferController@rowDetail');
				Route::post('create', 'WarehouseTransferController@create');
				Route::post('change_status_sample', 'WarehouseTransferController@changeStatusSample');
				Route::get('get_product', 'WarehouseTransferController@getProduct');
				Route::get('get_purchase_product', 'WarehouseTransferController@getPurchaseProduct');
				Route::post('destroy', 'WarehouseTransferController@destroy');
				Route::get('print/{id}', 'WarehouseTransferController@print');
				Route::get('show', 'WarehouseTransferController@show');
			});
		});
		
        Route::prefix('sales')->middleware('admin.role:1|3|4|5|6|9|10|15')->group(function() {
			
			Route::prefix('budgeting_project')->middleware('admin.role:1|3|4|5|9')->group(function() {
				Route::get('/', 'BudgetingProjectController@index');
				Route::post('create', 'BudgetingProjectController@create');
				Route::get('datatable', 'BudgetingProjectController@datatable');
				Route::get('show', 'BudgetingProjectController@show');
				Route::post('update/{id}', 'BudgetingProjectController@update');
				Route::get('detail/{id}', 'BudgetingProjectController@detail');
				Route::get('project/{id}', 'BudgetingProjectController@project');
				Route::post('destroy', 'BudgetingProjectController@destroy');
				Route::post('updateEstimation', 'BudgetingProjectController@updateEstimation');
				Route::get('get_project_product', 'BudgetingProjectController@getProjectProduct');
			});

			Route::prefix('sample')->middleware('admin.role:1|3|4|5|6|9|11')->group(function() {
				Route::get('/', 'SampleController@index');
				Route::get('detail/{id}', 'SampleController@detail');
				Route::get('datatable', 'SampleController@datatable');
				Route::post('create', 'SampleController@create');
				Route::post('edit/{id}', 'SampleController@edit');
				Route::get('get_product', 'SampleController@getProduct');
				Route::post('update_status_sample', 'SampleController@updateStatusSample');
				Route::post('add_sample_proof', 'SampleController@addSampleProof');
				Route::post('approval', 'SampleController@approval');
				Route::get('get_delivery_tracking', 'SampleController@getDeliveryTracking');
				Route::post('add_delivery_tracking_note', 'SampleController@addDeliveryTrackingNote');
				Route::post('delete_delivery_tracking', 'SampleController@deleteDeliveryTracking');
				Route::get('print/{param}/{id}', 'SampleController@print');
				Route::post('destroy', 'SampleController@destroy');
				Route::post('add_shipment_tracking','SampleController@addShipmentTracking');
				Route::post('delete_shipment_tracking','SampleController@deleteShipmentTracking');
				Route::get('get_tracking_shipment','SampleController@getShipmentTracking');
				Route::get('get_sample_product', 'SampleController@getSampleProduct');
				Route::get('get_sales_info', 'SampleController@getSalesInfo');
				Route::get('get_supplier_currency', 'SampleController@getSupplierCurrency');
				Route::post('create_po_supplier/{id}', 'SampleController@createPOSupplier');
				Route::post('delete_purchase', 'SampleController@deletePurchase');
				Route::post('add_purchase_return','SampleController@addReturnPurchase');
				Route::post('delete_purchase_return', 'SampleController@deletePurchaseReturn');
				Route::get('get_purchase_info', 'SampleController@getPurchaseInfo');
				Route::post('create_proforma/{id}', 'SampleController@createProforma');
				Route::get('get_purchase_proforma', 'SampleController@getPurchaseProforma');
				Route::get('get_purchase_product', 'SampleController@getPurchaseProduct');
				Route::post('create_delivery_shipment/{id}', 'SampleController@createDeliveryShipment');
				Route::get('get_shipment_edit', 'SampleController@getShipmentEdit');
				Route::get('get_shipment_info', 'SampleController@getShipmentInfo');
				Route::get('get_shipment_product', 'SampleController@getShipmentProduct');
				Route::post('create_warehouse_received/{id}', 'SampleController@createWarehouseReceived');
				Route::get('get_warehouse_edit', 'SampleController@getWarehouseEdit');
				Route::post('create_sample_delivery/{id}', 'SampleController@createSampleDelivery');
				Route::post('create_sample_return/{id}', 'SampleController@createSampleReturn');
				Route::get('get_delivery_product', 'SampleController@getDeliveryProduct');
				Route::post('add_return_memo','SampleController@addReturnMemo');
				Route::get('get_delivery_info','SampleController@getDeliveryInfo');
				Route::post('get_delivery_note','SampleController@getDeliveryNote');
				Route::post('add_sales_notes','SampleController@addSalesNote');
				Route::post('add_received_date','SampleController@addReceivedDate');
				Route::post('add_received_proof','SampleController@addReceivedProof');
				Route::post('add_return_proof','SampleController@addReturnProof');
				Route::post('show_edit_sample/{id}','SampleController@showSampleEdit');
				Route::get('repair','SampleController@repairupdateGrandtotalWr');
				Route::get('repair_cb','SampleController@repairBranchCashBankWarehouseReceive');
				Route::get('delete_delivery','SampleController@deleteDelivery');
			});

			Route::prefix('service_cost')->middleware('admin.role:1|3|4|5|9|11')->group(function() {
				Route::get('/', 'ServiceCostController@index');
				Route::post('create', 'ServiceCostController@create');
				Route::get('show', 'ServiceCostController@show');
				Route::get('datatable', 'ServiceCostController@datatable');
				Route::get('print/{param}/{id}', 'ServiceCostController@print');
				Route::get('get_service_cost_journal', 'ServiceCostController@getServiceCostJournal');
				Route::get('get_service_cost_payment', 'ServiceCostController@getServiceCostPayment');
				Route::post('create_payment', 'ServiceCostController@createServiceCostPayment');
				Route::post('destroy', 'ServiceCostController@destroy');
				Route::post('desroy_payment', 'ServiceCostController@destroyPayment');
			});
			
            Route::prefix('project')->middleware('admin.role:1|3|4|5|6|9|10|15')->group(function() {
				Route::middleware('admin.role:1|3|4|5|6|9|10|15')->group(function() {
					Route::get('get_product', 'ProjectController@getProduct');
				});
				Route::middleware('admin.role:1|3|4|5|6|10')->group(function() {
					Route::get('/', 'ProjectController@index');
					Route::get('datatable', 'ProjectController@datatable');
					Route::post('create', 'ProjectController@create');
					Route::post('approval', 'ProjectController@approval');
					Route::get('row_detail_sales', 'ProjectController@rowDetailSales');
					Route::get('get_delivery', 'ProjectController@getDelivery');
					Route::get('print/{param}/{id}', 'ProjectController@print');
					Route::get('print/sales_report_1', 'ProjectController@printSalesReport');
					Route::get('print/sales_report_2', 'ProjectController@printSalesReport2');
					Route::post('print/sales_report_3', 'ProjectController@printSalesReport3');
					Route::get('printHtml/{param}/{id}', 'ProjectController@printHtml');
					Route::match(['get', 'post'], 'progress/{id}', 'ProjectController@progress');
					Route::get('detail/{id}', 'ProjectController@detail');
					Route::get('get_sales_product', 'ProjectController@getSalesProduct');
					Route::get('get_sales_info', 'ProjectController@getSalesInfo');
					Route::get('get_payment_info', 'ProjectController@getPaymentInfo');
					Route::get('get_shipment_info', 'ProjectController@getShipmentInfo');
					Route::get('get_shipment_product', 'ProjectController@getShipmentProduct');
					Route::get('get_purchase_info', 'ProjectController@getPurchaseInfo');
					Route::get('get_supplier_currency', 'ProjectController@getSupplierCurrency');
					Route::get('get_purchase_product', 'ProjectController@getPurchaseProduct');
					Route::get('get_shading_product', 'ProjectController@getShadingProduct');
					Route::post('update_status_sample', 'ProjectController@updateStatusSample');
					Route::post('skip_form', 'ProjectController@skipForm');
					Route::post('add_shipment_tracking','ProjectController@addShipmentTracking');
					Route::post('delete_shipment_tracking','ProjectController@deleteShipmentTracking');
					Route::get('get_tracking_shipment','ProjectController@getShipmentTracking');
					Route::get('get_tracking_delivery','ProjectController@getDeliveryTracking');
					Route::get('getShadingFromVentura','ProjectController@getShadingVentura');
					Route::post('add_delivery_tracking','ProjectController@addDeliveryTracking');
					Route::post('delete_delivery_tracking','ProjectController@deleteDeliveryTracking');
					Route::post('request_delete_project','ProjectController@requestDeleteProject')->middleware('admin.role:1|5');
					Route::post('delete_project','ProjectController@deleteProject')->middleware('admin.role:1|5');
					Route::post('delete_sales','ProjectController@deleteSales');
					Route::post('delete_payment','ProjectController@deletePayment');
					Route::post('add_project_note','ProjectController@addProjectNote');
					Route::get('get_project','ProjectController@getProject');
					Route::post('add_sample_proof','ProjectController@addSampleProof');
					Route::post('add_pictures','ProjectController@addPictures');
					Route::post('get_pictures','ProjectController@getPictures');
					Route::post('delete_picture','ProjectController@deletePictures');
					Route::post('add_return_memo','ProjectController@addReturnMemo');
					Route::get('get_return_memo','ProjectController@getReturnMemo');
					Route::post('reset_quotation','ProjectController@resetQuotation');
					Route::get('get_delivery_product','ProjectController@getDeliveryProduct');
					Route::post('get_sales_note','ProjectController@getSalesNote');
					Route::post('add_sales_notes','ProjectController@addSalesNote');
					Route::post('get_sales_order','ProjectController@getSalesOrder');
					Route::post('close_sales_order','ProjectController@closeSalesOrder');
					Route::post('delete_sample','ProjectController@deleteSample');
					Route::get('datatable_project_trip', 'ProjectController@datatableProjectTrip');
					Route::post('create_project_trip', 'ProjectController@createProjectTrip');
					Route::get('show_project_trip', 'ProjectController@showProjectTrip');
					Route::get('get_project_trip', 'ProjectController@getProjectTrip');
				});
            });

            Route::prefix('retail')->middleware('admin.role:1|3|4|5|6|10')->group(function() {
                Route::get('/', 'OrderController@index');
                Route::get('datatable', 'OrderController@datatable');
                Route::match(['get', 'post'], 'detail/{id}', 'OrderController@detail');
            });
			
			Route::prefix('in_store')->middleware('admin.role:1|5|9|15')->group(function() {
                Route::get('/', 'InStoreController@index');
				Route::post('create','InStoreController@create');
                Route::get('datatable', 'InStoreController@datatable');
                Route::match(['get', 'post'], 'detail/{id}', 'InStoreController@detail');
            });


			Route::prefix('field_trip')->middleware('admin.role:1|3|4|5|6|10')->group(function() {
                Route::get('/', 'FieldTripController@index');
                Route::get('datatable', 'FieldTripController@datatable');
                Route::post('create', 'FieldTripController@create');
                Route::post('update_reminder', 'FieldTripController@updateReminder');
                Route::post('show', 'FieldTripController@show');
            });
        });

        Route::prefix('invoice')->middleware('admin.role:1|3|4|5|10|11')->group(function() {
            Route::prefix('retail')->group(function() {
                Route::get('/', 'OrderInvoiceController@index');
                Route::get('datatable', 'OrderInvoiceController@datatable');
                Route::match(['get', 'post'], 'detail/{id}', 'OrderInvoiceController@detail');
                Route::match(['get', 'post'], 'print/{id}', 'OrderInvoiceController@print');
            });
        });

        Route::prefix('purchase_order')->middleware('admin.role:1|3|4|5|7|9')->group(function() {
			Route::prefix('project')->group(function() {
                Route::get('/', 'ProjectController@index');
                Route::get('datatable', 'ProjectController@datatable');
                Route::post('create', 'ProjectController@create');
				Route::post('delete_purchase', 'ProjectController@deletePurchase');
				Route::post('delete_purchase_return', 'ProjectController@deletePurchaseReturn');
				Route::post('approval', 'ProjectController@approval');
                Route::get('get_product', 'ProjectController@getProduct');
				Route::get('get_report', 'ProjectController@getReport');
				Route::get('get_purchase_bill', 'ProjectController@getPurchaseBill');
				Route::get('get_purchase_proforma', 'ProjectController@getPurchaseProforma');
				Route::get('row_detail_purchase', 'ProjectController@rowDetailPurchase');
                Route::get('get_delivery', 'ProjectController@getDelivery');
                Route::get('print/{param}/{id}', 'ProjectController@print');
				Route::get('printHtml/{param}/{id}', 'ProjectController@printHtml');
                Route::match(['get', 'post'], 'progress/{id}', 'ProjectController@progress');
                Route::get('detail/{id}', 'ProjectController@detail');
				Route::get('get_sales_product', 'ProjectController@getSalesProduct');
				Route::get('get_sales_info', 'ProjectController@getSalesInfo');
				Route::get('get_shipment_info', 'ProjectController@getShipmentInfo');
				Route::get('get_shipment_edit', 'ProjectController@getShipmentEdit');
				Route::get('get_warehouse_edit', 'ProjectController@getWarehouseEdit');
				Route::get('get_shipment_product', 'ProjectController@getShipmentProduct');
				Route::get('get_purchase_info', 'ProjectController@getPurchaseInfo');
				Route::get('get_supplier_currency', 'ProjectController@getSupplierCurrency');
				Route::get('get_purchase_product', 'ProjectController@getPurchaseProduct');
				Route::get('get_shading_product', 'ProjectController@getShadingProduct');
				Route::post('update_status_sample', 'ProjectController@updateStatusSample');
				Route::post('skip_form', 'ProjectController@skipForm');
				Route::post('add_shipment_tracking','ProjectController@addShipmentTracking');
				Route::post('delete_shipment_tracking','ProjectController@deleteShipmentTracking');
				Route::get('get_tracking_shipment','ProjectController@getShipmentTracking');
				Route::get('get_tracking_delivery','ProjectController@getDeliveryTracking');
				Route::get('getShadingFromVentura','ProjectController@getShadingVentura');
				Route::post('add_delivery_tracking','ProjectController@addDeliveryTracking');
				Route::post('delete_delivery_tracking','ProjectController@deleteDeliveryTracking');
				Route::post('email_tracking_shipment','ProjectController@emailTrackingShipment');
				Route::post('add_project_note','ProjectController@addProjectNote');
				Route::post('add_purchase_return','ProjectController@addReturnPurchase');
				Route::get('get_purchase_return','ProjectController@getPurchaseReturn');
				Route::get('get_warehouse_receive','ProjectController@getWarehouseReceive');
				Route::get('get_purchase_payment_info', 'ProjectController@getPurchasePaymentInfo');
				Route::post('delete_purchase_payment','ProjectController@deletePurchasePayment');
				Route::post('delete_purchase_bill','ProjectController@deletePurchaseBill');
				Route::post('add_purchase_bill','ProjectController@addPurchaseBill');
				Route::post('skip_po','ProjectController@skipPurchaseOrder');
				Route::get('get_purchase_bill_info', 'ProjectController@getPurchaseBillInfo');
				Route::post('update_status_note','ProjectController@updateStatusNote');
				Route::post('get_project_note','ProjectController@getPreProjectNote');
				Route::post('add_pre_project_note','ProjectController@addPreProjectNote');
				Route::post('add_new_coa','ProjectController@addNewCoa');
				Route::post('save_from_stock','ProjectController@saveFromStock');
				Route::post('delete_from_stock','ProjectController@deleteFromStock');
				Route::post('add_tax_document','ProjectController@addTaxDocument');
				Route::post('update_tax_document','ProjectController@updateTaxDocument');
				Route::post('get_tax_documents','ProjectController@getTaxDocument');
				Route::post('delete_tax_document','ProjectController@deleteTaxDocument');
            });
			
            Route::prefix('retail')->group(function() {
                Route::get('/', 'OrderPoController@index');
                Route::get('datatable', 'OrderPoController@datatable');
                Route::match(['get', 'post'], 'detail/{id}', 'OrderPoController@detail');
                Route::match(['get', 'post'], 'print/{id}', 'OrderPoController@print');
            });
        });

        Route::prefix('delivery_order')->middleware('admin.role:1|4|5|6|10|11|18')->group(function() {
			Route::prefix('project')->group(function() {
                Route::get('/', 'ProjectController@index');
                Route::get('datatable', 'ProjectController@datatable');
                Route::post('create', 'ProjectController@create');
				Route::post('approval', 'ProjectController@approval');
                Route::get('get_product', 'ProjectController@getProduct');
				Route::get('get_report', 'ProjectController@getReport');
				Route::get('get_report_tax', 'ProjectController@getReportTax');
				Route::get('get_items', 'ProjectController@getItems');
				Route::get('get_customer_deposit', 'ProjectController@getCustomerDeposit');
				Route::get('get_customer_delivery', 'ProjectController@getCustomerDelivery');
                Route::get('get_delivery', 'ProjectController@getDelivery');
				Route::get('row_detail_delivery', 'ProjectController@rowDetailDelivery');
				Route::get('print/{param}/{id}', 'ProjectController@print')->middleware('admin.role:1|4|5|6|10|11|18');
                Route::match(['get', 'post'], 'progress/{id}', 'ProjectController@progress');
                Route::get('detail/{id}', 'ProjectController@detail');
				Route::get('get_sales_product', 'ProjectController@getSalesProduct');
				Route::get('get_sales_info', 'ProjectController@getSalesInfo');
				Route::get('get_shipment_info', 'ProjectController@getShipmentInfo');
				Route::get('get_payment_info', 'ProjectController@getPaymentInfo');
				Route::get('get_shipment_product', 'ProjectController@getShipmentProduct');
				Route::get('get_purchase_info', 'ProjectController@getPurchaseInfo');
				Route::get('get_bill_edit', 'ProjectController@getBillEdit');
				Route::get('get_supplier_currency', 'ProjectController@getSupplierCurrency');
				Route::get('get_purchase_product', 'ProjectController@getPurchaseProduct');
				Route::get('get_shading_product', 'ProjectController@getShadingProduct');
				Route::post('update_status_sample', 'ProjectController@updateStatusSample');
				Route::post('skip_form', 'ProjectController@skipForm');
				Route::post('add_shipment_tracking','ProjectController@addShipmentTracking');
				Route::post('delete_shipment_tracking','ProjectController@deleteShipmentTracking');
				Route::get('get_tracking_shipment','ProjectController@getShipmentTracking');
				Route::get('get_tracking_delivery','ProjectController@getDeliveryTracking');
				Route::get('getShadingFromVentura','ProjectController@getShadingVentura');
				Route::post('add_delivery_tracking','ProjectController@addDeliveryTracking');
				Route::post('delete_delivery_tracking','ProjectController@deleteDeliveryTracking');
				Route::post('email_tracking_delivery','ProjectController@emailTrackingDelivery');
				Route::post('add_received_date','ProjectController@addReceivedDate');
				Route::post('add_received_proof','ProjectController@addReceivedProof');
				Route::post('add_project_bill','ProjectController@addProjectBill');
				Route::post('add_return_proof','ProjectController@addReturnProof');
				Route::get('get_delivery_product','ProjectController@getDeliveryProduct');
				Route::get('get_delivery_info','ProjectController@getDeliveryInfo');
				Route::post('update_status_note','ProjectController@updateStatusNote');
				Route::post('get_project_note','ProjectController@getPreProjectNote');
				Route::post('add_pre_project_note','ProjectController@addPreProjectNote');
				Route::post('get_delivery_note','ProjectController@getDeliveryNote');
				Route::post('add_multi_notes','ProjectController@addMultiNotes');
				Route::post('add_sales_notes','ProjectController@addSalesNote');
				Route::post('add_tax_document','ProjectController@addTaxDocument');
				Route::post('update_tax_document','ProjectController@updateTaxDocument');
				Route::post('get_tax_documents','ProjectController@getTaxDocument');
				Route::post('delete_tax_document','ProjectController@deleteTaxDocument');
				Route::post('delete_delivery','ProjectController@deleteDelivery');
				Route::get('get_edit_sales_retur','ProjectController@getSalesRetur');
            });
			
            Route::prefix('retail')->group(function() {
                Route::get('/', 'OrderDoController@index');
                Route::get('datatable', 'OrderDoController@datatable');
                Route::get('information', 'OrderDoController@information');
                Route::match(['get', 'post'], 'print/{id}', 'OrderDoController@print');
            });
			
			Route::prefix('project_payment')->group(function() {
                Route::get('/', 'ProjectPaymentController@index');
                Route::get('datatable', 'ProjectPaymentController@datatable');
				Route::get('get_bill', 'ProjectPaymentController@getBill');
				Route::post('create', 'ProjectPaymentController@create');
				Route::post('destroy', 'ProjectPaymentController@destroy');
				Route::get('row_detail', 'ProjectPaymentController@rowDetail');
				Route::get('show', 'ProjectPaymentController@show');
				Route::get('get_balance_payment', 'ProjectPaymentController@getBalanceCustomer');
				Route::get('get_payment_date', 'ProjectPaymentController@getPaymentDate');
            });

			Route::prefix('delivery_status')->group(function() {
                Route::get('/', 'DeliveryStatusController@index');
                Route::get('datatable', 'DeliveryStatusController@datatable');
            });

			
			Route::prefix('receivable_payment')->group(function() {
                Route::get('/', 'ReceivablePaymentController@index');
                Route::get('datatable', 'ReceivablePaymentController@datatable');
				Route::post('add_payment', 'ReceivablePaymentController@addPayment');
				Route::post('get_payment', 'ReceivablePaymentController@getPayment');
				Route::post('delete_payment', 'ReceivablePaymentController@deletePayment');
            });

			Route::prefix('recap_payment')->group(function() {
                Route::get('/', 'DeliveryStatusController@index');
                Route::get('datatable', 'DeliveryStatusController@datatable');
            });
        });

        Route::prefix('finance')->middleware('admin.role:1|2|3|4')->group(function() {
			
			Route::prefix('purchase_request')->group(function() {
                Route::get('/', 'PurchaseRequestController@index');
				Route::get('datatable', 'PurchaseRequestController@datatable');
				Route::get('row_detail', 'PurchaseRequestController@rowDetail');
				Route::post('update_status/{id}', 'PurchaseRequestController@updateStatus');
				Route::post('get_payment', 'PurchaseRequestController@getPayment');
				Route::post('add_payment', 'PurchaseRequestController@addPayment');
				Route::post('update_payment', 'PurchaseRequestController@updatePayment');
				Route::post('add_multi_payment', 'PurchaseRequestController@addMultiPayment');
				Route::post('delete_payment', 'PurchaseRequestController@deletePayment');
				Route::post('approve', 'PurchaseRequestController@approve');
				Route::post('reject', 'PurchaseRequestController@reject');
				Route::post('get_purchase_request_total', 'PurchaseRequestController@getTotal');
				Route::get('get_journal', 'PurchaseRequestController@getJournal');
				Route::post('print', 'PurchaseRequestController@print');
				Route::post('show_journal', 'PurchaseRequestController@showJournal');
				Route::post('show_payable_report', 'PurchaseRequestController@showPayableReport');
				Route::post('get_balance_cash_bank', 'PurchaseRequestController@getBalanceCashBank');
            });
			
			Route::prefix('payment_request')->group(function() {
                Route::get('/', 'PaymentRequestController@index');
				Route::post('create', 'PaymentRequestController@create');
				Route::get('datatable', 'PaymentRequestController@datatable');
				Route::get('row_detail', 'PaymentRequestController@rowDetail');
				Route::post('show', 'PaymentRequestController@show');
				Route::post('upload_proof', 'PaymentRequestController@uploadProof');
				Route::post('upload_proof_source', 'PaymentRequestController@uploadProofSource');
				Route::post('update/{id}', 'PaymentRequestController@update');
				Route::post('destroy', 'PaymentRequestController@destroy');
            });
			
			Route::prefix('payment_purchase')->group(function() {
                Route::get('/', 'PaymentPurchaseController@index');
				Route::get('datatable', 'PaymentPurchaseController@datatable');
				Route::get('datatable_multi', 'PaymentPurchaseController@datatableMulti');
				Route::get('row_detail', 'PaymentPurchaseController@rowDetail');
				Route::post('delete_payment', 'PaymentPurchaseController@deletePayment');
				Route::post('add_balance_cash_bank', 'PaymentPurchaseController@addBalance');
            });
			
			Route::prefix('receivable_payment')->group(function() {
                Route::get('/', 'ReceivablePaymentController@financeIndex');
				Route::get('datatable', 'ReceivablePaymentController@financeDatatable');
            });
			
			Route::prefix('balance_cash_bank')->group(function() {
                Route::get('/', 'BalancePettyCashController@index');
				Route::get('datatable', 'BalancePettyCashController@datatable');
				Route::post('create', 'BalancePettyCashController@create');
				Route::post('create_cb', 'BalancePettyCashController@createCb');
				Route::post('create_transfer', 'BalancePettyCashController@createTransfer');
				Route::get('show', 'BalancePettyCashController@show');
				Route::post('print', 'BalancePettyCashController@print');
				Route::post('destroy', 'BalancePettyCashController@destroy');
				Route::get('row_detail', 'BalancePettyCashController@rowDetail');
				Route::post('show_detail', 'BalancePettyCashController@showDetail');
				Route::post('show_approved', 'BalancePettyCashController@showApproved');
				Route::post('get_balance_cash_bank', 'BalancePettyCashController@getBalanceCashBank');
            });
			
            Route::prefix('cash_bank')->group(function() {
                Route::get('/', 'CashBankController@index');
                Route::get('suggest_code', 'CashBankController@suggestCode');
                Route::get('row_detail', 'CashBankController@rowDetail');
                Route::get('datatable', 'CashBankController@datatable');
                Route::post('create', 'CashBankController@create');
                Route::get('show', 'CashBankController@show');
				Route::get('get_code', 'CashBankController@getCode');
				Route::get('project', 'CashBankController@getProject');
                Route::post('update/{id}', 'CashBankController@update');
				Route::get('print/{id}', 'CashBankController@print');
                Route::post('destroy', 'CashBankController@destroy');
				Route::post('create_prf', 'CashBankController@createPrf');
            });
			
			Route::prefix('cash_flow')->group(function() {
                Route::get('/', 'CashFlowController@index');
				Route::post('change_date', 'CashFlowController@changeDate');
				Route::post('save', 'CashFlowController@save');
				Route::post('save_debit', 'CashFlowController@saveDebit');
            });
        });

        Route::prefix('accounting')->middleware('admin.role:1|2|3|4|5|9')->group(function() {
            Route::prefix('budgeting')->middleware('admin.role:1|2|3|4')->group(function() {
                Route::get('/', 'BudgetingController@index');
                Route::get('datatable', 'BudgetingController@datatable');
				Route::match(['get', 'post'], 'yearly', 'BudgetingController@yearly');
                Route::post('create', 'BudgetingController@create');
                Route::get('show', 'BudgetingController@show');
				Route::get('row_detail', 'BudgetingController@rowDetail');
                Route::post('update/{id}', 'BudgetingController@update');
                Route::post('destroy', 'BudgetingController@destroy');
            });
        });

        Route::prefix('report')->group(function() {
			Route::prefix('project')->group(function() {
				Route::prefix('details')->middleware('admin.role:1|5|18')->group(function() {
					Route::get('/', 'ReportProjectController@index');
					Route::get('datatable', 'ReportProjectController@datatable');
					Route::post('create', 'ReportProjectController@create');
					Route::post('approval', 'ReportProjectController@approval');
					Route::get('get_product', 'ReportProjectController@getProduct');
					Route::get('get_delivery', 'ReportProjectController@getDelivery');
					Route::get('print/{param}/{id}', 'ReportProjectController@print');
					Route::get('printHtml/{param}/{id}', 'ReportProjectController@printHtml');
					Route::match(['get', 'post'], 'progress/{id}', 'ReportProjectController@progress');
					Route::get('detail/{id}', 'ReportProjectController@detail');
					Route::get('get_sales_product', 'ReportProjectController@getSalesProduct');
					Route::get('get_sales_info', 'ReportProjectController@getSalesInfo');
					Route::get('get_shipment_info', 'ReportProjectController@getShipmentInfo');
					Route::get('get_shipment_product', 'ReportProjectController@getShipmentProduct');
					Route::get('get_purchase_info', 'ReportProjectController@getPurchaseInfo');
					Route::get('get_supplier_currency', 'ReportProjectController@getSupplierCurrency');
					Route::get('get_purchase_product', 'ReportProjectController@getPurchaseProduct');
					Route::get('get_shading_product', 'ReportProjectController@getShadingProduct');
					Route::post('update_status_sample', 'ReportProjectController@updateStatusSample');
					Route::post('skip_form', 'ReportProjectController@skipForm');
					Route::post('add_shipment_tracking','ReportProjectController@addShipmentTracking');
					Route::post('delete_shipment_tracking','ReportProjectController@deleteShipmentTracking');
					Route::get('get_tracking_shipment','ReportProjectController@getShipmentTracking');
					Route::get('get_tracking_delivery','ReportProjectController@getDeliveryTracking');
					Route::get('getShadingFromVentura','ReportProjectController@getShadingVentura');
					Route::post('add_delivery_tracking','ReportProjectController@addDeliveryTracking');
					Route::post('delete_delivery_tracking','ReportProjectController@deleteDeliveryTracking');
				});
				
				Route::prefix('summary')->middleware('admin.role:1|3|4|5|11|18')->group(function() {
					Route::get('/', 'ReportProjectSummaryController@index');
					Route::post('report', 'ReportProjectSummaryController@report');
				});
				
				Route::prefix('sales_chart')->middleware('admin.role:1|3|4|5|18')->group(function() {
					Route::get('/', 'ReportProjectSalesChartController@index');
					Route::post('generate', 'ReportProjectSalesChartController@generate');
				});
            });
			
			Route::prefix('inventory')->middleware('admin.role:1|3|4|5|6|9|10|18|19')->group(function() {
				Route::prefix('product_inventory')->middleware('admin.role:1|3|4|5|9|19')->group(function() {
                    Route::get('/', 'ReportInventoryController@index');
					Route::post('report', 'ReportInventoryController@report');
					Route::get('datatable', 'ReportInventoryController@datatable');
					Route::get('detail/{id}', 'ReportInventoryController@detail');
					Route::get('print', 'ReportInventoryController@print');
					Route::get('print_by_warehouse', 'ReportInventoryController@printByWarehouse');
					Route::get('export_by_warehouse', 'ReportInventoryController@exportByWarehouse');
					Route::get('print_card_mode', 'ReportInventoryController@printCardMode');
                });

				Route::prefix('stock_card')->middleware('admin.role:1|3|4|5|6|9|10|19')->group(function() {
                    Route::get('/', 'ReportInventoryController@index');
					Route::get('datatable', 'ReportInventoryController@stockdatatable');
					Route::get('detail/{id}', 'ReportInventoryController@detail');
					Route::post('print', 'ReportInventoryController@card');
                });
			});
			
			Route::prefix('purchase_order')->middleware('admin.role:1|2|4|7|9|11')->group(function() {
				Route::prefix('project')->group(function() {
                    Route::get('/', 'ReportProjectController@purchaseOrderReport');
					Route::get('datatable', 'ReportProjectController@datatable_purchase');
					Route::get('detail/{id}', 'ReportProjectController@detailPurchaseOrderReport');
                });
				
				Route::prefix('warehouse_receive')->group(function() {
                    Route::get('/', 'ReportProjectController@warehouseReceiveReport');
					Route::get('datatable', 'ReportProjectController@datatableWarehouseReceive');
				});
			});

			Route::prefix('delivery_order')->middleware('admin.role:1|2|3|4|7|9|11|18')->group(function() {
				Route::prefix('invoice_delivery_status')->middleware('admin.role:1|2|3|4|7|9|11|18')->group(function() {
					Route::get('/', 'ReportDeliveryController@invoiceDeliveryStatus');
					Route::get('datatable', 'ReportDeliveryController@datatableInvoice');
					Route::get('print/{param}', 'ReportDeliveryController@print');
				});

				Route::prefix('payment')->group(function() {
                    Route::get('/', 'ReportDeliveryController@projectPaymentReport');
                    Route::get('datatable', 'ReportDeliveryController@datatable');
                    Route::get('row_detail', 'ReportDeliveryController@rowDetail');
                    Route::get('get_detail_product', 'ReportDeliveryController@showDetailproduct');
                });

				Route::prefix('bill_status')->group(function() {
                    Route::get('/', 'ReportDeliveryController@billStatus');
                    Route::get('datatable', 'ReportDeliveryController@datatableBill');
                    Route::get('print/{param}', 'ReportDeliveryController@print');
                });
			});
			
			Route::prefix('finance')->middleware('admin.role:1|2|3|4|9|11|18')->group(function() {
				Route::prefix('outstanding_a_r')->middleware('admin.role:1|2|3|4|11|18')->group(function() {
                    Route::get('/', 'ReportAccountingController@outstandingAR');
					Route::get('print/{param}', 'ReportAccountingController@print');
					Route::get('export/{param}', 'ReportAccountingController@export');
                });
				Route::prefix('outstanding_a_p')->middleware('admin.role:1|2|3|4|9')->group(function() {
                    Route::get('/', 'ReportAccountingController@outstandingAP');
					Route::get('export/{param}', 'ReportAccountingController@export');
                });
				Route::prefix('outstanding_a_p_other')->middleware('admin.role:1|2|3|4|9')->group(function() {
                    Route::get('/', 'ReportAccountingController@outstandingAPOther');
					Route::get('export/{param}', 'ReportAccountingController@export');
                });
			});
			
            Route::prefix('accounting')->middleware('admin.role:1|2|3|4|11|18')->group(function() {
                Route::prefix('balance_sheet')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@balanceSheet');
                });
				
				Route::prefix('coa_cheat')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@coaCheat');
                });
				
				Route::prefix('aging_receivable')->middleware('admin.role:1|2|3|4|11|18')->group(function() {
                    Route::get('/', 'ReportAccountingController@agingReceivable');
					Route::post('card_detail', 'ReportAccountingController@agingReceivableCardDetail');
					Route::post('card', 'ReportAccountingController@agingReceivableCard');
                });
				
				Route::prefix('aging_payable')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@agingPayable');
					Route::post('card', 'ReportAccountingController@agingPayableCard');
                });

				Route::prefix('ar_customer')->middleware('admin.role:1|2|3|4|11|18')->group(function() {
                    Route::get('/', 'ReportAccountingController@accountReceivableCustomer');
                    Route::get('datatable', 'ReportAccountingController@datatableArCustomer');
                    Route::get('detail/{id}', 'ReportAccountingController@showARCustomerDetail');
					Route::get('get_detail_product', 'ReportAccountingController@showDetailProduct');
					Route::get('get_detail_payment', 'ReportAccountingController@showDetailPayment');
					Route::post('unpaid_receivable_card', 'ReportAccountingController@unpaidReceivableCard');
                });
				
				Route::prefix('profit_loss')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@profitLoss');
                });
				
                Route::prefix('profit_loss_comparison')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@profitLossComparison');
                });
				
				Route::prefix('profit_loss_project')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@profitLossProjectIndex');
					Route::post('get_result', 'ReportAccountingController@profitLossProjectResult');
					Route::post('get_project_journal', 'ReportAccountingController@getProjectJournal');
					Route::post('get_non_project_journal', 'ReportAccountingController@getNonProjectJournal');
                });

                Route::prefix('ledger')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@ledger');
                    Route::get('datatable', 'ReportAccountingController@ledgerDatatable');
                    Route::get('row_detail', 'ReportAccountingController@ledgerRowDetail');
                });

                Route::prefix('trial_balance')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@trialBalance');
                    Route::get('datatable', 'ReportAccountingController@trialBalanceDatatable');
                });
				
				Route::prefix('cash_bank')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@cashBank');
                    Route::post('detail', 'ReportAccountingController@cashBankDetail');
                    Route::post('upload_file', 'ReportAccountingController@cashBankUploadFile');
                });

				Route::prefix('cash_flow')->middleware('admin.role:1|2|3|4')->group(function() {
                    Route::get('/', 'ReportAccountingController@cashFlow');
                    Route::get('get_cash_flow', 'ReportAccountingController@generateCashFlow');
                    Route::get('get_detail_cash_flow', 'ReportAccountingController@getDetailCashFlow');
                });
				
                Route::match(['get', 'post'], 'budgeting_comparison', 'ReportAccountingController@budgetingComparison');
                Route::match(['get', 'post'], 'project_comparison', 'ReportAccountingController@projectComparison');
				
            });
        });

        Route::prefix('hrd')->middleware('admin.role:1|14')->group(function() {
            Route::prefix('job_desc')->group(function() {
                Route::get('/', 'JobDescController@index');
            });
			
			Route::prefix('employee')->group(function() {
                Route::get('/', 'EmployeeController@index');
				Route::get('datatable', 'EmployeeController@datatable');
				
				Route::prefix('employment')->group(function() {
					Route::get('{id}', 'EmploymentController@index');
					Route::get('{id}/datatable', 'EmploymentController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17');
					Route::post('create','EmploymentController@create');
					Route::post('show', 'EmploymentController@show');
					Route::post('destroy', 'EmploymentController@destroy');
				});
				
				Route::prefix('family')->group(function() {
					Route::get('{id}', 'FamilyController@index');
					Route::get('{id}/datatable', 'FamilyController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17');
					Route::post('create','FamilyController@create');
					Route::post('show', 'FamilyController@show');
					Route::post('destroy', 'FamilyController@destroy');
				});
				
				Route::prefix('education')->group(function() {
					Route::get('{id}', 'EducationController@index');
					Route::get('{id}/datatable', 'EducationController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17');
					Route::post('create','EducationController@create');
					Route::post('show', 'EducationController@show');
					Route::post('destroy', 'EducationController@destroy');
				});
				
				Route::prefix('experience')->group(function() {
					Route::get('{id}', 'ExperienceController@index');
					Route::get('{id}/datatable', 'ExperienceController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17');
					Route::post('create','ExperienceController@create');
					Route::post('show', 'ExperienceController@show');
					Route::post('destroy', 'ExperienceController@destroy');
				});
				
				Route::prefix('asset')->group(function() {
					Route::get('{id}', 'AssetEmployeeController@index');
					Route::get('{id}/datatable', 'AssetEmployeeController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17');
					Route::post('create','AssetEmployeeController@create');
					Route::post('show', 'AssetEmployeeController@show');
					Route::post('destroy', 'AssetEmployeeController@destroy');
					Route::post('update_return', 'AssetEmployeeController@updateReturn');
				});
				
				Route::prefix('loan')->group(function() {
					Route::get('{id}', 'LoanController@index');
					Route::get('{id}/datatable', 'LoanController@datatable')->withoutMiddleware('admin.role:1|14')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16');
					Route::post('create','LoanController@create');
					Route::post('show', 'LoanController@show');
					Route::post('destroy', 'LoanController@destroy');
					Route::get('{user}/row_detail', 'LoanController@rowDetail');
					Route::post('payment/destroy', 'LoanController@destroyPayment');
				});
            });
			
			Route::prefix('files')->group(function() {
                Route::get('/', 'FolderController@hrdIndex');
				Route::get('datatable', 'FolderController@hrdDatatable');
				Route::get('datatable_detail', 'FolderController@hrdDatatableDetail');
                Route::get('row_detail', 'FolderController@hrdRowDetail');
				Route::get('detail/{id}', 'FolderController@hrdDetail');
				Route::get('{id}/download', 'FolderController@downloadFile');
            });
			
			Route::prefix('attendance')->group(function() {
				Route::get('/', 'AttendanceController@hrdIndex');
				Route::post('create', 'AttendanceController@create');
				Route::post('create_rule', 'AttendanceController@createRule');
				Route::get('datatable', 'AttendanceController@hrdDatatable');
				Route::get('row_detail', 'AttendanceController@hrdRowDetail');
				Route::post('print', 'AttendanceController@print');
			});
			
			Route::prefix('salary')->group(function() {
                Route::get('/', 'SalaryController@index');
				Route::post('create','SalaryController@create');
				Route::get('datatable', 'SalaryController@datatable');
				Route::post('get_employee_salary','SalaryController@getEmployeeSalary');
				Route::post('get_info_allowance','SalaryController@getInfoAllowance');
				Route::post('get_info_cutting','SalaryController@getInfoCutting');
				Route::post('get_info_loan','SalaryController@getInfoLoanCredit');
				Route::post('show', 'SalaryController@show');
				
				Route::prefix('employee_allowance')->group(function() {
					Route::get('/', 'SalaryController@indexEmployeeAllowance');
					Route::get('datatable', 'SalaryController@datatableEmployeeAllowance');
					Route::post('create','SalaryController@createEmployeeAllowance');
					Route::get('row_detail', 'SalaryController@rowDetailEmployeeAllowance');
					Route::post('show', 'SalaryController@showEmployeeAllowance');
				});
            });
			
			Route::prefix('commission')->group(function() {
                Route::get('/', 'CommissionController@index');
            });
        });
		
		Route::prefix('downloads')->group(function() {
            Route::get('/', 'DownloadController@index');
        });
		
		Route::prefix('legal_docs')->middleware('admin.role:1|3|4|5|9|10|16')->group(function() {
			Route::prefix('company')->group(function() {
				Route::get('/', 'CompanyLegalityController@index');
				Route::post('add_files','CompanyLegalityController@addFiles');
				Route::post('delete_file','CompanyLegalityController@deleteFile');
				Route::post('destroy_category','CompanyLegalityController@destroyCategory');
				Route::post('delete_files','CompanyLegalityController@deleteFiles');
				Route::get('download/{id}','CompanyLegalityController@downloadFile');
				Route::get('download_files','CompanyLegalityController@downloadFiles');
				Route::post('send_mail','CompanyLegalityController@sendMail');
				Route::post('change_category','CompanyLegalityController@changeCategory');
				Route::post('create_category','CompanyLegalityController@createCategory');
				Route::post('find','CompanyLegalityController@find');
				Route::get('get_history_email','CompanyLegalityController@getHistoryEmail');
				Route::get('datatable', 'CompanyLegalityController@datatable');
			});
        });

        Route::prefix('setting')->middleware('admin.role:1|4')->group(function() {
			Route::prefix('version')->middleware('admin.role:1')->group(function() {
				Route::get('/', 'VersionController@index');
				Route::get('datatable', 'VersionController@datatable');
				Route::post('create', 'VersionController@create');
				Route::get('show', 'VersionController@show');
				Route::post('get_latest_version', 'VersionController@getLatestVersion')->withoutMiddleware('admin.role:1')->middleware('admin.role:1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16');
				Route::post('destroy', 'VersionController@destroy');
			});
			
            Route::prefix('user')->middleware('admin.role:1')->group(function() {
                Route::get('/', 'UserController@index');
                Route::get('datatable', 'UserController@datatable');
                Route::get('row_detail', 'UserController@rowDetail');
                Route::post('create', 'UserController@create');
                Route::get('show', 'UserController@show');
                Route::post('update/{id}', 'UserController@update');
                Route::post('destroy', 'UserController@destroy');
                Route::post('reset_password', 'UserController@resetPassword');
            });
			
			Route::prefix('accounting')->middleware('admin.role:1|4')->group(function() {
                Route::get('/', 'AccountingController@index');
                Route::get('datatable', 'AccountingController@datatable');
				Route::get('datatable_daily', 'AccountingController@datatableDaily');
                Route::post('create', 'AccountingController@create');
                Route::post('create_daily', 'AccountingController@createDaily');
                Route::get('show', 'AccountingController@show');
                Route::post('destroy', 'AccountingController@destroy');
                Route::post('destroy_daily', 'AccountingController@destroyDaily');
				Route::post('update_status', 'AccountingController@updateStatus');
				Route::get('get_latest_month', 'AccountingController@getLatestMonth');
            });
        });
    });
});

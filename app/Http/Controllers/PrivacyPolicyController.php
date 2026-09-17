<?php
 
 namespace App\Http\Controllers;
 
 class PrivacyPolicyController extends Controller
 {
     public function index()
     {
        return view('privacy-policy', [
             'page_title' => 'Privacy Policy',
             'company_name' => config('app.name', 'Sadhna Weekly'),
            'contact_email' => config('mail.from.address') ?: 'info@sadhanaweekly.co.in',
            'effective_date' => '17 September 2026',
        ]);
     }
}

<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class VNPayController extends Controller
{
   public function createPayment(Request $request)
    {
        $data = $request->all();
        $vnp_TmnCode = 'MQFTBTCN';
        $vnp_HashSecret = 'LTL2LRVB30RVQL4SPGN5DFKY4GTL6QL4';
        $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $vnp_Returnurl = 'http://127.0.0.1:8000/vnpay-return';

        $vnp_TxnRef = $data['order_number']; 
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $data['total']*100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];


        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        // dd($inputData);
         if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
          
         ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
         $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
         $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
            );
         if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
            } else {
            echo json_encode($returnData);
            }
       
    } 

public function vnpayReturn(Request $request)
{
    $inputData = $request->except(['vnp_SecureHash', 'vnp_SecureHashType']);
    ksort($inputData);
    
    $hashData = urldecode(http_build_query($inputData));
    $secureHash = hash_hmac('sha512', $hashData, 'LTL2LRVB30RVQL4SPGN5DFKY4GTL6QL4');
    
    if ($secureHash === $request->vnp_SecureHash || $request->vnp_ResponseCode == '00') {
        
        $order = Order::where('order_number', $request->vnp_TxnRef)->first();
        if ($order) {
            $order->payment_status = '1';
            $order->save();
            return redirect()->route('shop.orders.show', $order->id)
                ->with('success', 'Thanh toán VNPay thành công!');
        }
    }
    return redirect()->route('shop.orders.index')
        ->with('error', 'Thanh toán thất bại hoặc sai chữ ký!');
}
}
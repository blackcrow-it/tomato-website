<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GHTK
{
    private $token;
    private $apiEndpoint;

    public function __construct()
    {
        $this->token = config('ghtk.token');
        $this->apiEndpoint = config('ghtk.endpoint');
    }

    /**
     * Hàm tính phí ship của GHTK.
     *
     * @param  string  $pickProvince Tên tỉnh/thành phố nơi lấy hàng hóa
     * @param  string  $pickDistrict Tên quận/huyện nơi lấy hàng hóa
     * @param  string  $province Tên tỉnh/thành phố của người nhận hàng hóa
     * @param  string  $district Tên quận/huyện của người nhận hàng hóa
     * @param  int  $weight Cân nặng của gói hàng, đơn vị sử dụng Gram
     * @param  int  $price Giá trị thực của đơn hàng áp dụng để tính phí bảo hiểm, đơn vị sử dụng VNĐ
     * @param  string  $transport Phương thức vâng chuyển road ( bộ ) , fly (bay). Nếu phương thức vận chuyển không hợp lệ thì GHTK sẽ tự động nhảy về PTVC mặc định
     * @param  bool  $xfast Sử dụng phương thức vận chuyển xfast. true=xteam, false=none
     * @return mixed Trả về dữ liệu API
     */
    public function getShipmentFee(string $pickProvince, string $pickDistrict, string $province, string $district, int $weight, int $price, string $transport, bool $xfast) {
        error_log($this->apiEndpoint);
        $deliverOption = 'none';
        if ($xfast) {
            $deliverOption = 'xteam';
        }
        $response = Http::withHeaders([
            'Token' => $this->token
        ])
        ->acceptJson()
        ->get($this->apiEndpoint.'/services/shipment/fee', [
            'pick_province' => $pickProvince,
            'pick_district' => $pickDistrict,
            'province' => $province,
            'district' => $district,
            'weight' => $weight,
            'value' => $price,
            'transport' => $transport,
            'deliver_option' => $deliverOption,
        ])
        ->json();
        return $response;
    }
}

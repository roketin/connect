<?php

namespace Roketin;

class RVoucher extends Roketin
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $code
     * @param $voucher_type
     * @return mixed
     */
    public function check($code, $voucher_type = null)
    {
        return $this->callAPI("product/vouchers/check", compact('code', 'voucher_type'), "POST");
    }

    /**
     * @param $voucher_code
     * @param $voucher_type
     * @param $used_by
     * @return mixed
     */
    public function invalidate($voucher_code, $voucher_type, $used_by)
    {
        return $this->callAPI("product/vouchers/check", ["code" => $voucher_code, "voucher_type" => "voucher_type", "redemeer_id" => $used_by], "POST");
    }
}

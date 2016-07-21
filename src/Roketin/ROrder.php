<?php

namespace Roketin;

use Carbon\Carbon;
// use Intervention\Image\ImageManagerStatic as Image;
use Intervention\Image\Image;

class ROrder extends Roketin
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param array $generalData
     * @param array $customerData
     * @param array $products
     * @param array $bcc
     * @return mixed
     */
    public function create(array $generalData, array $customerData, array $products, $bcc = null)
    {
        if (!is_null($bcc)) {
            $params = array_merge(["general" => $generalData], ["customer_data" => $customerData], ["product_detail" => $products]);
        } else {
            $params = array_merge(["general" => $generalData], ["customer_data" => $customerData], ["product_detail" => $products], ['bcc' => $bcc]);
        }

        return $this->callAPI("order", $params, "POST");
    }

    /**
     * @param $number
     * @return mixed
     */
    public function void($number)
    {
        return $this->callAPI("order/void", ["invoice_number" => $number], "POST");
    }

    /**
     * @param $invoice_number
     * @param $payment_type
     * @param $total
     * @param $customer_name
     * @param $transaction_number
     * @param Image $image
     * @param null $bank_account
     * @param null $paid_date
     * @param null $bcc
     * @return mixed
     */
    public function confirm($invoice_number, $payment_type, $total, $customer_name, $customer_bank = null, $transaction_number, Image $image = null, $bank_account = null, $paid_date = null, $bcc = null)
    {
        if (in_array($payment_type, ["CASH", "TRANSFER"]) && is_null($image)) {
            throw new \Exception("Image must be present", 422);
        }
        if (!is_null($image)) {
            if (!in_array($image->mime(), ['image/png', 'image/jpeg', 'image/jpg'])) {
                throw new \Exception("Image must be png, jpeg, or jpg", 422);
            }
            $image = $image->encode('data-url')->encoded;
        }
        $paid_on = is_null($paid_date) ? Carbon::now() : $paid_date;
        $name    = $customer_name;
        return $this->callAPI("payment/confirm", compact('invoice_number', 'payment_type', 'total', 'name', 'customer_bank', 'transaction_number', 'image', 'paid_on', 'bank_account', 'bcc'), "POST");
    }
}

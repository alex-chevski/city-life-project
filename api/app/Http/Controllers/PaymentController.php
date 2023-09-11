<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner\Banner;
use Carbon\Carbon;
use DomainException;
use Idma\Robokassa\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * undocumented class variable.
     * @var Payment
     */
    private $robokassa;

    public function __construct(Payment $robokassa)
    {
        $this->robokassa = $robokassa;
    }

    public function result(Request $request)
    {
        if (!$this->robokassa->validateResult($request->all())) {
            throw new DomainException('bad sign');
        }

        $banner = Banner::findOrFail($this->robokassa->getInvoiceId());

        if ($this->robokassa->getSum() !== $banner->cost) {
            throw new DomainException('pay is not valid');
        }

        $banner->pay(Carbon::now());

        return 'OK' . $this->robokassa->getInvoiceId();
    }

    public function success(Request $request): void {}

    public function fail(Request $request): void {}
}

<?php

declare(strict_types=1);

namespace App\Console\Commands\Advert;

use App\Models\Adverts\Advert\Advert;
use App\UseCases\Adverts\AdvertService;
use Carbon\Carbon;
use DomainException;
use Illuminate\Console\Command;

class ExpireCommand extends Command
{
    protected $signature = 'advert:expire';

    private $service;

    public function __construct(AdvertService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle(): bool
    {
        $success = true;

        // logic notify mail user

        // to close adverts expired
        foreach (Advert::active()->where('expired_at', '<', Carbon::now())->cursor() as $advert) {
            try {
                $this->service->expire($advert);
            } catch (DomainException $e) {
                $this->error($e->getMessage());
                $success = false;
            }
        }

        return $success;
    }
}

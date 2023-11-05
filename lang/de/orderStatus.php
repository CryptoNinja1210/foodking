<?php

use App\Enums\OrderStatus;

return [
    OrderStatus::PENDING          => 'Ausstehend',
    OrderStatus::ACCEPT           => 'Akzeptieren',
    OrderStatus::PROCESSING       => 'In Bearbeitung',
    OrderStatus::OUT_FOR_DELIVERY => 'Zur Lieferung bereit',
    OrderStatus::DELIVERED        => 'Geliefert',
    OrderStatus::CANCELED         => 'Storniert',
    OrderStatus::REJECTED         => 'Abgelehnt',
    OrderStatus::RETURNED         => 'Zurückgegeben',


];

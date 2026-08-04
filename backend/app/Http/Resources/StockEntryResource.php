<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'barang' => $this->whenLoaded('item', fn () => $this->item->barang, $this->item?->barang),
            'omega' => $this->whenLoaded('item', fn () => $this->item->omega, $this->item?->omega),
            'lokasi' => $this->lokasi,
            'isi' => $this->isi,
            'tapel' => $this->tapel,
            'tinggi' => $this->tinggi,
            'sisa' => $this->sisa,
            'cttn' => $this->cttn,
        ];
    }
}

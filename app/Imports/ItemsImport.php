<?php

namespace App\Imports;

use App\Models\ImportedItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;

class ItemsImport implements ToModel, WithHeadingRow
{
    public $batch_id;

    public function __construct()
    {
        $this->batch_id = Uuid::uuid4()->getHex();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportedItem([
            'item_name'         => $row['item_name'],
            'brand_name'        => $row['brand_name'],
            'category_name'     => $row['category_name'],
            'item_description'  => $row['item_description'],
            'price'             => $row['price'],
            'weight'            => $row['weight'],
            'unit'              => $row['unit'],
            'width'             => $row['width'],
            'dimension'         => $row['dimension'],
            'height'            => $row['height'],
            'batch_id'          => $this->batch_id,
        ]);
    }
}

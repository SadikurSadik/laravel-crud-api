<?php


namespace App\Services\Product;


use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService extends BaseService
{
    public function all(array $filters = [])
    {
        $query = Product::query();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['q'])) {
            $query->where(DB::raw('LOWER(name)'), 'LIKE', '%'. strtolower($filters['q']) .'%');
        }

        $limit = Arr::get($filters, 'limit', 20);

        return $limit != '-1' ? $query->paginate($limit) : $query->get();
    }

    public function getById($id)
    {
        return Product::find($id);
    }

    public function store(array $data)
    {
        return $this->saveProduct($data);
    }

    public function update($id, array $data)
    {
        return $this->saveProduct($data, $id);
    }

    public function destroy($id)
    {
        return Product::find($id)->delete();
    }

    private function saveProduct($data, $id = null)
    {
        $product = Product::findOrNew($id);
        $product->fill($data);
        $product->save();

        return $product;
    }
}

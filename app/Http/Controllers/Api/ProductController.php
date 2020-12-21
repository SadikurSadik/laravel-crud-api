<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Mockery\Exception;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->service->all(array_merge($request->all(), [
            'user_id' => auth()->user()->role == Role::USER_ROLE ? auth()->user()->id : ''
        ]));

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['title', 'description', 'price']);
            if ($request->photo) {
                $data['image'] = 'images/products/' . $this->uploadProductPhoto($request->photo);
            }

            $data = $this->service->store(array_merge($data, ['user_id' => auth()->user()->id]));
            DB::commit();

            debug_log("Product created successfully!", $data);

            return api($data)->success('Product Created successfully!', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            debug_log("Product creation failed!", $e->getTrace());
            DB::rollback();

            return api()->fails($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $this->service->getById($id);

        return api($data)->success('Success!');
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'title' => 'required',
            'price' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(['title', 'description', 'price']);
            if ($request->photo) {
                $data['image'] = 'images/products/' . $this->uploadProductPhoto($request->photo);
            }

            $response = $this->service->update($id, $data);
            DB::commit();

            debug_log("Product updated successfully!", $data);

            return api($response)->success('Product Updated successfully!', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            debug_log("Product update failed!", $e->getTrace());
            DB::rollback();

            return api()->fails($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->service->destroy($id);

            debug_log("Product deleted successfully!", $data);

            return api($data)->success('Product Deleted Successfully!');
        } catch (Exception $e) {
            debug_log("Product deletion failed!", $e->getTrace());

            return api()->fails($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function uploadProductPhoto($image): string
    {
        if (!File::isDirectory(public_path('images/products'))) {
            File::makeDirectory(public_path('images/products'));
        }

        $type = explode(';', $image)[0];
        $type = explode('/', $type)[1];
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = time().'.'.$type;
        File::put(public_path('images/products'). '/' . $imageName, base64_decode($image));

        return $imageName;
    }
}

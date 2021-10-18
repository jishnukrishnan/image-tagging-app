<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageTagRegisterRequest;
use App\Http\Resources\ImageTagResource;
use App\Models\Image;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageTagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        throw new HttpException(501, 'API not implemented');
    }

    /**
     * Store a tag against an image
     *
     * @param ImageTagRegisterRequest $request
     * @param Image $image
     * @return Response stored tag
     */
    public function store(ImageTagRegisterRequest $request, Image $image): Response
    {
        if ($this->checkImageUser($image->id)) {
            throw new NotFoundHttpException();
        }

        $requestParamsArray = $request->post();

        $tag = new Tag();
        $tag->image_id = $image->id;
        $tag->label = $this->getAndRemove($requestParamsArray, 'label');

        $coordinates = $this->getAndRemove($requestParamsArray, 'coordinates');
        $tag->x_1 = $coordinates[0][0];
        $tag->y_1 = $coordinates[0][1];
        $tag->x_2 = $coordinates[1][0];
        $tag->y_2 = $coordinates[1][1];
        $tag->x_3 = $coordinates[2][0];
        $tag->y_3 = $coordinates[2][1];
        $tag->x_4 = $coordinates[3][0];
        $tag->y_4 = $coordinates[3][1];

        $tag->additional_field = key($requestParamsArray);
        $tag->additional_value = reset($requestParamsArray);
        $tag->save();

        return response(new ImageTagResource($tag), 201);
    }

    /**
     * Check if the image ID is registered with the user.
     * @param int $id image id
     * @return bool true if the current user is not image owner.
     */
    private function checkImageUser(int $id): bool
    {
        return Image::findOrFail($id)->user_id !== auth()->user()->id;
    }

    /**
     * Remove and return element at given key from an array
     * @param array $arr target array
     * @param String $key target key
     * @return mixed|null
     */
    private function getAndRemove(array &$arr, string $key)
    {
        $val = $arr[$key] ?? null;
        unset($arr[$key]);
        return $val;
    }

    /**
     * Display the specified resource.
     *
     * @param Tag $tag
     * @return Response
     */
    public function show(Tag $tag): Response
    {
        throw new HttpException(501, 'API not implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function update(Request $request, Tag $tag): Response
    {
        throw new HttpException(501, 'API not implemented');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     * @return Response
     */
    public function destroy(Tag $tag): Response
    {
        throw new HttpException(501, 'API not implemented');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Return list of all public images.
     *
     * @return ResourceCollection image resource collection
     */
    public function index(): ResourceCollection
    {
        return ImageResource::collection(Image::where('public', '=', true)->simplePaginate(config('app.pagination_limit')));
    }

    /**
     * Store an image in storage and make an entry in DB.
     *
     * @param ImageUploadRequest $request upload request
     * @return Response uploaded image details
     * @throws Exception e
     */
    public function store(ImageUploadRequest $request): Response
    {
        $image = new Image;
        $disk = $request->public ? 'public' : 'local';
        $path = Storage::disk($disk)->putFile(
            'images', $request->file('image')
        );

        try {
            $size = getimagesize($request->file('image'));
            $image->title = $request->file('image')->getClientOriginalName();
            $image->path = $path;
            $image->category = $request->category;
            $image->width = $size[0];
            $image->height = $size[1];
            $image->public = $request->public;
            $image->user_id = auth()->user()->id;
            $image->save();
        } catch (Exception $e) {
            Storage::delete($path);
            throw $e;
        }
        return response(new ImageResource($image), 201);
    }

    /**
     * Get an image with the tags associated with it.
     *
     * @param Image $image Image object with request ID
     * @return Response target image
     */
    public function show(Image $image): Response
    {
        $imageFromDb = Image::with('tags')->findOrFail($image->id);

        $user = auth('sanctum')->user();

        if (!$imageFromDb->public && ($user === null || $imageFromDb->user_id !== $user->id)) {
            throw new NotFoundHttpException();
        }
        return response(new ImageResource($imageFromDb));
    }

    /**
     * Get all images uploaded by the user.
     * @return ResourceCollection return a collection of image resource
     */
    public function getUserImages(): ResourceCollection
    {
        return ImageResource::collection(request()->user()->images()->simplePaginate(config('app.pagination_limit')));
    }

    /**
     * Fetch a private image from storage and return it to user.
     * @param Request $request image path and uid
     * @return Response Image file
     */
    public function view(Request $request): Response
    {
        try {
            $image_path_ability = PersonalAccessToken::findToken(urldecode($request->uid))->abilities[0];
            if ($request->path() === $image_path_ability) {
                return response()->file(storage_path('app/' . $request->path()));
            }
        } catch (Exception $e) {
            Log::error($e);
        }
        return response(null, 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function update(Request $request): void
    {
        throw new HttpException(501, 'API not implemented');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id): void
    {
        throw new HttpException(501, 'API not implemented');
    }
}

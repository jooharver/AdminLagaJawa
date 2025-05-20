<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsApiResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function index()
    {
        //get all posts
        $posts = News::latest()->paginate(5);

        //return collection of posts as a resource
        return new NewsApiResource(true, 'List Data News Coyyy', $posts);
    }

    public function show($id)
    {
        $post = News::find($id);

        // // Ambil hanya field tertentu
        // $data = [
        //     'judul' => $post->judul,
        //     'deskripsi' => $post->deskripsi,
        // ];

        return new NewsApiResource(true, 'Detail Data NEWS COYYY!', $post);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' => 'required',
            'sub_judul' => 'required',
            'tempat' => 'required',
            'tanggal' => 'required',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/news', $image->hashName());

        //create post
        $news = News::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
            'judul'     => $request->judul,
            'sub_judul' => $request->sub_judul,
            'deskripsi' => $request->deskripsi,
            'tempat'    => $request->tempat,
            'tanggal'   => $request->tanggal,
        ]);

        //return response
        return new NewsApiResource(true, 'Data Post Berhasil Ditambahkan!', $news);
    }

    public function update(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'judul'     => 'required',
            'sub_judul'   => 'required',
            'tempat' => 'required',
            'tanggal' => 'required',
            'deskripsi' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $post = News::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());

            //delete old image
            Storage::delete('public/news/' . basename($post->image));

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'judul'     => $request->judul,
                'sub_judul'   => $request->sub_judul,
                'tempat'     => $request->tempat,
                'tanggal'   => $request->tanggal,
                // 'deskripsi'     => $request->deskripsi,
            ]);
        } else {

            //update post without image
            $post->update([
                'judul'     => $request->judul,
                'sub_judul'   => $request->sub_judul,
                'tempat'     => $request->tempat,
                'tanggal'   => $request->tanggal,
                // 'deskripsi'     => $request->deskripsi,
            ]);
        }

        //return response
        return new NewsApiResource(true, 'Data Post Berhasil Diubah!', $post);
    }


    public function destroy($id)
    {

        //find post by ID
        $post = News::find($id);

        //delete image
        Storage::delete('public/news/'.basename($post->image));

        //delete post
        $post->delete();

        //return response
        return new NewsApiResource(true, 'Data News Berhasil Dihapus!', null);
    }

}

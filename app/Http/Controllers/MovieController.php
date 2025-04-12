<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    //
	public function index(){
		$movies = Movie::all();
		$movies->transform(function($movie){
			if($movie->poster){
				$movie->poster = Storage::url($movie->poster);
			}
			return $movie;
		});
		return response()->json(['movies' => $movies]);
	}
	
	public function store(Request $request){
		$credentials = Validator::make($request->all(),[
			'title' => 'required|string|max:255',
			'description' => 'required|string',
			'poster' => 'required|image|mimes:jpeg,png,jpg,gif',
		]);
		if($credentials->fails()){
			return response()->json([
				'message' => 'Validation failed',
				'errors' => $credentials->errors()
			],422);
		}
		if($request->hasFile('poster')){
			$filePath = $request->file('poster')->store('poster_photos','public');
			$movie = new Movie();
			$movie->title = $request->input('title');
			$movie->description = $request->input('description');
			$movie->poster = $filePath;
			$movie->save();
			$movie->poster = Storage::url($filePath);
			return response()->json([
				'message' => 'Movie created successfully',
				'movie' => $movie
			],201);
		}
		return response()->json(['message' => 'Poster upload failed or missing'],400);
	}
}

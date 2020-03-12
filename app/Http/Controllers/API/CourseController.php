<?php

namespace App\Http\Controllers\API;

use App\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        //
        $courses = Course::all();

        // return response
        $response = [
            'success' => true,
            'message' => 'Courses retrieved successfully',
            'courses' => $courses
        ];

        return response()->json(
            $response, 200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save request array as input
        $input = $request->all();

        // validate request
        $validator = Validator::make($input, [
            'title' => 'required',
            'code' => 'required',
        ]);

        // check if validation fails
        if ( $validator->fails() ) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }

        $course = Course::create($input);

        return response()->json(
            $response, 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::find($id);

        if ( is_null($course)) {
            $response = [
                'success' => false,
                'message' => 'Course not found'
            ];

            return response()->json($response, 404);
        }

        $response = [
            'success' => true,
            'message' => 'Courses retrieved succesfully.'
        ];

        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // save request array as input
        $input = $request->all();

        // validate request
        $validator = Validator::make($input, [
            'title' => 'required',
            'code' => 'required',
        ]);

        // check if validation fails
        if ( $validator->fails() ) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
        }

        $course = Course::find($id);
        $course->title = $input['title'];
        $course->code = $input['code'];
        $description = isset($input['description']) ? $input['description'] : null;
        $course->description = $description;

        $course->save(); // save the record

        // response array
        $response = [
            'success' => true,
            'message' => 'Course updated successfully'
        ];

        return response()->json($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id); // find course by id
        $course->delete(); // delete course

        // response array
        $response = [
            'success' => true,
            'message' => 'Course deleted successfully'
        ];

        return response()->json($response, 200);
    }
}

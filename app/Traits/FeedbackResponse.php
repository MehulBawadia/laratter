<?php

namespace Laratter\Traits;

trait FeedbackResponse
{
    /**
     * Give the success feedback to the user.
     *
     * @param  string $message
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($message = null, $data = [])
    {
        return response()->json(array_merge([
            'status' => 'success',
            'title' => 'Success !',
            'delay' => 3000,
            'message' => $message,
        ], $data));
    }

    /**
     * Give the failed feedback to the user.
     *
     * @param  string $message
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedResponse($message = null, $data = [])
    {
        return response()->json(array_merge([
            'status' => 'failed',
            'title' => 'Failed !',
            'delay' => 3000,
            'message' => $message,
        ], $data));
    }
}

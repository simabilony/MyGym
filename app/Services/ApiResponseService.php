<?php

namespace App\Services;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseService
{
    /**
     * @param mixed|null $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public static function success(mixed $data=null, string $message='opr succ', int $status=200): \Illuminate\Http\JsonResponse
    {
return response()->json([
      "status"=>'success',
     "message"=>$message,
     "data"=>$data
   ]  ,$status);
    }
    /**
 * @param string $message
     * @param int $status
     *  @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public static function error($message='opr fail',$status=400,$data=null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            "status"=>'success',
            "message"=>$message,
            "data"=>$data
        ]  ,$status);
    }
    /**
     *Return a paginated JSON response.
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator The paginator instance.
     * @param string $message The success message.
     * @param int $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     *
     *
     */
    public static function paginated (LengthAwarePaginator $paginator, string $message = 'Operation successful', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json( [
            'status' => 'success',
             'message' => trans(key: $message),

        'data' => $paginator->items(),
        'pagination'=>[
        'total' => $paginator->total(),
            'count' => $paginator->count(),
        'per_page' => $paginator->perPage(),
        'current_page' => $paginator->currentPage(),
        'total_pages' => $paginator->lastPage(),

],
], $status);}
}



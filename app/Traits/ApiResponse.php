<?php

namespace App\Traits;

use Illuminate\Http\Response as Res;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * Class ApiController
 */
trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return void response
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function respondCreated($message = '', $data = null)
    {
        if ($message == '' || is_null($message)) {
            $message = 'The creation operation was successful.';
        }

        return $this->respond([
            'status' => Res::HTTP_CREATED,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * @return mixed
     */
    protected function respondWithPagination(Paginator $paginate, $data, $message)
    {
        $data = array_merge($data, [
            'paginator' => [
                'total_count' => $paginate->total(),
                'total_pages' => ceil($paginate->total() / $paginate->perPage()),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ],
        ]);

        return $this->respond([
            'status' => Res::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->respond([
            'status' => Res::HTTP_NOT_FOUND,
            'message' => $message,
        ]);
    }

    public function respondForbidden($message = 'Access Denied!')
    {
        return $this->respond([
            'status' => Res::HTTP_FORBIDDEN,
            'message' => $message,
        ]);
    }

    public function respondInternalError($message = '')
    {
        if ($message == '' || is_null($message)) {
            $message = 'Operation encountered problems.';
        }

        return $this->respond([
            'status' => Res::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $message,
        ]);
    }

    public function respondValidationError($message = '', $errors = null)
    {
        if ($message == '' || is_null($message)) {
            $message = 'Operation was not valid.';
        }

        return $this->respond([
            'status' => Res::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $message,
            'data' => $errors,
        ]);
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message = '')
    {
        if ($message == '' || is_null($message)) {
            $message = 'The operation encountered an error.';
        }

        return $this->respond([
            'status' => $this->getStatusCode(),
            'message' => $message,
        ]);
    }

    public function respondErrorWithCode($code, $message = '', $data = null)
    {
        if ($message == '' || is_null($message)) {
            $message = 'The operation encountered an error.';
        }

        return $this->respond([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function respondSuccess($message = '', $data = null)
    {
        if ($message == '' || is_null($message)) {
            $message = 'The operation was successful.';
        }

        return $this->respond([
            'status' => Res::HTTP_OK,
            'message' => $message,
            'data' => $data,
        ]);
    }
}

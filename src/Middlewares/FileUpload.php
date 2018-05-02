<?php
namespace Xpromx\GraphQL\Middlewares;

use Closure;

class FileUpload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $contentType = $request->header('content-type') ?? '';
        if (mb_stripos($contentType, 'multipart/form-data') !== false) {
            $this->validateRequest($request);
            $operations = json_decode($request->get('operations'), true);
            $map = json_decode($request->get('map'), true);
            $files = $request->file();
            if (isset($operations['operationName'])) {
                $operations['operation'] = $operations['operationName'];
                unset($operations['operationName']);
            }
            foreach ($map as $fileKey => $locations) {
                foreach ($locations as $location) {
                    $key = array_last(explode('.', $location));
                    $file = $request->file($key);
                    $operations['variables'][$key] = [
                      'available' => true
                    ];
                }
            }
            $request->headers->add(['content-type' => 'application/json']);
            $request->replace($operations);
        }
        return $next($request);
    }
    private function validateRequest($request)
    {
        if (!$request->has('operations')) {
            throw new \Exception('Request is expected to provide an operation');
        }
        if (!$request->has('map')) {
            throw new \Exception('Request is expected to provide a map');
        }
    }
}

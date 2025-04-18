<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    /**
     * Handle a controller action with try-catch and logging.
     *
     * @return mixed
     */
    /**
     * @param  bool  $useTransaction  If true, wraps callback in DB::transaction
     */
    protected function handleResourceAction(callable $callback, string $action, string $resource, string $successMsg = '', string $errorMsg = '', array $extraContext = [], bool $useTransaction = false)
    {
        try {
            $runner = $callback;
            if ($useTransaction) {
                $runner = function () use ($callback) {
                    return DB::transaction($callback);
                };
            }
            $result = $runner();
            if ($successMsg) {
                Log::channel('resource')->info($successMsg, array_merge(['action' => $action, 'resource' => $resource], $extraContext));
            }

            return $result;
        } catch (Exception $e) {
            Log::channel('resource')->error($errorMsg ?: ("Error during {$action} on {$resource}: " . $e->getMessage()), array_merge(['action' => $action, 'resource' => $resource, 'message' => $e->getMessage(), 'trace' => $e->getTrace()], $extraContext));

            return back()->withErrors(['error' => $errorMsg ?: 'An error occurred. Please try again.'])->withInput();
        }
    }
}

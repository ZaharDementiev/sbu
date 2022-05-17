<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

function cpu_count(): int
{
    if ('darwin' === strtolower(PHP_OS)) {
        $count = shell_exec('sysctl -n machdep.cpu.core_count');
    } else {
        $count = shell_exec('nproc');
    }

    return (int)$count > 0 ? (int)$count : 4;
}

function toArr(mixed $data): array
{
    if (is_string($data)) {
        return explode(',', $data);
    }

    if (is_object($data) && method_exists($data, 'toArray')) {
        return $data->toArray();
    }

    return is_array($data) ? $data : [];
}

function toStr(mixed $data, string $default = ''): string
{
    if (is_string($data)) {
        return $data;
    }
    if (is_numeric($data) || $data instanceof Stringable) {
        return (string)$data;
    }

    return $default;
}

function toInt(mixed $data, int $default = 0): int
{
    return is_numeric($data) ? (int)$data : $default;
}

function toFloat(mixed $data, float $default = 0.00): float
{
    return is_numeric($data) ? (float)$data : $default;
}

function arrToStr(array $data): string
{
    return 0 === count($data) ? '' : '["' . implode('","', $data) . '"]';
}

function unmarshal(string $class, array $data): mixed
{
    $reflection = new ReflectionClass($class);

    if (!$reflection->getConstructor()) {
        throw new RuntimeException("Constructor must be specified to unmarshal {$class}");
    }

    if (!$reflection->getConstructor()->isPublic()) {
        throw new RuntimeException("Constructor must be public to unmarshal {$class}");
    }

    $properties = [];
    foreach ($reflection->getConstructor()->getParameters() as $parameter) {
        if ($parameter->allowsNull() && !isset($data[$parameter->getName()])) {
            $properties[] = null;
        } else {
            $properties[] = $data[$parameter->getName()] ??
                throw new RuntimeException("Parameter {$parameter->getName()} must be given to unmarshal {$class}");
        }
    }

    return new $class(...$properties);
}

function marshal(object $object, bool $omitEmpty = false, bool $snake = true): array
{
    $reflection = new ReflectionClass($object);

    if (!$reflection->getConstructor()) {
        throw new RuntimeException('Constructor must be specified to marshal ' . $object::class);
    }

    $data = [];
    foreach ($reflection->getConstructor()->getParameters() as $parameter) {
        $property = $reflection->getProperty($parameter->getName());

        if (!$parameter->isPromoted() || (null === $property->getValue($object) && $omitEmpty)) {
            continue;
        }

        $property->setAccessible(true);
        $data[$parameter->getName()] = $property->getValue($object);
    }

    return $snake ? camelToSnake($data) : $data;
}

function snakeToCamel(array $array): array
{
    foreach ($array as $key => $value) {
        unset($array[$key]);
        $array[lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))))] = $value;
    }

    return $array;
}

function camelToSnake(array $array): array
{
    foreach ($array as $key => $value) {
        unset($array[$key]);
        $array[strtolower(toStr(preg_replace('/[A-Z]/', '_$0', lcfirst($key))))] = $value;
    }

    return $array;
}

function responseError(int $code = 500, string $msg = '_FAILED_'): JsonResponse
{
    return response()->json([
        'code' => $code,
        'msg' => trans($msg),
        'data' => null,
    ]);
}

function responseSuccess(mixed $data = null, string $msg = '_SUCCESS_'): JsonResponse
{
    return response()->json([
        'code' => Response::HTTP_OK,
        'msg' => trans($msg),
        'data' => $data,
    ]);
}

function bearer(string $authorization): string
{
    return trim(substr($authorization, 7));
}

function yearsDifference(string $date): int
{
    return Carbon::now()
        ->diff(Carbon::now()->subDays((strtotime(Carbon::now()->toDateString()) - strtotime($date)) / (60 * 60 * 24)))
        ->y;
}

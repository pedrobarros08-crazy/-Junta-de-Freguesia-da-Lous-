<?php

function cleanup_sqlsrv($conn, ...$statements): void
{
    foreach ($statements as $statement) {
        if ($statement) {
            sqlsrv_free_stmt($statement);
        }
    }

    if ($conn) {
        sqlsrv_close($conn);
    }
}

function redirect_with_status(string $path, array $queryParams, string $status, string $message): void
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $queryParams['status'] = $status;
    $queryParams['message'] = $message;

    $query = http_build_query($queryParams);
    header('Location: ' . $path . ($query !== '' ? '?' . $query : ''));
    exit;
}

function format_sqlsrv_date($value): string
{
    if ($value instanceof DateTimeInterface) {
        return $value->format('d/m/Y');
    }

    $rawDate = (string) $value;
    $ts = strtotime($rawDate);
    return $ts !== false ? date('d/m/Y', $ts) : $rawDate;
}

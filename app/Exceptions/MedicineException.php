<?php

namespace App\Exceptions;

use Exception;

class MedicineException extends Exception
{
    public static function notFound(int $id): self
    {
        return new self("Medicine with ID {$id} not found.", 404);
    }

    public static function insufficientStock(string $medicineName, int $available, int $requested): self
    {
        return new self(
            "Insufficient stock for {$medicineName}. Available: {$available}, Requested: {$requested}",
            422
        );
    }

    public static function updateFailed(string $reason = ''): self
    {
        $message = 'Failed to update medicine';
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message, 500);
    }

    public static function createFailed(string $reason = ''): self
    {
        $message = 'Failed to create medicine';
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message, 500);
    }

    public static function deleteFailed(string $reason = ''): self
    {
        $message = 'Failed to delete medicine';
        if ($reason) {
            $message .= ": {$reason}";
        }
        return new self($message, 500);
    }
}

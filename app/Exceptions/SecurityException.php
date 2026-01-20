<?php

namespace App\Exceptions;

use Exception;

class SecurityException extends Exception
{
    /**
     * User not found.
     */
    public static function userNotFound(int $id): self
    {
        return new self("Pengguna dengan ID {$id} tidak dijumpai.", 404);
    }

    /**
     * Invalid credentials.
     */
    public static function invalidCredentials(): self
    {
        return new self('Nama pengguna atau kata laluan tidak sah.', 401);
    }

    /**
     * Account locked.
     */
    public static function accountLocked(int $minutes): self
    {
        return new self("Akaun dikunci. Sila cuba lagi dalam {$minutes} minit.", 423);
    }

    /**
     * Account inactive.
     */
    public static function accountInactive(): self
    {
        return new self('Akaun tidak aktif. Sila hubungi pentadbir.', 403);
    }

    /**
     * Account suspended.
     */
    public static function accountSuspended(): self
    {
        return new self('Akaun telah digantung. Sila hubungi pentadbir.', 403);
    }

    /**
     * MFA required.
     */
    public static function mfaRequired(): self
    {
        return new self('Pengesahan dua faktor diperlukan.', 403);
    }

    /**
     * Invalid MFA code.
     */
    public static function invalidMfaCode(): self
    {
        return new self('Kod pengesahan tidak sah.', 401);
    }

    /**
     * MFA not configured.
     */
    public static function mfaNotConfigured(): self
    {
        return new self('Pengesahan dua faktor belum dikonfigurasikan.', 400);
    }

    /**
     * Password expired.
     */
    public static function passwordExpired(): self
    {
        return new self('Kata laluan telah tamat tempoh. Sila tukar kata laluan.', 403);
    }

    /**
     * Password reuse.
     */
    public static function passwordReuse(): self
    {
        return new self('Kata laluan telah digunakan sebelum ini. Sila gunakan kata laluan baharu.', 400);
    }

    /**
     * Password weak.
     */
    public static function passwordWeak(): self
    {
        return new self('Kata laluan tidak memenuhi keperluan keselamatan.', 400);
    }

    /**
     * IP not whitelisted.
     */
    public static function ipNotWhitelisted(string $ip): self
    {
        return new self("Akses ditolak. Alamat IP {$ip} tidak dalam senarai putih.", 403);
    }

    /**
     * Cannot delete last super admin.
     */
    public static function cannotDeleteLastSuperAdmin(): self
    {
        return new self('Tidak boleh memadam Super Admin terakhir.', 400);
    }

    /**
     * Cannot deactivate last super admin.
     */
    public static function cannotDeactivateLastSuperAdmin(): self
    {
        return new self('Tidak boleh menyahaktifkan Super Admin terakhir.', 400);
    }

    /**
     * Cannot modify own admin role.
     */
    public static function cannotModifyOwnAdminRole(): self
    {
        return new self('Tidak boleh mengubah peranan Super Admin anda sendiri.', 400);
    }

    /**
     * Session expired.
     */
    public static function sessionExpired(): self
    {
        return new self('Sesi telah tamat. Sila log masuk semula.', 401);
    }

    /**
     * Unauthorized access.
     */
    public static function unauthorized(): self
    {
        return new self('Akses tidak dibenarkan.', 403);
    }

    /**
     * Role has users.
     */
    public static function roleHasUsers(string $roleName, int $userCount): self
    {
        return new self("Peranan '{$roleName}' masih mempunyai {$userCount} pengguna. Sila alihkan pengguna dahulu.", 400);
    }

    /**
     * Backup failed.
     */
    public static function backupFailed(string $reason = ''): self
    {
        $message = 'Backup gagal';
        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self($message, 500);
    }

    /**
     * Restore failed.
     */
    public static function restoreFailed(string $reason = ''): self
    {
        $message = 'Pemulihan gagal';
        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self($message, 500);
    }
}

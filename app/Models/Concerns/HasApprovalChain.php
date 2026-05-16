<?php

namespace App\Models\Concerns;

/**
 * Sequential 3-tier approval: Secretary → Kagawad → Captain.
 *
 * The host model must declare these columns:
 *   approved_by_secretary[_id|_at], approved_by_kagawad[_id|_at],
 *   approved_by_captain[_id|_at], status (enum incl. pending/approved/rejected),
 *   rejection_reason.
 */
trait HasApprovalChain
{
    public function isPending(): bool
    {
        return ($this->status ?? null) === 'pending';
    }

    public function isApproved(): bool
    {
        return ($this->status ?? null) === 'approved';
    }

    public function nextApprover(): string
    {
        if (! $this->approved_by_secretary) return 'secretary';
        if (! $this->approved_by_kagawad)   return 'kagawad';
        if (! $this->approved_by_captain)   return 'captain';
        return 'none';
    }

    public function canBeApprovedBy(string $role): bool
    {
        return $this->nextApprover() === $role;
    }

    public function markApproved(string $role, int $userId): void
    {
        $this->{"approved_by_{$role}"}       = true;
        $this->{"approved_by_{$role}_id"}    = $userId;
        $this->{"approved_by_{$role}_at"}    = now();

        if ($this->nextApprover() === 'none') {
            $this->status = 'approved';
        }
        $this->save();
    }

    public function markRejected(string $reason): void
    {
        $this->status = 'rejected';
        $this->rejection_reason = $reason;
        $this->save();
    }
}

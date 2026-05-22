<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function transfromAudit(array $data): array{
        $data['reason'] = request()->input('reason');
        return $data;
    }
}

<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    /**
     * Get paginated companies with optional search filter.
     */
    public function getPaginatedWithSearch(?string $search = null, int $perPage = 15)
    {
        return Company::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Company
    {
        $company = Company::create($data);

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            $company->attachFile($data['logo'], 'logo', 'public');
        }

        return $company;
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old logo if you want, or just attach new
            if ($oldLogo = $company->getLatestAttachment('logo')) {
                // Delete old file optionally: Storage::disk($oldLogo->disk)->delete($oldLogo->file_path);
                // $oldLogo->delete();
            }
            $company->attachFile($data['logo'], 'logo', 'public');
        }

        return $company;
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }
}

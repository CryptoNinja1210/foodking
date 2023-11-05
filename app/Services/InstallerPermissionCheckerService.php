<?php

namespace App\Services;

class InstallerPermissionCheckerService
{

    protected array $results = [];

    public function __construct()
    {
        $this->results['permissions'] = [];
        $this->results['errors']      = null;
    }

    public function check(array $folders) : array
    {
        foreach ($folders as $folder => $permission) {
            if (!($this->getPermission($folder) >= $permission)) {
                $this->addFileAndSetErrors($folder, $permission, false);
            } else {
                $this->addFile($folder, $permission, true);
            }
        }
        return $this->results;
    }

    private function getPermission($folder) : string
    {
        return substr(sprintf('%o', fileperms(base_path($folder))), -4);
    }

    private function addFile($folder, $permission, $isSet)
    {
        array_push($this->results['permissions'], [
            'folder'     => $folder,
            'permission' => $permission,
            'isSet'      => $isSet,
        ]);
    }

    private function addFileAndSetErrors($folder, $permission, $isSet)
    {
        $this->addFile($folder, $permission, $isSet);

        $this->results['errors'] = true;
    }
}

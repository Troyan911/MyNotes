<?php

namespace App\Controllers\Api;

use App\Models\Folder;
use App\Validators\Folders\CreateFolderValidator;

class FoldersController extends BaseApiController
{
    public function index()
    {
        return$this->response(
            200,
            Folder::where('user_id', '=', authId())
        );

    }

    public function show(int $id)
    {
        dd(__METHOD__);

    }

    public function store()
    {
        $data = array_merge(
            requestBody(),
            ['user_id' => authId()]
        );

        $validator = new CreateFolderValidator();

        if($validator->validate($data) && $folder = Folder::create($data)) {
            return $this->response(200, $folder->toArray());
        }
        return $this->response(200, [], $validator->getErrors());

    }

    public function update(int $id)
    {
        $folder = Folder::find($id);

        if($folder && is_null($folder->user_id) && $folder->user_id !== authId()) {
            return $this->response(403, [], ['message' => 'This resource is forbidden for you']);
        }

        $data = requestBody();
        $folder->update($data);

    }

    public function destroy(int $id)
    {
        dd(__METHOD__);

    }

}
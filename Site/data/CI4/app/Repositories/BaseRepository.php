<?php

namespace App\Repositories;

use CodeIgniter\Model;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): array
    {
        return $this->model->findAll();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findBy(array $where): array
    {
        return $this->model->where($where)->findAll();
    }

    public function create(array $data)
    {
        return $this->model->insert($data);
    }

    public function update($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function paginate(int $perPage = 20, string $group = 'default')
    {
        return $this->model->paginate($perPage, $group);
    }
}
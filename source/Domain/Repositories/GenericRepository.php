<?php

declare(strict_types=1);

namespace Source\Domain\Repositories;

use PDOException;

interface GenericRepository
{
    public function data(): ?object;
    public function fail(): ?PDOException;
    public function message(): ?object;
    public function entityAlias(string $alias): static;
    public function join(string $type, string $alias, self $class, string $term): static;
    public function find(?string $terms, ?string $params, string $columns = "*"): static;
    public function findById(int $id): ?static;
    public function fetch(bool $all = false): ?static;
    public function order(string $columnOrder): static;
    public function group(string $columnGroup): static;
    public function limit(int $limit): static;
    public function offset(int $offset): static;
    public function count(?string $key): int;
    public function save(): bool;
    public function lastId(): int;
    public function delete(string $terms, ?string $params): bool;
    public function destroy(): bool;
}